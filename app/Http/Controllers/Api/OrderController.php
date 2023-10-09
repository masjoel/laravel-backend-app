<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        $this->sendNotificationToUser($request->seller_id, 'Order ' . $request->total_price . ' masuk, Menunggu pembayaran');
        $midtrans = new CreatePaymentUrlService();
        $paymentUrl = $midtrans->getPaymentUrl($order->load('user', 'orderItems'));

        $order->update([
            'payment_url' => $paymentUrl
        ]);
        return response()->json([
            'data' => $order
        ]);
    }
}