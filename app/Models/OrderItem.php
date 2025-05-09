<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items'; 

    protected $primaryKey = 'orditm_id';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'prod_id',
        'orditm_type',
        'orditm_start_date',
        'orditm_end_date',
        'orditm_rent_price',
        'orditm_rent_nondiscounted',
        'orditm_rent_interval',
        'orditm_buy_price',
        'orditm_buy_nondiscounted',
        'orditm_quantity',
        'orditm_rent_total',
        'orditm_buy_total',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'prod_id', 'prod_id');
    }
}
