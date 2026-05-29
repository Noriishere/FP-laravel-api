@extends('layouts.app')

@section('content')
    <div class="space-y-5">


        {{-- HEADER --}}
        <div class="bg-white rounded-2xl shadow">

            <div class="p-5 border-b">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    <div>

                        <h2 class="text-lg font-bold text-gray-800">
                            Booking Reports
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Monthly booking analytics and revenue report
                        </p>

                    </div>

                    <div class="flex gap-2">

                        <a href="{{ route('admin.reports.pdf', ['period' => request('period')]) }}" target="_blank" download="{{ route('admin.reports.pdf', ['period' => request('period')]) }}"
                            class="bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-red-700 transition">

                            <i class="fa-solid fa-file-pdf mr-2"></i>
                            Download PDF

                        </a>

                    </div>

                </div>

            </div>

        </div>

        {{-- STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="bg-white rounded-xl shadow p-5">

                <p class="text-sm text-gray-500">
                    Total Booking
                </p>

                <h2 class="text-3xl font-bold mt-2">
                    {{ number_format($summary['total_booking']) }}
                </h2>

            </div>

            <div class="bg-white rounded-xl shadow p-5">

                <p class="text-sm text-gray-500">
                    Booking Paid
                </p>

                <h2 class="text-3xl font-bold mt-2 text-green-600">
                    {{ number_format($summary['total_paid']) }}
                </h2>

            </div>

            <div class="bg-white rounded-xl shadow p-5">

                <p class="text-sm text-gray-500">
                    Total Penumpang
                </p>

                <h2 class="text-3xl font-bold mt-2 text-blue-600">
                    {{ number_format($summary['total_seat']) }}
                </h2>

            </div>

            <div class="bg-white rounded-xl shadow p-5">

                <p class="text-sm text-gray-500">
                    Revenue
                </p>

                <h2 class="text-3xl font-bold mt-2 text-emerald-600">
                    Rp {{ number_format($summary['revenue'], 0, ',', '.') }}
                </h2>

            </div>

        </div>

        {{-- FILTER --}}
        <div class="bg-white rounded-2xl shadow">

            <div class="p-5">

                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">

                    <div>

                        <input type="month" name="period" value="{{ request('period', now()->format('Y-m')) }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5">

                    </div>

                    <div>

                        <select name="payment_status" class="w-full border border-gray-200 rounded-xl px-4 py-2.5">

                            <option value="">
                                Semua Payment
                            </option>

                            <option value="paid">
                                Paid
                            </option>

                            <option value="pending">
                                Pending
                            </option>

                            <option value="failed">
                                Failed
                            </option>

                        </select>

                    </div>

                    <div>

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari customer / order id"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5">

                    </div>

                    <div>

                        <button class="w-full bg-gray-900 text-white rounded-xl px-4 py-2.5 hover:bg-black transition">

                            <i class="fa-solid fa-filter mr-1"></i>
                            Filter

                        </button>

                    </div>

                </form>

            </div>

        </div>

        {{-- CHART + TOP CUSTOMER --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            <div class="lg:col-span-2 bg-white rounded-2xl shadow">

                <div class="p-5 border-b">

                    <h3 class="font-bold text-gray-800">
                        Traffic Booking Harian
                    </h3>

                </div>

                <div class="p-5">

                    <canvas id="trafficChart" height="120"></canvas>

                </div>

            </div>

            <div class="bg-white rounded-2xl shadow">

                <div class="p-5 border-b">

                    <h3 class="font-bold text-gray-800">
                        Top Customer
                    </h3>

                </div>

                <div class="divide-y">

                    @forelse($topCustomers as $customer)
                        <div class="p-4 flex justify-between items-center">

                            <div>

                                <p class="font-medium text-gray-800">
                                    {{ $customer->name }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    {{ $customer->total_booking }} Booking
                                </p>

                            </div>

                            <div class="text-right">

                                <p class="text-sm font-semibold text-green-600">
                                    Rp {{ number_format($customer->total_spent, 0, ',', '.') }}
                                </p>

                            </div>

                        </div>

                    @empty

                        <div class="p-8 text-center text-gray-500">
                            Tidak ada data
                        </div>
                    @endforelse

                </div>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-2xl shadow">

            <div class="p-5 border-b">

                <h3 class="font-bold text-gray-800">
                    Data Booking
                </h3>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full min-w-[1000px]">

                    <thead class="bg-gray-50">

                        <tr class="text-left text-sm text-gray-500">

                            <th class="px-6 py-4">Order ID</th>
                            <th class="px-6 py-4">Customer</th>
                            <th class="px-6 py-4">Seat</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Payment</th>
                            <th class="px-6 py-4">Tanggal</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($bookings as $booking)
                            <tr class="border-t hover:bg-gray-50 transition">

                                <td class="px-6 py-4 font-medium">
                                    {{ $booking->order_id }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $booking->user->name }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $booking->total_seat }}
                                </td>

                                <td class="px-6 py-4 font-medium">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4">

                                    <span class="text-xs px-3 py-1 rounded-full bg-blue-100 text-blue-700">
                                        {{ ucfirst($booking->status) }}
                                    </span>

                                </td>

                                <td class="px-6 py-4">

                                    <span
                                        class="text-xs px-3 py-1 rounded-full
                            {{ $booking->payment_status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">

                                        {{ ucfirst($booking->payment_status) }}

                                    </span>

                                </td>

                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $booking->created_at->format('d M Y H:i') }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="py-16 text-center text-gray-500">

                                    Tidak ada data booking

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="p-5 border-t">

                {{ $bookings->links() }}

            </div>

        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('trafficChart');

        new Chart(ctx, {

            type: 'line',

            data: {

                labels: @json($traffic->pluck('date')),

                datasets: [{

                    label: 'Booking',

                    data: @json($traffic->pluck('total')),

                    borderColor: '#2563eb',

                    backgroundColor: 'rgba(37,99,235,0.15)',

                    fill: true,

                    tension: 0.4,

                    borderWidth: 3

                }]

            },

            options: {

                responsive: true,

                plugins: {

                    legend: {
                        display: true
                    }

                }

            }

        });
    </script>
@endsection
