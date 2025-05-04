<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductController extends Controller
{
    //  Create product
    public function store(Request $request)
    {
        $request->validate([
            'SellerID' => 'required',
            'Name' => 'required',
            'Price' => 'required|numeric',
            'Description' => 'required',
            'Rating' => 'nullable|numeric',
            'Buy' => 'required|boolean',
            'Rent' => 'required|boolean',
            'Image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $latestProduct = Product::orderBy('_id', 'desc')->first();
        $nextId = $latestProduct ? intval(substr($latestProduct->ProductID, 5)) + 1 : 1;
        $generatedProductId = 'PROD-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        // Upload image to Cloudinary
        $uploadedFileUrl = Cloudinary::upload($request->file('Image')->getRealPath())->getSecurePath();

        $product = Product::create([
            'ProductID' => $request->ProductID,
            'SellerID' => $request->SellerID,
            'Image' => $uploadedFileUrl,
            'Name' => $request->Name,
            'Price' => $request->Price,
            'Description' => $request->Description,
            'Rating' => $request->Rating ?? 0,
            'Buy' => $request->Buy,
            'Rent' => $request->Rent,
        ]);

        return response()->json(['status' => 'success', 'product' => $product], 201);
    }

    //  Get all products
    public function index()
    {
        return response()->json(Product::all());
    }

     //  Get all products from Sellers
    public function index(Request $request)
    {
        $sellerId = $request->query('SellerID');
    
        if ($sellerId) {
            $product = Product::where('SellerID', $sellerId)->get();
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'SellerID is required to fetch product.'
            ], 400);
        }
    
        return response()->json(['status' => 'success', 'buyitem' => $product]);
    }

    //  Get specific product
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['status' => 'failed', 'message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    //  Update product
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['status' => 'failed', 'message' => 'Product not found'], 404);
        }

        $data = $request->only([
            'Name', 'Price', 'Description', 'Rating', 'Buy', 'Rent'
        ]);

        if ($request->hasFile('Image')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('Image')->getRealPath())->getSecurePath();
            $data['Image'] = $uploadedFileUrl;
        }

        $product->update($data);

        return response()->json(['status' => 'success', 'product' => $product]);
    }

    //  Delete product
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['status' => 'failed', 'message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['status' => 'success', 'message' => 'Product deleted']);
    }
}
