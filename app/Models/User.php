<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class User extends Model
{
    use Notifiable;

    protected $connection = 'mongodb';
    protected $table = 'users';
    protected $fillable = [
        'UserID',
        'FirstName',
        'LastName',
        'Email',
        'Password',
        'PhoneNo',
        'Address',
    ];
}


