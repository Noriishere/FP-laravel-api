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
        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        if ($booking->payment_status === 'paid') {
            return response()->json([
                'message' => 'Booking already paid',
            ], 400);
        }

        if ($booking->expired_at && $booking->expired_at < now()) {
            return response()->json([
                'message' => 'Booking expired',
            ], 400);
        }

        $response = Http::post(
            'https://app.pakasir.com/api/transactioncreate/qris',
            [
                'project' => config('pakasir.project'),
                'order_id' => $booking->order_id,
                'amount' => (int) $booking->total_price,
                'api_key' => config('pakasir.api_key'),
            ]
        );

        if (! $response->successful()) {
            return response()->json([
                'message' => 'Failed to create payment',
            ], 500);
        }

        $data = $response->json();

        if (! isset($data['payment'])) {
            return response()->json([
                'message' => 'Invalid response from payment gateway',
            ], 500);
        }

        $payment = $data['payment'];

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
            'payment' => $payment,
        ]);
    }

    public function webhook(Request $request)
    {
        Log::info(
            'Webhook Pakasir',
            $request->all()
        );

        $orderId = $request->order_id;

        $amount = $request->amount;

        $status = $request->status;

        $booking = Booking::with([
            'pickupStop',
            'dropoffStop',
            'bookingSeats',
        ])
            ->where(
                'order_id',
                $orderId
            )
            ->first();

        if (! $booking) {

            return response()->json([
                'message' => 'Booking not found',
            ], 404);
        }

        if (
            $booking->payment_status
            ===
            'paid'
        ) {

            return response()->json([
                'message' => 'Already processed',
            ]);
        }

        if (
            $booking->total_price
            !=
            $amount
        ) {

            return response()->json([
                'message' => 'Invalid amount',
            ], 400);
        }

        if (
            $booking->expired_at
            &&
            $booking->expired_at < now()
        ) {

            return response()->json([
                'message' => 'Booking expired',
            ], 400);
        }

        if ($status === 'completed') {

            DB::transaction(function () use (
                $booking,
                $request
            ) {

                $seatIds = $booking->bookingSeats
                    ->pluck('seat_id');

                $pickupOrder =
                    $booking->pickupStop?->order;

                $dropoffOrder =
                    $booking->dropoffStop?->order;

                $conflict = Booking::with([
                    'pickupStop',
                    'dropoffStop',
                    'bookingSeats',
                ])
                    ->where(
                        'schedule_id',
                        $booking->schedule_id
                    )
                    ->where(
                        'id',
                        '!=',
                        $booking->id
                    )
                    ->whereIn(
                        'payment_status',
                        [
                            'paid',
                            'pending',
                        ]
                    )
                    ->get()
                    ->contains(function ($existingBooking) use (
                        $pickupOrder,
                        $dropoffOrder,
                        $seatIds
                    ) {

                        if (
                            ! $existingBooking->pickupStop
                            ||
                            ! $existingBooking->dropoffStop
                        ) {
                            return false;
                        }

                        $existingPickup =
                            $existingBooking
                                ->pickupStop
                                ->order;

                        $existingDropoff =
                            $existingBooking
                                ->dropoffStop
                                ->order;

                        $segmentOverlap =
                            $pickupOrder
                            <
                            $existingDropoff
                            &&
                            $dropoffOrder
                            >
                            $existingPickup;

                        if (! $segmentOverlap) {
                            return false;
                        }

                        $existingSeatIds =
                            $existingBooking
                                ->bookingSeats
                                ->pluck('seat_id');

                        return $seatIds
                            ->intersect(
                                $existingSeatIds
                            )
                            ->isNotEmpty();
                    });

                if ($conflict) {

                    Log::warning(
                        'Seat conflict after payment',
                        [
                            'booking_id' => $booking->id,
                        ]
                    );

                    $booking->update([

                        'status' => 'cancelled',

                        'payment_status' => 'failed',
                    ]);

                    return;
                }

                $booking->update([

                    'status' => 'paid',

                    'payment_status' => 'paid',

                    'payment_method' => $request->payment_method,
                ]);
            });
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function callback(Request $request)
    {
        Log::info('Payment Callback', [
            'payload' => $request->all(),
        ]);

        $orderId = $request->order_id;

        $status = strtolower(
            trim($request->status)
        );

        $booking = Booking::where(
            'order_id',
            $orderId
        )->first();

        if (! $booking) {

            return response()->json([
                'message' => 'Booking not found',
            ], 404);
        }

        if (
            in_array(
                $status,
                [
                    'paid',
                    'completed',
                    'success',
                ]
            )
        ) {

            $booking->update([

                'status' => 'paid',

                'payment_status' => 'paid',
            ]);

            return response()->json([

                'success' => true,

                'message' => 'Payment successful',

                'data' => [

                    'order_id' => $booking->order_id,

                    'payment_status' => $booking->payment_status,

                    'status' => $booking->status,
                ],
            ]);
        }

        if (
            in_array(
                $status,
                [
                    'pending',
                    'waiting',
                    'unpaid',
                ]
            )
        ) {

            $booking->update([

                'status' => 'pending',

                'payment_status' => 'pending',
            ]);

            return response()->json([

                'success' => false,

                'message' => 'Payment not completed yet',

                'data' => [

                    'order_id' => $booking->order_id,

                    'payment_status' => $booking->payment_status,

                    'status' => $booking->status,
                ],
            ], 400);
        }

        if (
            in_array(
                $status,
                [
                    'failed',
                    'expired',
                    'cancelled',
                ]
            )
        ) {

            $booking->update([

                'status' => 'cancelled',

                'payment_status' => $status,
            ]);

            return response()->json([

                'success' => false,

                'message' => 'Payment failed or expired',

                'data' => [

                    'order_id' => $booking->order_id,

                    'payment_status' => $booking->payment_status,

                    'status' => $booking->status,
                ],
            ], 400);
        }

        return response()->json([

            'success' => false,

            'message' => 'Unknown payment status',

            'received_status' => $request->status,

            'normalized_status' => $status,
        ], 400);
    }

    public function cancel(Request $request)
    {
        try {

            $booking = Booking::where('id', $request->booking_id)
                ->where('user_id', auth('api')->id())
                ->firstOrFail();

            DB::transaction(function () use ($booking) {

                $booking->update([
                    'status' => 'cancelled',
                    'payment_status' => 'cancelled',
                ]);

                $seatIds = DB::table('booking_seats')
                    ->where('booking_id', $booking->id)
                    ->pluck('seat_id');

                DB::table('seats')
                    ->whereIn('id', $seatIds)
                    ->update(['status' => 'available']);
            });

            return response()->json([
                'message' => 'Booking cancelled successfully',
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }
}
