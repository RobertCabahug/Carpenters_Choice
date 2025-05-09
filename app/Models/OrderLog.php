<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    protected $table = 'order_logs';

    protected $primaryKey = 'ordlog_id';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'ordlog_status',
        'ordlog_created_at',
        'ordlog_seller_remarks',
        'ordlog_user_remarks',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->ordlog_created_at = Carbon::now();
        });
    }

    
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
    
}
