<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $primaryKey = 'msg_id';
    public $timestamps = false;

    protected $fillable = [
        'conv_id',
        'msg_sender',
        'msg_content',
        'msg_sent_at',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->msg_sent_at = Carbon::now();
        });
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conv_id', 'conv_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'msg_sender', 'user_id');
    }
}
