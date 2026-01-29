<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MarketerRequest extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'invoice_number',
        'marketer_id'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime'
    ];

    public function marketer()
    {
        return $this->belongsTo(User::class, 'marketer_id');
    }

    public function items()
    {
        return $this->hasMany(MarketerRequestItem::class, 'request_id');
    }

    public function status()
    {
        return $this->hasOne(MarketerRequestStatus::class, 'request_id');
    }

    public function getStatusTextAttribute()
    {
        if (!$this->status) return 'قيد المراجعة';
        
        // التحقق من وجود توثيق
        $isDocumented = DB::table('delivery_confirmation')
            ->where('request_id', $this->id)
            ->exists();
            
        if ($isDocumented) {
            return 'موثق';
        }
        
        return match($this->status->status) {
            'pending' => 'قيد المراجعة',
            'approved' => 'انتظار التوثيق',
            'rejected' => 'مرفوض',
            default => 'غير محدد'
        };
    }
}