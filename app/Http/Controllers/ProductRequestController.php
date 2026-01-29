<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\MainStock;
use App\Models\MarketerRequest;
use App\Models\MarketerRequestItem;
use App\Models\MarketerRequestStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductRequestController extends Controller
{
    // صفحة إنشاء طلب جديد للمسوق
    public function create()
    {
        $products = Product::with('mainStock')
            ->where('is_active', true)
            ->get();

        return view('requests.create', compact('products'));
    }

    // إرسال طلب جديد
    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1'
        ]);

        DB::transaction(function() use ($request) {
            $marketerRequest = MarketerRequest::create([
                'invoice_number' => 'REQ-' . time() . '-' . Auth::id(),
                'marketer_id' => Auth::id()
            ]);

            foreach ($request->products as $product) {
                MarketerRequestItem::create([
                    'request_id' => $marketerRequest->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity']
                ]);
            }
        });

        return redirect()->route('requests.index')->with('success', 'تم إرسال الطلب بنجاح');
    }

    // عرض طلبات المسوق
    public function index()
    {
        if (Auth::user()->isSalesman()) {
            $requests = MarketerRequest::with(['items.product', 'status'])
                ->where('marketer_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
                
            // جلب مخزون المسوق
            $actualStock = DB::table('marketer_actual_stock')
                ->join('products', 'marketer_actual_stock.product_id', '=', 'products.id')
                ->where('marketer_id', Auth::id())
                ->select('products.name', 'marketer_actual_stock.quantity', 'products.current_price')
                ->get();
                
            $reservedStock = DB::table('marketer_reserved_stock')
                ->join('products', 'marketer_reserved_stock.product_id', '=', 'products.id')
                ->where('marketer_id', Auth::id())
                ->select('products.name', 'marketer_reserved_stock.reserved_quantity', 'products.current_price')
                ->get();
                
            return view('requests.index', compact('requests', 'actualStock', 'reservedStock'));
        } else {
            $requests = MarketerRequest::with(['marketer', 'items.product', 'status'])
                ->orderBy('created_at', 'desc')
                ->get();
                
            // جلب المخزن الرئيسي
            $mainStock = DB::table('main_stock')
                ->join('products', 'main_stock.product_id', '=', 'products.id')
                ->select('products.name', 'main_stock.quantity', 'products.current_price')
                ->orderBy('products.name')
                ->get();
                
            return view('requests.index', compact('requests', 'mainStock'));
        }
    }

    // عرض تفاصيل طلب
    public function show($id)
    {
        $request = MarketerRequest::with(['marketer', 'items.product', 'status.keeper'])
            ->findOrFail($id);
            
        // جلب معلومات التوثيق
        $documentInfo = DB::table('delivery_confirmation')
            ->where('request_id', $id)
            ->first();

        return view('requests.show', compact('request', 'documentInfo'));
    }

    // موافقة أمين المخزن على الطلب
    public function approve($id)
    {
        $marketerRequest = MarketerRequest::with('items.product')->findOrFail($id);
        
        if (!Auth::user()->isWarehouseKeeper()) {
            return redirect()->back()->with('error', 'غير مصرح لك بهذا الإجراء');
        }

        // التحقق من توفر الكميات
        foreach ($marketerRequest->items as $item) {
            $mainStock = MainStock::where('product_id', $item->product_id)->first();
            if (!$mainStock || $mainStock->quantity < $item->quantity) {
                return redirect()->back()->with('error', 'الكمية المطلوبة غير متوفرة للمنتج: ' . $item->product->name);
            }
        }

        DB::transaction(function() use ($marketerRequest) {
            // إضافة حالة الطلب
            MarketerRequestStatus::create([
                'request_id' => $marketerRequest->id,
                'marketer_id' => $marketerRequest->marketer_id,
                'keeper_id' => Auth::id(),
                'status' => 'approved'
            ]);

            // خصم من المخزن الرئيسي وإضافة للمخزن المحجوز
            foreach ($marketerRequest->items as $item) {
                // خصم من المخزن الرئيسي
                DB::table('main_stock')
                    ->where('product_id', $item->product_id)
                    ->decrement('quantity', $item->quantity);

                // إضافة للمخزن المحجوز
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

    // رفض أمين المخزن للطلب
    public function reject(Request $request, $id)
    {
        $marketerRequest = MarketerRequest::findOrFail($id);
        
        if (!Auth::user()->isWarehouseKeeper()) {
            return redirect()->back()->with('error', 'غير مصرح لك بهذا الإجراء');
        }

        MarketerRequestStatus::create([
            'request_id' => $marketerRequest->id,
            'marketer_id' => $marketerRequest->marketer_id,
            'keeper_id' => Auth::id(),
            'status' => 'rejected'
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

        return view('requests.upload-document', compact('request'));
    }

    public function storeDocument(Request $request, $id)
    {
        $request->validate([
            'document_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $marketerRequest = MarketerRequest::with('items')->findOrFail($id);
        
        if (!Auth::user()->isWarehouseKeeper()) {
            return redirect()->back()->with('error', 'غير مصرح لك بهذا الإجراء');
        }

        $fileName = 'invoice_' . time() . '.' . $request->file('document_image')->getClientOriginalExtension();
        $imagePath = $request->file('document_image')->storeAs(
            'requests/' . $marketerRequest->invoice_number, 
            $fileName, 
            'public'
        );

        DB::transaction(function() use ($marketerRequest, $imagePath) {
            // تحديث حالة الطلب إلى موثق (نستخدم approved لأن documented غير موجود في enum)
            // سنعتمد على جدول delivery_confirmation لمعرفة أن الطلب موثق

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

        return redirect()->route('requests.index')->with('success', 'تم توثيق الطلب ونقل البضاعة للمخزن الفعلي');
    }
}