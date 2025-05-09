<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'favorites'; 

    protected $primaryKey = 'fav_id'; 

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'prod_id',
        'fav_at',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->fav_at = Carbon::now();
        });
    }
    

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'prod_id', 'prod_id');
    }

    
}
