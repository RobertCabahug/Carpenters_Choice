<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;

class SellerController extends Controller
{
    //  Create seller
    public function store(Request $request)
    {
        $request->validate([
            'FirstName' => 'required',
            'LastName' => 'required',
            'Email' => 'required|email',
            'PhoneNo' => 'required',
            'Address' => 'required',
            'Rating' => 'nullable|numeric',
        ]);

        // Auto-generate SellerID
        $latestSeller = Seller::orderBy('_id', 'desc')->first();
        $nextId = $latestSeller ? intval(substr($latestSeller->SellerID, 7)) + 1 : 1;
        $generatedSellerId = 'SELLER-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $seller = Seller::create([
            'SellerID' => $generatedSellerId,
            'FirstName' => $request->FirstName,
            'LastName' => $request->LastName,
            'Email' => $request->Email,
            'PhoneNo' => $request->PhoneNo,
            'Address' => $request->Address,
            'Rating' => $request->Rating ?? 0,
        ]);

        return response()->json(['status' => 'success', 'seller' => $seller], 201);
    }

    //  Get all sellers
    public function index()
    {
        return response()->json(Seller::all());
    }

    //  Get specific seller
    public function show($id)
    {
        $seller = Seller::find($id);

        if (!$seller) {
            return response()->json(['status' => 'failed', 'message' => 'Seller not found'], 404);
        }

        return response()->json($seller);
    }

    //  Update seller
    public function update(Request $request, $id)
    {
        $seller = Seller::find($id);

        if (!$seller) {
            return response()->json(['status' => 'failed', 'message' => 'Seller not found'], 404);
        }

        $seller->update($request->only([
            'FirstName', 'LastName', 'Email', 'PhoneNo', 'Address', 'Rating'
        ]));

        return response()->json(['status' => 'success', 'seller' => $seller]);
    }

    //  Delete seller
    public function destroy($id)
    {
        $seller = Seller::find($id);

        if (!$seller) {
            return response()->json(['status' => 'failed', 'message' => 'Seller not found'], 404);
        }

        $seller->delete();

        return response()->json(['status' => 'success', 'message' => 'Seller deleted']);
    }
}
