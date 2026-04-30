<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $booking = Booking::findOrFail($request->booking_id);

        if ($booking->payment_status === 'paid') {
            return response()->json([
                'message' => 'Booking already paid'
            ], 400);
        }

        if ($booking->expired_at && $booking->expired_at < now()) {
            return response()->json([
                'message' => 'Booking expired'
            ], 400);
        }

        $response = Http::post(
            'https://app.pakasir.com/api/transactioncreate/qris',
            [
                "project" => config('pakasir.project'),
                "order_id" => $booking->order_id,
                "amount" => (int) $booking->total_price,
                "api_key" => config('pakasir.api_key'),
            ]
        );

        if (!$response->successful()) {
            return response()->json([
                'message' => 'Failed to create payment'
            ], 500);
        }

        $data = $response->json();

        if (!isset($data['payment'])) {
            return response()->json([
                'message' => 'Invalid response from payment gateway'
            ], 500);
        }

        $payment = $data['payment'];

        // 🔥 update DB
        $booking->update([
            'payment_provider' => 'pakasir',
            'payment_method' => 'qris',
            'payment_status' => 'pending',
            'expired_at' => isset($payment['expired_at'])
                ? Carbon::parse($payment['expired_at'])->format('Y-m-d H:i:s')
                : now()->addMinutes(15),
        ]);

        return response()->json([
            'type' => 'qr',
            'payment' => $payment
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

        if ($booking->payment_status === 'paid') {
            return response()->json(['message' => 'Already processed']);
        }

        if ($booking->total_price != $amount) {
            return response()->json(['message' => 'Invalid amount'], 400);
        }

        if ($status === 'completed') {

            DB::transaction(function () use ($booking, $request) {

                $booking->update([
                    'status' => 'paid',
                    'payment_status' => 'paid',
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
