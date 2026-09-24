<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class TripayCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $privateKey = env('TRIPAY_PRIVATE_KEY');
        $callbackSignature = $request->header('X-Callback-Signature');
        $json = $request->getContent();

        // Validasi Signature dari Tripay
        $signature = hash_hmac('sha256', $json, $privateKey);

        if ($signature !== $callbackSignature) {
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
        }

        $data = json_decode($json);

        if ($request->header('X-Callback-Event') === 'payment_status') {
            $orderId = $data->merchant_ref;
            $status = $data->status;

            $order = Order::find($orderId);

            if ($order) {
                if ($status === 'PAID') {
                    $order->update(['status' => 'PAID']);
                } elseif (in_array($status, ['EXPIRED', 'FAILED'])) {
                    $order->update(['status' => 'FAILED']);
                }
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Unrecognized event'], 400);
    }
}