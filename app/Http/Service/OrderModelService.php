<?php

namespace App\Http\Service;

use App\Http\Repositories\OrderRepository;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderModelService
{
    private $orderRepository;
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function OrderOnCart(Object $request)
    {
        return $this->orderRepository->onCart($request);
    }

    public function GetOrderbyId($id)
    {
        return $this->orderRepository->queryGetOrderById($id);
    }

    public function OrderById(Object $request)
    {
        return $this->orderRepository->orderById($request);
    }

    public function AllOrder(Object $request)
    {
        return $this->orderRepository->allOrder($request);
    }

    public function CreateOrder(Object $request)
    {
        return $this->orderRepository->queryOrderCreate($request);
    }

    public function GetOrderByIdWithProduct($id)
    {
        return $this->orderRepository->queryGetOrderByIdWithProduct($id);
    }

    public function GetOrderByIdStatus($id, $status)
    {
        return $this->orderRepository->queryGetOrderByIdStatus($id, $status);
    }

    public function DeleteOrder($id)
    {
        return $this->orderRepository->queryOderDelete($id);
    }

    public function UpdateOrder(Object $request, Order $order)
    {
        return $this->orderRepository->queryUpdateOrder($request, $order);
    }

    public function UpdateAllOrder(Object $request, Order $order)
    {
        return $this->orderRepository->queryUpdateAll($request, $order);
    }

    public function GetOrder(Object $request, $id)
    {
        $order = $this->orderRepository->queryGetOrder($id);
        $qty = $order->quantity + $request->quantity;
        $totalPrice = $qty * $order->product->price;

        return $order->update([
            'quantity' => $qty,
            'total_price' => $totalPrice
        ]);
    }

    public function GetTotalprice($id, $qty)
    {
        $product = $this->orderRepository->queryGetTotalPrice($id);
        return $product->price * $qty;
    }

    public function GetOrderByStatus($id, $status)
    {
        $total = 0;
        $orders = $this->orderRepository->queryGetOrderByStatus($id, $status);

        foreach($orders as $item) {
            $total += $item->total_price;
        }
        return $total;
    }

    public function GetTotalByStatus()
    {
        return $this->orderRepository->queryGetTotalByStatus();
    }

    public function queryGetTotalByUserStatus($id)
    {
        return $this->orderRepository->queryGetTotalByUserStatus($id);
    }
}
