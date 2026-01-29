<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'unit_price',
        'unit',
        'is_active'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function mainStock()
    {
        return $this->hasOne(MainStock::class);
    }
}