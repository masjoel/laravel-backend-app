<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Services\Midtrans\CallbackService;

class CallbackController extends Controller
{

    // public function __construct()
    // {
    //     $callback = new CallbackService;
    //     $this->notification = $callback->getNotification();
    //     $this->order = $callback->getOrder();
    //     $this->orderItem  = Order::where('number', $this->order->number)->with('orderItems')->with('orderItems.product')->get();
    //     $this->serverKey = config('midtrans.server_key');
    // }
    // private function _createLocalsignatureKey()
    // {
    //     $total = new Collection();
    //     foreach ($this->orderItem[0]->orderItems as $item) {
    //         $total->push([
    //             'total' => $item->product->price * $item->quantity,
    //         ]);
    //     }
    //     $orderId = $this->order->number;
    //     $statusCode = $this->notification->status_code;
    //     $grossAmount = number_format($total->sum('total'), 2, '.', '');
    //     $serverkey = $this->serverKey;
    //     $input = $orderId . $statusCode . $grossAmount . $serverkey;
    //     $signature = openssl_digest($input, 'sha512');
    //     return $signature;
    // }
    public function callback()
    {
        // $sign = $this->_createLocalsignatureKey();
        $callback = new CallbackService;
        if ($callback->isSignatureKeyVerified()) {
            $notification = $callback->getNotification();
            $order = $callback->getOrder();

            if ($callback->isSuccess()) {
                Order::where('id', $order->id)->update([
                    'payment_status' => 2,
                ]);
            }

            if ($callback->isExpire()) {
                Order::where('id', $order->id)->update([
                    'payment_status' => 3,
                ]);
            }

            if ($callback->isCancelled()) {
                Order::where('id', $order->id)->update([
                    'payment_status' => 3,
                ]);
            }

            return response()
                ->json([
                    'success' => true,
                    'message' => 'Notification successfully processed',
                ]);
        } else {
            return response()
                ->json([
                    'error' => true,
                    'message' => 'Signature key not verified',
                ], 403);
        }
    }
}
