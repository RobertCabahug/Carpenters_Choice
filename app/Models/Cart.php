<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Cart extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'carts';

    protected $fillable = [
        'CartID',
        'UserID',
        'BuyItemID',
        'RentItemID',
        'TotalItems',
    ];
}
