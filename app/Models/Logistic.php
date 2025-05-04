<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Logistic extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'logistics';

    protected $fillable = [
        'LogisticID',
        'UserID',
        'BuyItemID',
        'RentItemID',
        'DeliveryDate',
        'DeliveryStatus',
        'DeliveryAddress',
    ];
}
