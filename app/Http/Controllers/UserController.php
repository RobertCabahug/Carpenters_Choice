<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Favorite;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PDO;

class UserController extends Controller
{

    public function me(){
        return request()->json([
            'me' => auth()->user()
        ]);
    }

    public function register(Request $request){
    
        $validation = Validator::make($request->all(), [
            'user_first_name' => 'required|string|max:50',
            'user_last_name' => 'required|string|max:50',
            'user_email' => 'required|email|max:50|unique:users,user_email',
            'user_password' => 'required|min:6',
            'user_phone' => 'required|string|max:15|unique:users,user_phone',
            'user_address' => 'required|string|max:255',
            'user_type' => 'required|integer|min:0|max:1',
        ]);
        
        if($validation->fails()){
            return response()->json([
                'errors' => $validation->errors(),
            ], 422);
        }

        $validated = $validation->validated();

        User::create([
            'user_first_name' =>  $validated['user_first_name'],
            'user_last_name' => $validated['user_last_name'],
            'user_email' => $validated['user_email'],
            'user_password' => $validated['user_password'],
            'user_phone' => $validated['user_phone'],
            'user_address' => $validated['user_address'],
            'user_type' => $validated['user_type'],
        ]);


        return response()->json([
            'message' => 'User created successfully!'
        ], 200);
        
    }

    public function login(Request $request){
        $validation = Validator::make($request->all(), [
            'user_email' => 'required|email|max:50',
            'user_password' => 'required|min:6',
        ]);
        
        if($validation->fails()){
            return response()->json([
                'errors' => $validation->errors()
            ], 422);
        }

        $validated = $validation->validated();

        $user = User::where('user_email', $validated['user_email'])->first();

        if(!$user || !Hash::check($validated['user_password'], $user->user_password)){
            return response()->json([
                'error' => [
                    'message' => 'Invalid Credentials!'
                ]
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User authenticated successfully!',
            'token' => $token
        ], 200);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully!',
        ], 200);
    }

    public function cart(Request $request){
        return response()->json([
            'results' => $request->user()->getCart(),
        ], 200);   
    }

    public function clearCart(Request $request){
        $request->user()->clearCart();

        return response()->json([
            'message' => 'User\'s cart cleared successfully!',
        ], 200);
        
    }
    
    
    public function products(Request $request){
        return datatablesAssist($request->query(),[
            'searchFrom' => ["prod_name", "prod_description", DB::raw("CONCAT(users.user_first_name, ' ', users.user_last_name)")],
            'orderBy' => 'prod_name',
            'orderDir' => 'asc'
        ], function() use ($request){
            return Product::join('users', 'products.user_id', '=', 'users.user_id')->where('user_id', $request->user()->user_id);
        });
    }

    public function orders(Request $request){
        return datatablesAssist($request->query(),[
            'searchFrom' => ["order_address", DB::raw("CONCAT(users.user_first_name, ' ', users.user_last_name)")],
            'orderBy' => 'order_created_at',
            'orderDir' => 'desc'
        ], function() use ($request){
            return Order::select('orders.*','users.*')
            ->join('users', 'orders.order_seller_id', '=', 'users.user_id')
            ->where('user_id', $request->user()->user_id);
        });
    }

    
    public function updateCart(Request $request){
        $validation = Validator::make($request->all(), [
            'items.*.prod_id'                    => 'required|integer|exists:products,prod_id',
            'items.*.orditm_type'                => 'required|in:0,1', // 0 - buy, 1 - rent

            'items.*.orditm_start_date'          => 'nullable|date',
            'items.*.orditm_end_date'            => 'nullable|date|after_or_equal:orditm_start_date',

            'items.*.orditm_rent_price'          => 'nullable|numeric|min:0',
            'items.*.orditm_rent_nondiscounted'  => 'nullable|numeric|min:0',
            'items.*.orditm_rent_interval'       => 'nullable|integer|min:1',

            'items.*.orditm_buy_price'           => 'nullable|numeric|min:0',
            'items.*.orditm_buy_nondiscounted'   => 'nullable|numeric|min:0',

            'items.*.orditm_quantity'            => 'required|integer|min:1',

            'items.*.orditm_rent_total'          => 'nullable|numeric|min:0',
            'items.*.orditm_buy_total'           => 'nullable|numeric|min:0',
        ]);

        if($validation->fails()){
            return response()->json([
                'errors' => $validation->errors()
            ], 422);
        }

        $validated = $validation->validated();
        $orderItems = $validated['items'];

        $cart = $request->user()->getCart();

        $cart->saveOrderItems($orderItems);

        return response()->json([
            'mesage' => "Cart items updated successfully!"
        ]);

    }


    public function addToFavorites(Request $request){
        $prodId = $request->get('prod_id') ?? 0;
        $product = Product::find($prodId);

        if($product == null)
            return response()->json([
                'error' => [
                    'message' => 'Product not found!'
                ],
            ], 404);

        Favorite::create([
            'user_id' => auth()->user()->user_id,
            'prod_id' => $prodId
        ]);

        return response()->json([
            'message' => 'Product saved to favorites!',
        ]);    
    }


    public function favorites(Request $request){
        return datatablesAssist($request->query(),[
            'searchFrom' => ["prod_name", "prod_description"],
            'orderBy' => 'fav_at',
            'orderDir' => 'desc'
        ], function() use ($request){
            return Favorite::join('products', 'favorites.prod_id', '=', 'products.prod_id')
            ->where('favorites.user_id', $request->user()->user_id);
        });
    }

    public function unfavorite(Request $request, $favId){        
        Favorite::where('fav_id', $favId)->delete();

        return response()->json([
            'message' => 'Product removed from favorites!',
        ]);  
    }

    public function startSelling(){
        auth()->user()->user_type = 1;
        auth()->user()->save();

        return response()->json([
            'message' => 'User converted to seller successfully!',
        ]);  
    }


    public function quitSelling(){
        auth()->user()->user_type = 0;
        auth()->user()->save();

        return response()->json([
            'message' => 'User converted to customer successfully!',
        ]);  
    }

    public function conversations(Request $request){
        return datatablesAssist($request->query(),[
            'searchFrom' => ["prod_name", "prod_description"],
            'orderBy' => 'fav_at',
            'orderDir' => 'desc'
        ], function(){
            if(auth()->user()->user_type == 0){
                return Conversation::with(['userB', 'userBLastRead'])->where('conv_user_a', auth()->user()->user_id);
            }else{
                return Conversation::with(['userA', 'userALastRead'])->where('conv_user_b', auth()->user()->user_id);
            }
        });
    }



}

