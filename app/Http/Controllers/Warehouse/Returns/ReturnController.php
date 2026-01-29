<?php

namespace App\Http\Controllers\Warehouse\Returns;

use App\Http\Controllers\Controller;
use App\Models\MarketerReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index()
    {
        $mainStock = DB::table('main_stock')
            ->join('products', 'main_stock.product_id', '=', 'products.id')
            ->select('products.name', 'products.current_price', 'main_stock.quantity')
            ->where('main_stock.quantity', '>', 0)
            ->get();
        
        $returns = MarketerReturnRequest::with(['items.product', 'marketer'])
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
        
        return view('warehouse.returns.index', compact(
            'mainStock',
            'documentedReturns', 'approvedReturns', 'pendingReturns', 'rejectedReturns',
            'documentedCount', 'approvedCount', 'pendingCount', 'rejectedCount'
        ));
    }

    public function show($id)
    {
        $return = MarketerReturnRequest::with(['items.product', 'marketer', 'keeper', 'approvedBy', 'documentedBy'])
            ->findOrFail($id);
        
        return view('warehouse.returns.show', compact('return'));
    }

    public function approve($id)
    {
        $return = MarketerReturnRequest::findOrFail($id);
        $return->update([
            'status' => 'approved',
            'approved_by' => Auth::id()
        ]);
        
        return redirect()->back()->with('success', 'تم الموافقة على طلب الإرجاع');
    }

    public function reject($id)
    {
        $return = MarketerReturnRequest::findOrFail($id);
        $return->update([
            'status' => 'rejected',
            'approved_by' => Auth::id()
        ]);
        
        return redirect()->back()->with('success', 'تم رفض طلب الإرجاع');
    }

    public function uploadDocument($id)
    {
        $return = MarketerReturnRequest::with(['items.product', 'marketer'])->findOrFail($id);
        return view('warehouse.returns.upload-document', compact('return'));
    }

    public function storeDocument(Request $request, $id)
    {
        $request->validate([
            'stamped_image' => 'required|image|max:2048'
        ]);

        $return = MarketerReturnRequest::findOrFail($id);
        
        DB::beginTransaction();
        try {
            if ($request->hasFile('stamped_image')) {
                $path = $request->file('stamped_image')->store('returns', 'public');
                
                $return->update([
                    'stamped_image' => $path,
                    'status' => 'documented',
                    'documented_by' => Auth::id()
                ]);

                // تنفيذ حركة المخزون
                foreach ($return->items as $item) {
                    // خصم من مخزون المسوق
                    DB::table('marketer_actual_stock')
                        ->where('marketer_id', $return->marketer_id)
                        ->where('product_id', $item->product_id)
                        ->decrement('quantity', $item->quantity);
                    
                    // إضافة للمخزن الرئيسي
                    DB::table('main_stock')
                        ->where('product_id', $item->product_id)
                        ->increment('quantity', $item->quantity);
                }
            }

            DB::commit();
            return redirect()->route('warehouse.returns.index')->with('success', 'تم توثيق الإرجاع بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء التوثيق');
        }
    }
}
