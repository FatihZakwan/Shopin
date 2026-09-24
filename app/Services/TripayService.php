<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TripayService
{
    protected $apiKey;
    protected $privateKey;
    protected $merchantCode;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = env('TRIPAY_API_KEY');
        $this->privateKey = env('TRIPAY_PRIVATE_KEY');
        $this->merchantCode = env('TRIPAY_MERCHANT_CODE');
        
        // URL Sandbox Tripay
        $this->apiUrl = 'https://tripay.co.id/api-sandbox/';
    }

    // Mengambil daftar channel pembayaran dari Tripay
    public function getPaymentChannels()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get($this->apiUrl . 'merchant/payment-channel');

        return json_decode($response->body());
    }

    // Membuat transaksi pembayaran baru di Tripay
    public function createTransaction($order, $paymentMethod)
    {
        $privateKey = $this->privateKey;
        $merchantCode = $this->merchantCode;
        $merchantRef = $order->invoice;
        $amount = (int) $order->total;

        // Signature Tripay
        $signature = hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey);

        $items = [];
        
        // Menggunakan $order->orderItems (bukan $order->items)
        // Dan ditambahkan pengecekan agar tidak null
        $orderItems = $order->orderItems ?? [];
        
        foreach ($orderItems as $item) {
            $items[] = [
                'name'     => $item->product_name ?? ($item->product->name ?? 'Produk'),
                'price'    => (int) $item->price,
                'quantity' => (int) $item->quantity,
            ];
        }

        $payload = [
            'method'         => $paymentMethod,
            'merchant_ref'   => $merchantRef,
            'amount'         => $amount,
            'customer_name'  => $order->customer_name,
            'customer_email' => $order->customer_email,
            'customer_phone' => $order->phone,
            'order_items'    => $items,
            'return_url'     => route('checkout.success'),
            'expired_time'   => (time() + (24 * 60 * 60)), // Expired 24 Jam
            'signature'      => $signature,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post($this->apiUrl . 'transaction/create', $payload);

        return json_decode($response->body());
    }
}