<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <title>
        Booking Report
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #222;
            font-size: 12px;
        }

        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
        }

        .company {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
        }

        .subtitle {
            color: #666;
            margin-top: 4px;
        }

        .report-title {
            margin-top: 15px;
            font-size: 18px;
            font-weight: bold;
        }

        .period {
            color: #666;
            margin-top: 5px;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .summary td {
            width: 25%;
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        .summary-label {
            font-size: 11px;
            color: #666;
        }

        .summary-value {
            margin-top: 8px;
            font-size: 18px;
            font-weight: bold;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #2563eb;
            color: white;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table tr:nth-child(even) {
            background: #f8fafc;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 11px;
            color: #888;
        }
    </style>
</head>

<body>

    <div class="header">

        <div class="company">
            GASSIN TRAVEL
        </div>

        <div class="subtitle">
            Booking & Revenue Monthly Report
        </div>

        <div class="report-title">
            Booking Report
        </div>

        <div class="period">
            Period :
            {{ \Carbon\Carbon::createFromFormat('Y-m', $period)->format('F Y') }}
        </div>

    </div>

    <table class="summary">

        <tr>

            <td>

                <div class="summary-label">
                    Total Booking
                </div>

                <div class="summary-value">
                    {{ $summary['total_booking'] }}
                </div>

            </td>

            <td>

                <div class="summary-label">
                    Booking Paid
                </div>

                <div class="summary-value">
                    {{ $summary['total_paid'] }}
                </div>

            </td>

            <td>

                <div class="summary-label">
                    Total Seat
                </div>

                <div class="summary-value">
                    {{ $summary['total_seat'] }}
                </div>

            </td>

            <td>

                <div class="summary-label">
                    Revenue
                </div>

                <div class="summary-value">
                    Rp {{ number_format($summary['revenue'], 0, ',', '.') }}
                </div>

            </td>

        </tr>

    </table>

    <table class="table">

        <thead>

            <tr>

                <th>No</th>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Seat</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Date</th>

            </tr>

        </thead>

        <tbody>

            @forelse($bookings as $booking)
                <tr>

                    <td>
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        {{ $booking->order_id }}
                    </td>

                    <td>
                        {{ $booking->user->name ?? '-' }}
                    </td>

                    <td>
                        {{ $booking->total_seat }}
                    </td>

                    <td>
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </td>

                    <td>
                        {{ ucfirst($booking->status) }}
                    </td>

                    <td>
                        {{ ucfirst($booking->payment_status) }}
                    </td>

                    <td>
                        {{ $booking->created_at->format('d M Y H:i') }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="8" style="text-align:center;">
                        Tidak ada data booking
                    </td>

                </tr>
            @endforelse

        </tbody>

    </table>

    <div class="footer">

        Generated :
        {{ now()->format('d M Y H:i:s') }}

    </div>

</body>

</html>
