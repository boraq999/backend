<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketerReturnRequest extends Model
{
    protected $fillable = [
        'invoice_number',
        'marketer_id',
        'keeper_id',
        'approved_by',
        'documented_by',
        'status',
        'stamped_image'
    ];

    public function marketer()
    {
        return $this->belongsTo(User::class, 'marketer_id');
    }

    public function keeper()
    {
        return $this->belongsTo(User::class, 'keeper_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function documentedBy()
    {
        return $this->belongsTo(User::class, 'documented_by');
    }

    public function items()
    {
        return $this->hasMany(MarketerReturnItem::class, 'return_request_id');
    }
}
