<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketerRequestItem extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'request_id',
        'product_id',
        'quantity'
    ];

    public function request()
    {
        return $this->belongsTo(MarketerRequest::class, 'request_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}