<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProductFeedback extends Model
{
    protected $primaryKey = 'prodfeed_id';

    public $timestamps = false;

    protected $fillable = [
        'prod_id',
        'user_id',
        'prodfeed_rating',
        'prodfeed_comment',
        'prodfeed_added_at',
    ];

    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->prodfeed_added_at = Carbon::now();
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'prod_id', 'prod_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
