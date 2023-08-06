<?php

namespace App\Http\Controllers;

use App\Http\Service\OrderModelService;
use App\Http\Service\CustomCakeModelService;
use App\Http\Service\UserModelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use Notification;
use App\Notifications\EmailNotification;

class OrderController extends Controller
{
    private $orderModelService;
    private $customCakeModelService;
    private $userModelService;

    public function __construct(OrderModelService $orderModelService, customCakeModelService $customCakeModelService, UserModelService $userModelService)
    {
        $this->orderModelService = $orderModelService;
        $this->customCakeModelService = $customCakeModelService;
        $this->userModelService = $userModelService;
    }

    public function index(Request $request) {
        if($request->status === "onCart") {
            $orders = $this->orderModelService->OrderOnCart($request);
        } else if (isset($request->status)) {
            $orders = $this->orderModelService->OrderById($request);
        } else {
            $orders = $this->orderModelService->AllOrder($request);
        }

        return response()->json([
            "data" => $orders
        ]);
    }

    public function store(Request $request) {

        $image = "";
        if($request->hasFile('image')) {
            $image = $request->image->store('custom-cakes');
            $img = Image::make(public_path('storage/' . $image))->fit(400, 500);
            $img->save();
        }

        $totalPrice = empty($request->product_id)
                    ? 0
                    : $this->getTotalPrice($request->product_id, $request->quantity);

        $order = (object)([
            "user_id" => Auth::id(),
            "product_id" => $request->product_id,
            "quantity" => $request->quantity,
            "total_price" => $totalPrice,
            "status" => $request->status,
            "message" => $request->message,
            "type" => $request->type,
            "image" => $image,
            "remarks" => $request->remarks,
            "delivery_date" => $request->delivery_date,
            "delivery_address" => $request->delivery_address,
        ]);

        $orders = $this->orderModelService->CreateOrder($order);

        return response()->json([
            "message" => "Added to Cart",
            "data" => $order,
        ]);
    }

    public function show($id) {
        $data = $this->orderModelService->GetOrderByIdWithProduct($id);

        return response()->json([
            'data' => $data
        ]);
    }

    public function getUserCart(Request $request) {
        $id = Auth::user()->id;
        $status = $request->status;

        $orders = $this->orderModelService->GetOrderByIdStatus($id, $status);

        return response()->json([
            "message" => "Fetch All Cart Success",
            "data" => $orders,
            "status" => $status
        ]);
    }

    public function delete($id) {

        $this->orderModelService->DeleteOrder($id);
        return response()->json([
            'message' => 'Order has been deleted.',
        ]);
    }

    public function update(Request $request, $id) {
        $order = $this->orderModelService->GetOrderbyId($id);

        if($order->status == "onCart" && $order->total_price == 0) {
            $this->orderModelService->UpdateOrder($request, $order);
        }
        else {
            $this->orderModelService->UpdateAllOrder($request, $order);
        }

        $this->sendMail($order, $request->status);

        return response()->json([
            'message' => 'Order has been updated.',
            'data' => $order
        ]);
    }

    public function updateAddToCart(Request $request, $id) {
        $order = $this->orderModelService->GetOrder($request, $id);

        return response()->json([
            'message' => "Product Added to Cart",
            'data' => $order
        ]);
    }

    public function getTotalPrice($id, $qty) {
        return $this->orderModelService->GetTotalprice($id, $qty);
    }

    public function getTotalOrder($orders) {
        $total = 0;
        foreach($orders as $item) {
            $total += $item->total_price;
        }

        return $total;
    }

    public function getTotalOfAllItems(Request $request) {
        $id = Auth::user()->id;
        $total = $this->orderModelService->GetOrderByStatus($id, $request->status);
        $customCakes = $this->customCakeModelService->UserCustomeCake($id, $request->status);

        foreach($customCakes as $item) {
            $total += $item->price * $item->quantity;
        }

        return response()->json([
            'message' => "Total Price of all items in the cart",
            'totalPrice' => $total,
        ]);
    }

    public function getQtyEachOrder(Request $request) {

        [$onCart, $toPay, $processing, $delivery, $completed] = $this->orderModelService->GetTotalByStatus();
        return response()->json([
            'data' => [
                'oncart' => $onCart,
                'topay' => $toPay,
                'processing' => $processing,
                'delivery' => $delivery,
                'completed' => $completed,
            ]
        ]);
    }

    public function getQtyEachUserOrder(Request $request) {
        $id = Auth::user()->id;
        [$oncart, $paid, $process, $deliver, $completed] = $this->orderModelService->queryGetTotalByUserStatus($id);
        return response()->json([
            "oncart" => $oncart,
            "paid" => $paid,
            "process" => $process,
            "deliver" => $deliver,
            "completed" => $completed,
        ]);
    }

    public function sendMail($order, $status) {
        $user = $this->userModelService->GetUserById($order->user_id)->first();

        $details = [
            'greeting' => 'Hi ' . $user['first_name'],
            'details' => 'Heres the updated details of your order: ' ,
            'order' => "Order ID: " . $order->id,
            'date' => "Delivery Date: " . $order->delivery_date,
            'address' => "Delivery Address: " . $order->delivery_address,
            'status' => "Status: " . $order->status,
            'thanks' => 'Thank you for your patience',
            'actionText' => 'Website',
            'actionURL' => url('https://purplebox.com'),
        ];

        Notification::send($user, new EmailNotification($details));
    }
}
