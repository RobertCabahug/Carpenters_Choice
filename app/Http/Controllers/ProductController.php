<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function add(Request $request){
        $validation = Validator::make($request->all(), [
            'prod_name' => 'required|max:50',
            'prod_image' => 'required|mimes:jpg,jpeg,png|max:2048',
            'prod_description' => 'required|max:500',
            'prod_rent_price' => 'required|numeric|min:0',
            'prod_rent_nondiscounted' => 'required|numeric|min:0',
            'prod_rent_interval' => 'required|integer',
            'prod_buy_price' => 'required|numeric|min:0',
            'prod_buy_nondiscounted' => 'required|numeric|min:0',
        ]);

        if($validation->fails()){
            return response()->json([
                'errors' => $validation->errors(),
            ], 422);
        }

        $validated = $validation->validated();

        $path = $request->file('prod_image')->store('uploads', 'public');

        $product = Product::create(array_merge($validated, [
            'prod_image' => $path,
            'user_id' => $request->user()->user_id
        ]));


        return response()->json([
            'message' => 'Product created successfully!',
            'links' => [
                'self' => url('/product/'.$product->user_id)
            ]
        ], 200);
        
    }

    public function all(Request $request){
        return datatablesAssist($request->query(),[
            'searchFrom' => ["prod_name", "prod_description", DB::raw("CONCAT(users.user_first_name, ' ', users.user_last_name)")],
            'orderBy' => 'prod_name',
            'orderDir' => 'asc'
        ], function(){
            return Product::join('users', 'products.user_id', '=', 'users.user_id');
        });
    }

    public function get(Request $request, $prodId){
        $product = Product::where('prod_id', $prodId)->with('seller')->first();

        if($product == null){
            return response()->json([
                'errors' =>[
                    'message' => 'Product not found!'
                ]
            ], 404);
        }

        return response()->json([
            'product' => $product,
            'links' => [
                'self' => url('/product/'.$product->user_id)
            ]
        ], 200);    
    }

    public function edit(Request $request, $prodId){
        $validation = Validator::make($request->all(), [
            'prod_name' => 'required|max:50',
            'prod_image' => 'mimes:jpg,jpeg,png|max:2048',
            'prod_description' => 'required|max:500',
            'prod_rent_price' => 'required|numeric|min:0',
            'prod_rent_nondiscounted' => 'required|numeric|min:0',
            'prod_rent_interval' => 'required|integer',
            'prod_buy_price' => 'required|numeric|min:0',
            'prod_buy_nondiscounted' => 'required|numeric|min:0',
        ]);

        if($validation->fails()){
            return response()->json([
                'errors' => $validation->errors(),
            ], 422);
        }

        $validated = $validation->validated();
        $product = Product::where('prod_id', $prodId)->first($prodId);

        if($product == null){
            return response()->json([
                'errors' =>[
                    'message' => 'Product not found!'
                ]
            ], 404);
        }

        $path = $request->file('prod_image') == null ? $product->prod_image :  $request->file('prod_image')->store('uploads', 'public');

        $product = Product::create(array_merge($validated, [
            'prod_image' => $path,
        ]));
        

        return response()->json([
            'message' => 'Product updated successfully!',
            'links' => [
                'self' => url('/product/'.$product->user_id)
            ]
        ], 200);
    }

    public function delete(Request $request, $prodId){

        $product = Product::where('prod_id', $prodId)->first($prodId);

        if($product == null){
            return response()->json([
                'errors' =>[
                    'message' => 'Product not found!'
                ]
            ], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully!',
        ], 200);
    }
}
