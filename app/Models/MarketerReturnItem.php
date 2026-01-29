<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketerReturnItem extends Model
{
    protected $fillable = [
        'return_request_id',
        'product_id',
        'quantity'
    ];

    public $timestamps = false;

    public function returnRequest()
    {
        return $this->belongsTo(MarketerReturnRequest::class, 'return_request_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
