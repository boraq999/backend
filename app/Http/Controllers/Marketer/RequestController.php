<?php

namespace App\Http\Controllers\Marketer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\MarketerRequest;
use App\Models\MarketerRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RequestController extends Controller
{
    public function index()
    {
        $marketerId = Auth::id();
        
        $documentedRequests = MarketerRequest::with(['items.product', 'status.keeper'])
            ->where('marketer_id', $marketerId)
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
            
        $waitingDocRequests = MarketerRequest::with(['items.product', 'status.keeper'])
            ->where('marketer_id', $marketerId)
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
            
        $pendingRequests = MarketerRequest::with(['items.product', 'status.keeper'])
            ->where('marketer_id', $marketerId)
            ->where(function($q) {
                $q->whereDoesntHave('status')
                  ->orWhereHas('status', function($subQ) {
                      $subQ->where('status', 'pending');
                  });
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        $rejectedRequests = MarketerRequest::with(['items.product', 'status.keeper'])
            ->where('marketer_id', $marketerId)
            ->whereHas('status', function($q) {
                $q->whereIn('status', ['rejected', 'cancelled']);
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        $documentedCount = $documentedRequests->count();
        $waitingDocCount = $waitingDocRequests->count();
        $pendingCount = $pendingRequests->count();
        $rejectedCount = $rejectedRequests->count();

        return view('marketer.requests.index', compact(
            'documentedRequests', 'waitingDocRequests', 'pendingRequests', 'rejectedRequests',
            'documentedCount', 'waitingDocCount', 'pendingCount', 'rejectedCount'
        ));
    }

    public function create()
    {
        $products = Product::with('mainStock')
            ->where('is_active', true)
            ->get();

        return view('marketer.requests.create', compact('products'));
    }

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

        return redirect()->route('marketer.requests.index')->with('success', 'تم إرسال الطلب بنجاح');
    }

    public function show($id)
    {
        $request = MarketerRequest::with(['marketer', 'items.product', 'status.keeper'])
            ->where('marketer_id', Auth::id())
            ->findOrFail($id);
            
        $documentInfo = DB::table('delivery_confirmation')
            ->where('request_id', $id)
            ->first();

        return view('marketer.requests.show', compact('request', 'documentInfo'));
    }

    public function cancel($id)
    {
        $request = MarketerRequest::where('marketer_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($request->status && $request->status->status !== 'pending') {
            return redirect()->back()->with('error', 'لا يمكن إلغاء طلب تم اتخاذ قرار بشأنه');
        }

        $request->delete();
        
        return redirect()->route('marketer.requests.index')->with('success', 'تم إلغاء الطلب بنجاح');
    }

    public function printInvoice($id)
    {
        $request = MarketerRequest::with(['marketer', 'items.product', 'status.keeper'])
            ->where('marketer_id', Auth::id())
            ->findOrFail($id);

        $documentInfo = DB::table('delivery_confirmation')
            ->where('request_id', $id)
            ->first();

        $arabic = new \ArPHP\I18N\Arabic();

        $data = [
            'invoiceNumber' => $request->invoice_number,
            'date' => \Carbon\Carbon::parse($request->created_at)->format('Y-m-d H:i'),
            'marketerName' => $arabic->utf8Glyphs($request->marketer->full_name ?? 'غير محدد'),
            'status' => $arabic->utf8Glyphs($request->status->status ?? 'قيد المراجعة'),
            'keeperName' => $request->status && $request->status->keeper ? $arabic->utf8Glyphs($request->status->keeper->full_name) : '',
            'statusDate' => $request->status ? \Carbon\Carbon::parse($request->status->updated_at)->format('Y-m-d H:i') : '',
            'items' => $request->items->map(function($item) use ($arabic) {
                return (object)[
                    'name' => $arabic->utf8Glyphs($item->product->name),
                    'quantity' => $item->quantity,
                    'price' => $item->product->current_price,
                    'total' => $item->quantity * $item->product->current_price
                ];
            }),
            'total' => $request->items->sum(fn($item) => $item->quantity * $item->product->current_price),
            'title' => $arabic->utf8Glyphs('فاتورة طلب بضاعة'),
            'labels' => [
                'invoiceNumber' => $arabic->utf8Glyphs('رقم الفاتورة'),
                'date' => $arabic->utf8Glyphs('تاريخ الطلب'),
                'marketer' => $arabic->utf8Glyphs('اسم المسوق'),
                'status' => $arabic->utf8Glyphs('حالة الطلب'),
                'keeper' => $arabic->utf8Glyphs('أمين المخزن'),
                'statusDate' => $arabic->utf8Glyphs('تاريخ المعالجة'),
                'product' => $arabic->utf8Glyphs('اسم المنتج'),
                'quantity' => $arabic->utf8Glyphs('الكمية'),
                'price' => $arabic->utf8Glyphs('السعر'),
                'total' => $arabic->utf8Glyphs('الإجمالي'),
                'grandTotal' => $arabic->utf8Glyphs('الإجمالي الكلي'),
                'currency' => $arabic->utf8Glyphs('دينار'),
                'marketerSign' => $arabic->utf8Glyphs('توقيع المسوق'),
                'keeperSign' => $arabic->utf8Glyphs('توقيع أمين المخزن'),
            ]
        ];

        $pdf = Pdf::loadView('marketer.requests.invoice-pdf', $data)->setPaper('a4');

        return $pdf->download('invoice-' . $request->invoice_number . '.pdf');
    }
}
