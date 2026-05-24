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
    background:#f4f4f5;
    font-family:Arial, Helvetica, sans-serif;
">

    <table width="100%" cellpadding="0" cellspacing="0" border="0"
        style="background:#f4f4f5; padding:40px 20px;">

        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" border="0"
                    style="
                        background:#ffffff;
                        border-radius:20px;
                        overflow:hidden;
                    ">

                    {{-- HEADER --}}
                    <tr>
                        <td style="
                            background:#C00707;
                            padding:32px;
                            text-align:center;
                            color:white;
                        ">

                            <h1 style="
                                margin:0;
                                font-size:28px;
                            ">
                                Pembayaran Berhasil 🎉
                            </h1>

                            <p style="
                                margin-top:10px;
                                font-size:15px;
                                opacity:.9;
                            ">
                                Terima kasih sudah menggunakan GASSIN
                            </p>

                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="
                            padding:40px;
                            color:#333333;
                            line-height:1.7;
                        ">

                            <p style="margin-top:0;">
                                Halo
                                <strong>
                                    {{ $booking->user?->name }}
                                </strong>,
                            </p>

                            <p>
                                Pembayaran shuttle kamu berhasil diproses.
                            </p>

                            <hr style="
                                border:none;
                                border-top:1px solid #eeeeee;
                                margin:30px 0;
                            ">

                            <h3 style="
                                margin-top:0;
                                color:#111827;
                            ">
                                Detail Perjalanan
                            </h3>

                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="
                                    background:#fafafa;
                                    border-radius:12px;
                                    padding:20px;
                                ">

                                {{-- RUTE --}}
                                <tr>
                                    <td style="
                                        padding:12px 0;
                                        color:#666;
                                    ">
                                        Rute
                                    </td>

                                    <td align="right" style="
                                        padding:12px 0;
                                        font-weight:bold;
                                        color:#111827;
                                    ">

                                        {{ $booking->schedule?->origin?->name }}
                                        →

                                        {{ $booking->schedule?->destination?->name }}

                                    </td>
                                </tr>

                                {{-- KURSI --}}
                                <tr>
                                    <td style="
                                        padding:12px 0;
                                        color:#666;
                                    ">
                                        Kursi
                                    </td>

                                    <td align="right" style="
                                        padding:12px 0;
                                        font-weight:bold;
                                        color:#111827;
                                    ">

                                        {{ $booking->seat_number
                                            ?? $booking->seat?->seat_number
                                            ?? '-' }}

                                    </td>
                                </tr>

                                {{-- TOTAL --}}
                                <tr>
                                    <td style="
                                        padding:12px 0;
                                        color:#666;
                                    ">
                                        Total
                                    </td>

                                    <td align="right" style="
                                        padding:12px 0;
                                        font-weight:bold;
                                        color:#C00707;
                                        font-size:18px;
                                    ">

                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}

                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="
                            padding:24px;
                            text-align:center;
                            font-size:13px;
                            color:#999999;
                            background:#fafafa;
                        ">

                            © {{ date('Y') }} GASSIN.
                            Semua hak dilindungi.

                        </td>
                    </tr>

                </table>

            </td>
        </tr>

    </table>

</body>

</html>