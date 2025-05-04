<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Eloquent\Model;

class Seller extends Model
{

    use HasApiTokens;

    protected $connection = 'mongodb';
    protected $collection = 'sellers';

    protected $fillable = [
        'SellerID',
        'FirstName',
        'LastName',
        'Email',
        'PhoneNo',
        'Address',
        'Rating',
    ];
}
