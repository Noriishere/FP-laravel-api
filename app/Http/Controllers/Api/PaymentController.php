<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
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
