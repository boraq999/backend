<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\MainStock;
use App\Models\MarketerRequest;
use App\Models\MarketerRequestStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index()
    {
        // موثق - له delivery_confirmation
        $documentedRequests = MarketerRequest::with(['marketer', 'items.product', 'status.keeper'])
            ->whereHas('status', function($q) {
                $q->where('status', 'approved');
            })
            ->whereExists(function($q) {
                $q->select(DB::raw(1))
                  ->from('delivery_confirmation')
                  ->whereRaw('delivery_confirmation.request_id = marketer_requests.id');
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        // في انتظار التوثيق - موافق عليه ولكن لا يوجد delivery_confirmation
        $waitingDocRequests = MarketerRequest::with(['marketer', 'items.product', 'status.keeper'])
            ->whereHas('status', function($q) {
                $q->where('status', 'approved');
            })
            ->whereNotExists(function($q) {
                $q->select(DB::raw(1))
                  ->from('delivery_confirmation')
                  ->whereRaw('delivery_confirmation.request_id = marketer_requests.id');
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        // في انتظار الموافقة - pending أو لا يوجد status
        $pendingRequests = MarketerRequest::with(['marketer', 'items.product', 'status.keeper'])
            ->where(function($q) {
                $q->whereDoesntHave('status')
                  ->orWhereHas('status', function($subQ) {
                      $subQ->where('status', 'pending');
                  });
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        // مرفوضة - rejected أو cancelled
        $rejectedRequests = MarketerRequest::with(['marketer', 'items.product', 'status.keeper'])
            ->whereHas('status', function($q) {
                $q->whereIn('status', ['rejected', 'cancelled']);
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        $mainStock = DB::table('main_stock')
            ->join('products', 'main_stock.product_id', '=', 'products.id')
            ->select('products.name', 'main_stock.quantity', 'products.current_price')
            ->orderBy('products.name')
            ->get();
            
        $documentedCount = $documentedRequests->count();
        $waitingDocCount = $waitingDocRequests->count();
        $pendingCount = $pendingRequests->count();
        $rejectedCount = $rejectedRequests->count();

        return view('warehouse.requests.index', compact(
            'documentedRequests', 'waitingDocRequests', 'pendingRequests', 'rejectedRequests',
            'documentedCount', 'waitingDocCount', 'pendingCount', 'rejectedCount', 'mainStock'
        ));
    }

    public function show($id)
    {
        $request = MarketerRequest::with(['marketer', 'items.product', 'status.keeper'])
            ->findOrFail($id);
            
        $documentInfo = DB::table('delivery_confirmation')
            ->where('request_id', $id)
            ->first();

        return view('warehouse.requests.show', compact('request', 'documentInfo'));
    }

    public function approve($id)
    {
        $marketerRequest = MarketerRequest::with('items.product')->findOrFail($id);

        foreach ($marketerRequest->items as $item) {
            $mainStock = MainStock::where('product_id', $item->product_id)->first();
            if (!$mainStock || $mainStock->quantity < $item->quantity) {
                return redirect()->back()->with('error', 'الكمية المطلوبة غير متوفرة للمنتج: ' . $item->product->name);
            }
        }

        DB::transaction(function() use ($marketerRequest) {
            MarketerRequestStatus::create([
                'request_id' => $marketerRequest->id,
                'marketer_id' => $marketerRequest->marketer_id,
                'keeper_id' => Auth::id(),
                'status' => 'approved'
            ]);

            foreach ($marketerRequest->items as $item) {
                DB::table('main_stock')
                    ->where('product_id', $item->product_id)
                    ->decrement('quantity', $item->quantity);

                DB::table('marketer_reserved_stock')->updateOrInsert(
                    [
                        'marketer_id' => $marketerRequest->marketer_id,
                        'product_id' => $item->product_id
                    ],
                    [
                        'reserved_quantity' => DB::raw('COALESCE(reserved_quantity, 0) + ' . $item->quantity)
                    ]
                );
            }
        });

        return redirect()->back()->with('success', 'تم الموافقة على الطلب وتحويل البضاعة للمخزن المحجوز');
    }

    public function reject(Request $request, $id)
    {
        $marketerRequest = MarketerRequest::findOrFail($id);

        MarketerRequestStatus::create([
            'request_id' => $marketerRequest->id,
            'marketer_id' => $marketerRequest->marketer_id,
            'keeper_id' => Auth::id(),
            'status' => 'rejected',
            'reason' => $request->rejection_reason
        ]);

        return redirect()->back()->with('success', 'تم رفض الطلب');
    }

    public function uploadDocument($id)
    {
        $request = MarketerRequest::with(['marketer', 'items.product', 'status'])
            ->findOrFail($id);
            
        if (!$request->status || $request->status->status !== 'approved') {
            return redirect()->back()->with('error', 'يجب الموافقة على الطلب أولاً');
        }

        return view('warehouse.requests.upload-document', compact('request'));
    }

    public function storeDocument(Request $request, $id)
    {
        $request->validate([
            'document_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $marketerRequest = MarketerRequest::with('items')->findOrFail($id);

        $fileName = 'invoice_' . time() . '.' . $request->file('document_image')->getClientOriginalExtension();
        $imagePath = $request->file('document_image')->storeAs(
            'requests/' . $marketerRequest->invoice_number, 
            $fileName, 
            'public'
        );

        DB::transaction(function() use ($marketerRequest, $imagePath) {
            foreach ($marketerRequest->items as $item) {
                DB::table('marketer_reserved_stock')
                    ->where('marketer_id', $marketerRequest->marketer_id)
                    ->where('product_id', $item->product_id)
                    ->decrement('reserved_quantity', $item->quantity);

                DB::table('marketer_actual_stock')->updateOrInsert(
                    [
                        'marketer_id' => $marketerRequest->marketer_id,
                        'product_id' => $item->product_id
                    ],
                    [
                        'quantity' => DB::raw('COALESCE(quantity, 0) + ' . $item->quantity)
                    ]
                );
            }

            DB::table('delivery_confirmation')->insert([
                'request_id' => $marketerRequest->id,
                'keeper_id' => Auth::id(),
                'signed_image' => $imagePath,
                'confirmed_at' => now()
            ]);
        });

        return redirect()->route('warehouse.requests.index')->with('success', 'تم توثيق الطلب ونقل البضاعة للمخزن الفعلي');
    }

    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $marketerRequest = MarketerRequest::with('items')->findOrFail($id);
        
        if (!$marketerRequest->status || $marketerRequest->status->status !== 'approved') {
            return redirect()->back()->with('error', 'لا يمكن إلغاء هذا الطلب');
        }
        
        $documentExists = DB::table('delivery_confirmation')
            ->where('request_id', $id)
            ->exists();
            
        if ($documentExists) {
            return redirect()->back()->with('error', 'لا يمكن إلغاء طلب موثق');
        }

        DB::transaction(function() use ($marketerRequest, $request) {
            // 1️⃣ تحديث حالة الطلب إلى cancelled
            $marketerRequest->status()->update([
                'status' => 'cancelled',
                'reason' => $request->cancellation_reason
            ]);

            // 2️⃣ إرجاع الكمية من الحجز إلى المخزن الرئيسي
            foreach ($marketerRequest->items as $item) {
                // خصم من الحجز
                DB::table('marketer_reserved_stock')
                    ->where('marketer_id', $marketerRequest->marketer_id)
                    ->where('product_id', $item->product_id)
                    ->decrement('reserved_quantity', $item->quantity);

                // إضافة للمخزن الرئيسي
                DB::table('main_stock')
                    ->where('product_id', $item->product_id)
                    ->increment('quantity', $item->quantity);

                // 4️⃣ توثيق الحركة في warehouse_stock_logs
                DB::table('warehouse_stock_logs')->insert([
                    'action' => 'return_from_reservation',
                    'invoice_type' => 'marketer_request',
                    'invoice_id' => $marketerRequest->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'keeper_id' => Auth::id(),
                    'created_at' => now()
                ]);
            }
        });

        return redirect()->route('warehouse.requests.index')->with('success', 'تم إلغاء الطلب وإرجاع الكمية للمخزن الرئيسي');
    }
}