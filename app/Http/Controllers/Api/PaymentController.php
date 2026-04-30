<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $booking = Booking::findOrFail($request->booking_id);

        $response = Http::post(
            'https://app.pakasir.com/api/transactioncreate/qris',
            [
                "project" => config('pakasir.project'),
                "order_id" => $booking->order_id,
                "amount" => $booking->total_price,
                "api_key" => config('pakasir.api_key'),
            ]
        );

        $data = $response->json();

        return response()->json([
            'payment' => $data['payment']
        ]);
    }
    public function webhook(Request $request)
    {
        Log::info('Webhook Pakasir', $request->all());

        $orderId = $request->order_id;
        $amount = $request->amount;
        $status = $request->status;

        $booking = Booking::where('order_id', $orderId)->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if ($booking->status === 'paid') {
            return response()->json(['message' => 'Already processed']);
        }

        if ($booking->total_price != $amount) {
            return response()->json(['message' => 'Invalid amount'], 400);
        }

        if ($status === 'completed') {

            DB::transaction(function () use ($booking, $request) {

                $booking->update([
                    'status' => 'paid',
                    'payment_method' => $request->payment_method
                ]);

                $seatIds = DB::table('booking_seats')
                    ->where('booking_id', $booking->id)
                    ->pluck('seat_id');

                DB::table('seats')
                    ->whereIn('id', $seatIds)
                    ->update(['status' => 'booked']);
            });
        }

        return response()->json(['success' => true]);
    }
}
