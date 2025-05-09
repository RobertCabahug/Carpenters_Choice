<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_first_name',
        'user_last_name',
        'user_email',
        'user_password',
        'user_phone',
        'user_address',
        'user_type',
    ];

    protected $hidden = [
        'user_password',
    ];

    public $timestamps = false;

    public function getPassword()
    {
        return $this->user_password;
    }

    public function setUserPasswordAttribute($value)
    {
        $this->attributes['user_password'] = Hash::make($value);
    }

    public function getCart(){
        $cart = Order::where('order_status',0)->where('order_cust_id', $this->user_id)->with('items')->orderBy('order_created_at', 'desc')->first();

        if($cart == null){
            $cart = Order::create([
                'order_cust_id' => $this->user_id,
                'order_status' => 0,
            ]);
        }

        return $cart->loadMissing('items');
    }

    public function clearCart(){
        $cart = Order::where('order_status',0)->where('order_cust_id', $this->user_id)->with('items')->orderBy('order_created_at', 'desc')->first();

        if($cart == null){
            $cart = Order::create([
                'order_cust_id' => $this->user_id,
                'order_status' => 0,
            ]);
        }

        $cart->items()->delete();
    }
}
