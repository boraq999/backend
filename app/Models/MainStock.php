<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainStock extends Model
{
    protected $table = 'main_stock';
    public $timestamps = false;
    
    protected $fillable = [
        'product_id',
        'quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}