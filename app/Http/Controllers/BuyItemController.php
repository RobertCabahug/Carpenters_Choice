<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BuyItem;

class BuyItemController extends Controller
{
    //  Create a new buy item
    public function store(Request $request)
    {
        $data = $request->only([
            'BuyItemID',
            'ProductID',
            'UserID',
            'Quantity',
            'Date',
            'DeliveryFee',
            'TotalAmount',
            'PaymentMethod',
            'TrackingNo',
        ]);

        if (empty($data['TrackingNo'])) {
            $data['TrackingNo'] = 'TRK-' . strtoupper(uniqid());
        }

        if (empty($data['BuyItemID'])) {
            $data['BuyItemID'] = 'BID-' . strtoupper(uniqid());
        }

        $buyItem = BuyItem::create($data);

        return response()->json(['status' => 'success', 'buy_item' => $buyItem], 201);
    }

    //  Get all buy items (or filter by UserID if needed)
    public function index(Request $request)
    {
        $userId = $request->query('UserID');
    
        if ($userId) {
            $buyitem = BuyItem::where('UserID', $userId)->get();
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'UserID is required to fetch buy items.'
            ], 400);
        }
    
        return response()->json(['status' => 'success', 'buyitem' => $buyitem]);
    }

    //  Get specific buy item by ID (optional bonus)
    public function show($id)
    {
        $buyItem = BuyItem::find($id);

        if (!$buyItem) {
            return response()->json(['status' => 'failed', 'message' => 'Buy item not found'], 404);
        }

        return response()->json($buyItem);
    }
}
