<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $primaryKey = 'prod_id';

    protected $fillable = [
        'user_id',
        'prod_name',
        'prod_image',
        'prod_description',
        'prod_rent_price',
        'prod_rent_nondiscounted',
        'prod_rent_interval',
        'prod_buy_price',
        'prod_buy_nondiscounted',
    ];

    public $timestamps = false;

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
