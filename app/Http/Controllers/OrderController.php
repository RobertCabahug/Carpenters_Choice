<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function add(Request $request){
        $validation = Validator::make($request->all(), [
            'order_seller_id' => 'required|integer|exists:users,user_id',
            'order_address' => 'required|string|max:500',

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
                'errors' => $validation->errors(),
                'request' => $request->all()
            ], 422);
        }

        $validated = $validation->validated();
        $orderItems = $validated['items'];
        unset($validated['items']);


        $order = Order::create( array_merge($validated, [
            'order_status' => 1,
            'order_cust_id' => $request->user()->user_id
        ]));

        $order->saveOrderItems($orderItems);

        $order->logStatus(1, null);

        return [
            'mesage' => "Order added successfully!"
        ];
        
    }

    public function orderData(Request $request, $orderId){
        return [
            'results' => Order::with(['items.product', 'logs', 'seller', 'customer'])->find($orderId)
        ];
    }
    

    public function addLog(Request $request, $orderId){
        $validation = Validator::make($request->all(), [
            'ordlog_status' => 'required|integer|min:1|max:7',
            'ordlog_seller_remarks' => 'required|string|max:500',
        ]);

        if($validation->fails()){
            return response()->json([
                'errors' => $validation->errors(),
                'request' => $request->all()
            ], 422);
        }

        $validated = $validation->validated();
        $order = Order::find($orderId);

        $order->logStatus($validated['ordlog_status'], $validated['ordlog_seller_remarks']);

        return [
            'message' => 'Order status logged successfully!'
        ];

    }
}
