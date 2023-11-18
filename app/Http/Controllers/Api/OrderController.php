<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Services\Midtrans\CreatePaymentUrlService;

class OrderController extends Controller
{
    public function sendNotificationToUser($userId, $message)
    {
        $user = User::find($userId);
        $token = $user->fcm_token;

        $messaging = app('firebase.messaging');
        $notification = Notification::create('Order Masuk', $message);

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification);

        $messaging->send($message);
    }
    public function order(Request $request)
    {
        $order = Order::create([
            'user_id' => $request->user()->id,
            'seller_id' => $request->seller_id,
            'number' => time(),
            'total_price' => $request->total_price,
            'payment_status' => 1,
            'delivery_address' => $request->delivery_address,
            'shipper' => $request->shipper,
            'shipping_cost' => $request->shipping_cost,
        ]);

        foreach ($request->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity']
            ]);
        }

        $this->sendNotificationToUser($request->seller_id, 'Order ' . number_format($request->total_price) . ' masuk, menunggu pembayaran');
        $midtrans = new CreatePaymentUrlService();
        $paymentUrl = $midtrans->getPaymentUrl($order->load('user', 'orderItems'));

        $order->update([
            'payment_url' => $paymentUrl
        ]);
        return response()->json([
            'data' => $order
        ]);
    }
    public function orderById(Request $request)
    {
        $user_id = $request->query('user_id');
        $seller_id = $request->query('seller_id');
        $payment_status = $request->query('payment_status');
        $order = Order::when(
            $user_id,
            fn ($query, $user_id) => $query->where('user_id', '=', $user_id)
        )->when(
            $seller_id,
            fn ($query, $seller_id) => $query->where('seller_id', '=', $seller_id)
        )->when(
            $payment_status,
            fn ($query, $payment_status) => $query->where('payment_status', '=', $payment_status)
        )
            ->get();
        // $order->load('orderItems', 'user');
        return new OrderResource($order);
    }
}
