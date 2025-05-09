<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $primaryKey = 'conv_id';
    public $timestamps = false;

    protected $fillable = [
        'conv_user_a',
        'conv_user_b',
        'conv_user_a_last_read',
        'conv_user_b_last_read',
    ];

    public function userA()
    {
        return $this->belongsTo(User::class, 'conv_user_a', 'user_id');
    }

    public function userB()
    {
        return $this->belongsTo(User::class, 'conv_user_b', 'user_id');
    }

    public function userALastRead()
    {
        return $this->belongsTo(Message::class, 'conv_user_a_last_read', 'msg_id');
    }

    public function userBLastRead()
    {
        return $this->belongsTo(Message::class, 'conv_user_b_last_read', 'msg_id');
    }
}
