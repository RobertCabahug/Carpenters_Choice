<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //  Register user
    public function register(Request $request)
    {
        $data = $request->only([
            'FirstName', 'LastName', 'Email', 'Password', 'PhoneNo', 'Address'
        ]);
    
        if (User::where('Email', $data['Email'])->exists()) {
            return response()->json(['status' => 'failed', 'message' => 'Email already registered'], 409);
        }
    
        $data['UserID'] = uniqid();
        $data['Password'] = Hash::make($data['Password']);
    
        $user = User::create($data);
    
        return response()->json(['status' => 'success', 'user' => $user], 201);
    }

    //  Login user
    public function login(Request $request)
    {
        $credentials = $request->only(['Email', 'Password']);

        $user = User::where('Email', $credentials['Email'])->first();
        

        if(!$user)
            $user = Seller::where('Email', $credentials['Email'])->first();

        if (!$user || !Hash::check($credentials['Password'], $user->Password)) {
            return response()->json(['status' => 'failed', 'message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;


        return response()->json([
            'status' => 'success',
            'user' => $user,
            'token' => $token
        ], 200);
    }
}
