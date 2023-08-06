<?php

namespace App\Http\Repositories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Image;
use Illuminate\Support\Facades\Auth;

class OrderRepository
{
    public function onCart(Object $request)
    {
        return Order::with('user', 'product')->where('status', $request->status)
                    ->where('total_price', 0)->orderByDesc('id')->get();
    }

    public function queryGetOrderById($id)
    {
        return Order::where('id', $id)->first();
    }

    public function orderById(Object $request)
    {
        return Order::with('user', 'product')->where('status', $request->status)->orderByDesc('id')->get();
    }

    public function allOrder(Object $request)
    {
        return Order::with('user', 'product')->orderByDesc('id')->get();
    }

    public function queryOrderCreate(Object $request)
    {
        return Order::create([
            "user_id" => Auth::id(),
            "product_id" => $request->product_id,
            "quantity" => $request->quantity,
            "total_price" => $request->total_price,
            "status" => $request->status,
            "message" => $request->message,
            "type" => $request->type,
            "image" => $request->image,
            "remarks" => $request->remarks,
            "delivery_date" => $request->delivery_date,
            "delivery_address" => $request->delivery_address,
        ]);
    }

    public function queryGetOrderByIdWithProduct($id)
    {
        return Order::where('id', $id)->with('user', 'product')->first();
    }

    public function queryGetOrderByIdStatus($id, $status)
    {
        return Order::where('user_id', $id)->orderByDesc('id')->where('status', $status)->with('product')->get();
    }

    public function queryOderDelete($id)
    {
        $order = Order::where('id', $id)->first();
        $order->delete();
    }

    public function queryUpdateOrder(Object $request, Order $order)
    {
        $order->update([
            "unit_price" => $request->unit_price,
            "total_price" => $request->unit_price * $order->quantity
        ]);
    }

    public function queryUpdateAll(Object $request, Order $order)
    {
        $order->update($request->all());
    }

    public function queryGetOrder($id)
    {
        return Order::where('product_id', $id)->with('product')->first();
    }

    public function queryGetTotalPrice($id)
    {
        return Product::where('id', $id)->first();
    }

    public function queryGetOrderByStatus($id, $status)
    {
        return Order::where('user_id', $id)->where('status', $status)->with('product')->get();
    }

    public function queryGetTotalByStatus()
    {
        $onCart = Order::where('status', "onCart")->where('total_price', 0)->count();;
        $toPay = Order::where('status', "Paid")->count();
        $processing = Order::where('status', "Processing")->count();
        $delivery = Order::where('status', "Ready-For-Delivery")->count();
        $completed = Order::where('status', "Completed")->count();
        return [$onCart, $toPay, $processing, $delivery, $completed];
    }

    public function queryGetTotalByUserStatus($id)
    {
        $oncart = Order::where('user_id', $id)->orderByDesc('id')->where('status', "onCart")->count();
        $paid = Order::where('user_id', $id)->orderByDesc('id')->where('status', "Paid")->count();
        $process = Order::where('user_id', $id)->orderByDesc('id')->where('status', "Processing")->count();
        $deliver = Order::where('user_id', $id)->orderByDesc('id')->where('status', "Ready-For-Delivery")->count();
        $completed = Order::where('user_id', $id)->orderByDesc('id')->where('status', "Completed")->count();
        return [$oncart, $paid, $process, $deliver, $completed];
    }

}
