<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //  Create a new cart
    public function store(Request $request)
    {

        $data = $request->only(['CartID', 'UserID', 'BuyItemID', 'RentItemID', 'TotalItems']);

        if (empty($data['CartID'])) {
            $data['CartID'] = 'CID-' . strtoupper(uniqid());
        }

        $cart = Cart::create($data)

        return response()->json(['status' => 'success', 'cart' => $cart], 201);
    }

    //  Get all carts (or filter by UserID if needed)
    public function index(Request $request)
    {
        $userId = $request->query('UserID');
    
        if ($userId) {
            $carts = Cart::where('UserID', $userId)->get();
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'UserID is required to fetch carts.'
            ], 400);
        }
    
        return response()->json(['status' => 'success', 'carts' => $carts]);
    }
    

    //  Show a single cart by ID
    public function show($id)
    {
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json(['status' => 'failed', 'message' => 'Cart not found'], 404);
        }
        return response()->json($cart);
    }

    //  Update only the TotalItems of a cart
    public function update(Request $request, $id)
    {
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json(['status' => 'failed', 'message' => 'Cart not found'], 404);
        }

        $cart->TotalItems = $request->input('TotalItems');
        $cart->save();

        return response()->json(['status' => 'success', 'cart' => $cart]);
    }

    //  Delete a cart
    public function destroy($id)
    {
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json(['status' => 'failed', 'message' => 'Cart not found'], 404);
        }

        $cart->delete();

        return response()->json(['status' => 'success', 'message' => 'Cart deleted']);
    }
}
