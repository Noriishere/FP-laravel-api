<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', now()->format('Y-m'));

        try {
            $date = Carbon::createFromFormat('Y-m', $period);
        } catch (\Exception $e) {
            $date = now();
        }

        $month = $date->month;
        $year = $date->year;
        $traffic = Booking::selectRaw('DATE(created_at) as date')
            ->selectRaw('COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $query = Booking::with('user')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month);

        $topCustomers = Booking::join('users', 'users.id', '=', 'bookings.user_id')
            ->select(
                'users.name',
                DB::raw('COUNT(bookings.id) as total_booking'),
                DB::raw('SUM(bookings.total_price) as total_spent')
            )
            ->whereYear('bookings.created_at', $year)
            ->whereMonth('bookings.created_at', $month)
            ->where('bookings.payment_status', 'paid')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_booking')
            ->limit(5)
            ->get();

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = (clone $query)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $summary = [
            'total_booking' => (clone $query)->count(),

            'total_paid' => (clone $query)
                ->where('payment_status', 'paid')
                ->count(),

            'total_seat' => (clone $query)
                ->sum('total_seat'),

            'revenue' => (clone $query)
                ->where('payment_status', 'paid')
                ->sum('total_price'),
        ];

        return view('pages.reports.index', [
            'bookings' => $bookings,
            'summary' => $summary,
            'period' => $period,
            'traffic' => $traffic,
            'topCustomers' => $topCustomers,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $period = $request->period ?? now()->format('Y-m');

        $date = Carbon::createFromFormat('Y-m', $period);

        $bookings = Booking::with('user')
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->get();
        $summary = [
            'total_booking' => $bookings->count(),
            'total_paid' => $bookings->where('payment_status', 'paid')->count(),
            'total_seat' => $bookings->sum('total_seat'),
            'revenue' => $bookings->where('payment_status', 'paid')->sum('total_price'),
        ];

        $pdf = Pdf::loadView(
            'reports.pdf',
            compact(
                'bookings',
                'period',
                'summary'
            )
        );

        return $pdf->download(
            'report-'.$period.'.pdf'
        );
    }
}
