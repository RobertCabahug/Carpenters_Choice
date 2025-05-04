<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasApiTokens, Notifiable;

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


