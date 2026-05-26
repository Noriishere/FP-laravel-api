<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
</head>

<body style="
    margin:0;
    padding:0;
    background:#F9F7F4;
    font-family:Arial, Helvetica, sans-serif;
">

    <table width="100%" cellpadding="0" cellspacing="0" border="0"
        style="
            background:#F9F7F4;
            padding:40px 20px;
        ">

        <tr>
            <td align="center">

                {{-- CARD --}}
                <table width="600" cellpadding="0" cellspacing="0" border="0"
                    style="
                        background:#FFF5F5;
                        border:1px solid #FFD6D6;
                        border-radius:24px;
                        overflow:hidden;
                    ">

                    {{-- HEADER --}}
                    <tr>
                        <td
                            style="
                            background:linear-gradient(135deg, #E82C2C, #C41F1F);
                            padding:40px 32px;
                            text-align:center;
                            color:white;
                        ">

                            <h1
                                style="
                                margin:0;
                                font-size:30px;
                                line-height:1.3;
                                font-weight:bold;
                            ">
                                Pembayaran Berhasil 🎉
                            </h1>

                            <p
                                style="
                                margin-top:12px;
                                font-size:15px;
                                opacity:.95;
                            ">
                                Terima kasih sudah menggunakan GASSIN
                            </p>

                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td
                            style="
                            padding:40px;
                            color:#111010;
                            line-height:1.8;
                            font-size:15px;
                        ">

                            <p style="margin-top:0;">
                                Halo
                                <strong>
                                    {{ $booking->user?->name }}
                                </strong>,
                            </p>

                            <p>
                                Pembayaran shuttle kamu berhasil diproses.
                                Berikut detail perjalanan kamu:
                            </p>

                            {{-- DETAIL BOX --}}
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="
                                    margin-top:30px;
                                    background:#FFFFFF;
                                    border:1px solid #F3D7D7;
                                    border-radius:16px;
                                    padding:24px;
                                ">

                                {{-- PERJALANAN --}}
                                <tr>
                                    <td
                                        style="
        padding:14px 0;
        color:#6B6B6B;
        font-size:14px;
        vertical-align:top;
    ">
                                        Perjalanan
                                    </td>

                                    <td align="right"
                                        style="
        padding:14px 0;
        color:#111010;
        font-weight:bold;
        line-height:1.6;
    ">

                                        {{ $booking->pickupStop?->name }}
                                        →
                                        {{ $booking->dropoffStop?->name }}

                                        <br>

                                        <span
                                            style="
                font-size:12px;
                color:#6B6B6B;
                font-weight:normal;
            ">
                                            {{ $booking->schedule?->route?->name }}
                                        </span>

                                    </td>
                                </tr>

                                {{-- KURSI --}}
                                <tr>
                                    <td
                                        style="
                                        padding:14px 0;
                                        color:#6B6B6B;
                                        font-size:14px;
                                    ">
                                        Kursi
                                    </td>

                                    <td align="right"
                                        style="
                                        padding:14px 0;
                                        color:#111010;
                                        font-weight:bold;
                                    ">

                                        {{ $booking->bookingSeats->isNotEmpty()
                                            ? $booking->bookingSeats->pluck('seat.seat_number')->filter()->join(', ')
                                            : '-' }}

                                    </td>
                                </tr>

                                {{-- TOTAL --}}
                                <tr>
                                    <td
                                        style="
                                        padding:14px 0;
                                        color:#6B6B6B;
                                        font-size:14px;
                                    ">
                                        Total Pembayaran
                                    </td>

                                    <td align="right"
                                        style="
                                        padding:14px 0;
                                        color:#E82C2C;
                                        font-weight:bold;
                                        font-size:20px;
                                    ">

                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}

                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td
                            style="
                            background:#FFF0F0;
                            padding:24px;
                            text-align:center;
                            color:#888888;
                            font-size:13px;
                            line-height:1.6;
                        ">

                            © {{ date('Y') }} GASSIN <br>
                            Shuttle nyaman untuk perjalananmu.

                        </td>
                    </tr>

                </table>

            </td>
        </tr>

    </table>

</body>

</html>
