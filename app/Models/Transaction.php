<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Transaction extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'transactions';

    protected $fillable = [
        'TransactionID',
        'UserID',
        'BuyItemID',
        'RentItemID',
        'Item',
        'Price',
        'Quantity',
        'Subtotal',
    ];
}
