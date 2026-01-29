<?php

namespace App\Http\Controllers\Marketer\Returns;

use App\Http\Controllers\Controller;
use App\Models\MarketerReturnRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index()
    {
        $marketerId = Auth::id();
        
        $returns = MarketerReturnRequest::with(['items.product', 'keeper'])
            ->where('marketer_id', $marketerId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $documentedReturns = $returns->where('status', 'documented');
        $approvedReturns = $returns->where('status', 'approved');
        $pendingReturns = $returns->where('status', 'pending');
        $rejectedReturns = $returns->where('status', 'rejected');
        
        $documentedCount = $documentedReturns->count();
        $approvedCount = $approvedReturns->count();
        $pendingCount = $pendingReturns->count();
        $rejectedCount = $rejectedReturns->count();
        
        return view('marketer.returns.index', compact(
            'documentedReturns', 'approvedReturns', 'pendingReturns', 'rejectedReturns',
            'documentedCount', 'approvedCount', 'pendingCount', 'rejectedCount'
        ));
    }

    public function show($id)
    {
        $return = MarketerReturnRequest::with(['items.product', 'keeper', 'approvedBy', 'documentedBy'])
            ->where('marketer_id', Auth::id())
            ->findOrFail($id);
        
        return view('marketer.returns.show', compact('return'));
    }

    public function create()
    {
        $marketerId = Auth::id();
        
        $products = DB::table('marketer_actual_stock')
            ->join('products', 'marketer_actual_stock.product_id', '=', 'products.id')
            ->where('marketer_actual_stock.marketer_id', $marketerId)
            ->where('marketer_actual_stock.quantity', '>', 0)
            ->select('products.*', 'marketer_actual_stock.quantity as available_quantity')
            ->get();
        
        return view('marketer.returns.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            $invoiceNumber = 'RET-' . date('Ymd') . '-' . str_pad(MarketerReturnRequest::count() + 1, 4, '0', STR_PAD_LEFT);
            
            $returnRequest = MarketerReturnRequest::create([
                'invoice_number' => $invoiceNumber,
                'marketer_id' => Auth::id(),
                'status' => 'pending'
            ]);

            foreach ($request->products as $item) {
                $returnRequest->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity']
                ]);
            }

            DB::commit();
            return redirect()->route('marketer.returns.index')->with('success', 'تم إنشاء طلب الإرجاع بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء إنشاء الطلب');
        }
    }

    public function printInvoice($id)
    {
        $return = MarketerReturnRequest::with(['items.product', 'marketer', 'keeper', 'approvedBy', 'documentedBy'])
            ->where('marketer_id', Auth::id())
            ->findOrFail($id);
        
        $arabic = new \ArPHP\I18N\Arabic();
        
        $statusMap = [
            'pending' => 'في انتظار الموافقة',
            'approved' => 'موافق عليه',
            'documented' => 'موثق',
            'rejected' => 'مرفوض'
        ];
        
        $data = [
            'invoiceNumber' => $return->invoice_number,
            'date' => \Carbon\Carbon::parse($return->created_at)->format('Y-m-d H:i'),
            'marketerName' => $arabic->utf8Glyphs($return->marketer->full_name ?? $return->marketer->name),
            'keeperName' => $return->approvedBy ? $arabic->utf8Glyphs($return->approvedBy->full_name ?? $return->approvedBy->name) : $arabic->utf8Glyphs('---'),
            'status' => $arabic->utf8Glyphs($statusMap[$return->status] ?? $return->status),
            'items' => $return->items->map(function($item) use ($arabic) {
                return (object)[
                    'name' => $arabic->utf8Glyphs($item->product->name),
                    'quantity' => $item->quantity
                ];
            }),
            'totalQty' => $return->items->sum('quantity'),
            'labels' => [
                'title' => $arabic->utf8Glyphs('فاتورة إرجاع بضاعة'),
                'marketer' => $arabic->utf8Glyphs('المسوق'),
                'keeper' => $arabic->utf8Glyphs('أمين المخزن'),
                'date' => $arabic->utf8Glyphs('التاريخ'),
                'status' => $arabic->utf8Glyphs('حالة الطلب'),
                'product' => $arabic->utf8Glyphs('المنتج'),
                'quantity' => $arabic->utf8Glyphs('الكمية'),
                'total' => $arabic->utf8Glyphs('الإجمالي'),
                'marketerSign' => $arabic->utf8Glyphs('توقيع المسوق'),
                'keeperSign' => $arabic->utf8Glyphs('توقيع أمين المخزن'),
            ]
        ];
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('marketer.returns.invoice-pdf', $data);
        
        return $pdf->download('return-invoice-' . $return->invoice_number . '.pdf');
    }
}
