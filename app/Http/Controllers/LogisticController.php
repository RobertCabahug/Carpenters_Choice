<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Logistic;

class LogisticController extends Controller
{
    //  Create a new logistic record
    public function store(Request $request)
    {
        $data = $request->only([
            'LogisticID',
            'UserID',
            'BuyItemID',
            'RentItemID',
            'DeliveryDate',
            'DeliveryStatus',
            'DeliveryAddress',
        ]);

        if (empty($data['LogisticID'])) {
            $data['LogisticID'] = 'LID-' . strtoupper(uniqid());
        }

        $logistic = Logistic::create($data);

        return response()->json(['status' => 'success', 'logistic' => $logistic], 201);
    }

    //  Get all logistic records (or filter by UserID if needed)
    public function index(Request $request)
    {
        $userId = $request->query('UserID');

        if ($userId) {
            $buyitem = Logistic::where('UserID', $userId)->get();
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'UserID is required to fetch logistics.'
            ], 400);
        }
    
        return response()->json(['status' => 'success', 'buyitem' => $buyitem]);
    }

    //  Get a specific logistic record by ID
    public function show($id)
    {
        $logistic = Logistic::find($id);

        if (!$logistic) {
            return response()->json(['status' => 'failed', 'message' => 'Logistic record not found'], 404);
        }

        return response()->json($logistic);
    }

    //  Update delivery status or address
    public function update(Request $request, $id)
    {
        $logistic = Logistic::find($id);

        if (!$logistic) {
            return response()->json(['status' => 'failed', 'message' => 'Logistic record not found'], 404);
        }

        $logistic->update($request->only([
            'DeliveryDate',
            'DeliveryStatus',
            'DeliveryAddress',
        ]));

        return response()->json(['status' => 'success', 'logistic' => $logistic]);
    }
}
