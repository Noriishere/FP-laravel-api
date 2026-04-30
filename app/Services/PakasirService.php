<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class PakasirService
{
    public function createTransaction($booking)
    {
        $response = Http::post(
            config('pakasir.base_url') . '/transactioncreate/qris',
            [
                "project" => config('pakasir.project'),
                "order_id" => $booking->order_id,
                "amount" => $booking->total_price,
                "api_key" => config('pakasir.api_key'),
            ]
        );

        return $response->json();
    }

    public function transactionDetail($booking)
    {
        $url = config('pakasir.base_url') . '/transactiondetail?' . http_build_query([
            'project' => config('pakasir.project'),
            'amount' => $booking->total_price,
            'order_id' => $booking->order_id,
            'api_key' => config('pakasir.api_key'),
        ]);

        $response = Http::get($url);

        return $response->json();
    }
}