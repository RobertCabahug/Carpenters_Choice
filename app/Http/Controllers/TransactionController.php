<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    // Create a new transaction
    public function store(Request $request)
    {
        $data = $request->only([
            'TransactionID', 'UserID', 'BuyItemID', 'RentItemID', 'Item', 'Price', 'Quantity'
        ]);

        $data['Subtotal'] = $data['Price'] * $data['Quantity'];

        if (empty($data['TransactionID'])) {
            $data['TransactionID'] = 'TID-' . strtoupper(uniqid());
        }

        $transaction = Transaction::create($data);

        return response()->json(['status' => 'success', 'transaction' => $transaction], 201);
    }

    // Get all transactions
    public function index(Request $request)
    {
        $userId = $request->query('UserID');
        
        if ($userId) {
            $transactions = Transaction::where('UserID', $userId)->get();
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'UserID is required to fetch transactions.'
            ], 400);
        }
    
        return response()->json(['status' => 'success', 'transactions' => $transactions]);
    }

    // Get a specific transaction by ID
    public function show($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['status' => 'failed', 'message' => 'Transaction not found'], 404);
        }

        return response()->json($transaction);
    }

    // Update price, quantity, and recalculate subtotal
    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['status' => 'failed', 'message' => 'Transaction not found'], 404);
        }

        $price = $request->input('Price', $transaction->Price);
        $quantity = $request->input('Quantity', $transaction->Quantity);

        $transaction->Price = $price;
        $transaction->Quantity = $quantity;
        $transaction->Subtotal = $price * $quantity;

        $transaction->save();

        return response()->json(['status' => 'success', 'transaction' => $transaction]);
    }

    // Delete transaction
    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['status' => 'failed', 'message' => 'Transaction not found'], 404);
        }

        $transaction->delete();

        return response()->json(['status' => 'success', 'message' => 'Transaction deleted']);
    }
}
