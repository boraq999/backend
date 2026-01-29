<?php

namespace App\Http\Controllers\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index()
    {
        $marketerId = Auth::id();
        
        $actualStock = DB::table('marketer_actual_stock')
            ->join('products', 'marketer_actual_stock.product_id', '=', 'products.id')
            ->where('marketer_actual_stock.marketer_id', $marketerId)
            ->where('marketer_actual_stock.quantity', '>', 0)
            ->select(
                'products.id',
                'products.name',
                'products.current_price',
                'marketer_actual_stock.quantity',
                DB::raw('marketer_actual_stock.quantity * products.current_price as total_value')
            )
            ->get();
        
        $reservedStock = DB::table('marketer_reserved_stock')
            ->join('products', 'marketer_reserved_stock.product_id', '=', 'products.id')
            ->where('marketer_reserved_stock.marketer_id', $marketerId)
            ->where('marketer_reserved_stock.reserved_quantity', '>', 0)
            ->select(
                'products.id',
                'products.name',
                'products.current_price',
                'marketer_reserved_stock.reserved_quantity as quantity',
                DB::raw('marketer_reserved_stock.reserved_quantity * products.current_price as total_value')
            )
            ->get();
        
        return view('marketer.stock.index', compact('actualStock', 'reservedStock'));
    }
}
