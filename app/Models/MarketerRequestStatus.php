<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketerRequestStatus extends Model
{
    protected $table = 'marketer_request_status';
    public $timestamps = false;
    
    protected $fillable = [
        'request_id',
        'marketer_id', 
        'keeper_id',
        'status'
    ];

    public function request()
    {
        return $this->belongsTo(MarketerRequest::class, 'request_id');
    }

    public function marketer()
    {
        return $this->belongsTo(User::class, 'marketer_id');
    }

    public function keeper()
    {
        return $this->belongsTo(User::class, 'keeper_id');
    }
}