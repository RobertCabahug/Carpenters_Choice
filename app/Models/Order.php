<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Order extends Model
{
    protected $primaryKey = 'order_id';

    public $timestamps = false;

    protected $fillable = [
        'order_seller_id',
        'order_cust_id',
        'order_address',
        'order_status',
        'order_created_at',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_created_at = Carbon::now();
        });
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'order_seller_id', 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'order_cust_id', 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function logs()
    {
        return $this->hasMany(OrderLog::class, 'order_id', 'order_id');
    }


    const STATUS_CART = 0;
    const STATUS_SENT = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_SHIPPED = 3;
    const STATUS_DELIVERED = 4;
    const STATUS_RETURNING = 5;
    const STATUS_RETURNED = 6;
    const STATUS_CANCELLED = 7;

    public function getStatusLabelAttribute()
    {
        switch ($this->order_status) {
            case self::STATUS_CART:
                return 'Cart';
            case self::STATUS_SENT:
                return 'Sent';
            case self::STATUS_PROCESSING:
                return 'Processing';
            case self::STATUS_SHIPPED:
                return 'Shipped';
            case self::STATUS_DELIVERED:
                return 'Delivered';
            case self::STATUS_RETURNING:
                return 'Returning';
            case self::STATUS_RETURNED:
                return 'Returned';
            case self::STATUS_CANCELLED:
                return 'Cancelled';
            default:
                return 'Unknown';
        }
    }

    public function saveOrderItems($orderItems){

        $fetchedOrderItems = $this->items()->get()->pluck('orditm_id')->map(fn($id) => (int) $id)->toArray();

        
        foreach($orderItems as $orderItem){
            if($orderItem['orditm_type'] == 1){
                //calculating final rent price
                $timeDiff = strtotime($orderItem['orditm_end_date']) - strtotime($orderItem['orditm_start_date']);
                $incurCount = floor($timeDiff / intval($orderItem['orditm_rent_interval'])) + 1;
                $rentPrice = $orderItem['orditm_rent_price'] * $incurCount;
                $orderItem['orditm_rent_total'] = $rentPrice * intval($orderItem['orditm_quantity']);
            }else{
                $orderItem['orditm_buy_total'] = $orderItem['orditm_buy_price'] * intval($orderItem['orditm_quantity']);
            }

            $orderItem['order_id'] = $this->order_id;

            if( !isset($orderItem['orditm_id'])){
                OrderItem::create($orderItem);
            }else{
                OrderItem::where('orditm_id', $orderItem['orditm_id'])->update($orderItem);
            }
        }

        $orderItemIds = array_column($orderItem, "orditm_id");
        $removedItems = array_filter($fetchedOrderItems, fn($item) => in_array( (int) $item, $orderItemIds ));

        $this->whereIn('orditm_id' , $removedItems)->delete();
    }

    // public function status(){

    //     return OrderLog::where('order_id', $this->order_id)->orderBy('ordlog_created_at', 'desc')->first();
    // }

    public function statusLogs(){
        return OrderLog::where('order_id', $this->order_id)->orderBy('ordlog_created_at', 'desc');
    }

    public function logStatus($status, $sellerRemarks){
        OrderLog::create([
            'order_id' => $this->order_id,
            'ordlog_status' => $status,
            'ordlog_seller_remarks' => $sellerRemarks
        ]);

        $this->order_status = $status;
        $this->save();
    }

}
