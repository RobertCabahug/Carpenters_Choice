<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class BuyItem extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'buy_items';

    protected $fillable = [
        'BuyItemID',
        'ProductID',
        'UserID',
        'Quantity',
        'Date',
        'DeliveryFee',
        'TotalAmount',
        'PaymentMethod',
        'TrackingNo',
    ];
}
