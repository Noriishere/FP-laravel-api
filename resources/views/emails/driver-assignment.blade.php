<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penugasan Driver Baru</title>
</head>

<body style="
    margin:0;
    padding:0;
    background:#F9F7F4;
    font-family:Arial, Helvetica, sans-serif;
">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#F9F7F4;padding:40px 20px;">

        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" border="0" style="
                        background:#FFF5F5;
                        border:1px solid #FFD6D6;
                        border-radius:24px;
                        overflow:hidden;
                    ">

                    {{-- HEADER --}}
                    <tr>
                        <td style="
                            background:linear-gradient(135deg,#E82C2C,#C41F1F);
                            padding:40px 32px;
                            text-align:center;
                            color:white;
                        ">

                            <h1 style="
                                margin:0;
                                font-size:30px;
                                line-height:1.3;
                                font-weight:bold;
                            ">
                                Penugasan Baru 🚐
                            </h1>

                            <p style="
                                margin-top:12px;
                                font-size:15px;
                                opacity:.95;
                            ">
                                Anda mendapatkan jadwal perjalanan baru
                            </p>

                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="
                            padding:40px;
                            color:#111010;
                            line-height:1.8;
                            font-size:15px;
                        ">

                            <p style="margin-top:0;">
                                Halo
                                <strong>
                                    {{ $schedule->driver->user->name ?? 'Driver' }}
                                </strong>,
                            </p>

                            <p>
                                Anda telah ditugaskan untuk menjalankan perjalanan berikut.
                                Silakan periksa aplikasi Driver untuk melihat detail lengkap dan melakukan konfirmasi.
                            </p>

                            {{-- DETAIL --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="
                                    margin-top:30px;
                                    background:#FFFFFF;
                                    border:1px solid #F3D7D7;
                                    border-radius:16px;
                                    padding:24px;
                                ">

                                <tr>
                                    <td style="
                                        padding:14px 0;
                                        color:#6B6B6B;
                                        font-size:14px;
                                    ">
                                        Jadwal Berangkat
                                    </td>

                                    <td align="right" style="
                                        padding:14px 0;
                                        color:#111010;
                                        font-weight:bold;
                                    ">
                                        {{ \Carbon\Carbon::parse($schedule->departure_time)->translatedFormat('d F Y H:i') }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="
                                        padding:14px 0;
                                        color:#6B6B6B;
                                        font-size:14px;
                                    ">
                                        Kendaraan
                                    </td>

                                    <td align="right" style="
                                        padding:14px 0;
                                        color:#111010;
                                        font-weight:bold;
                                    ">
                                        {{ $schedule->vehicle->name ?? '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="
                                        padding:14px 0;
                                        color:#6B6B6B;
                                        font-size:14px;
                                    ">
                                        Harga Perjalanan
                                    </td>

                                    <td align="right" style="
                                        padding:14px 0;
                                        color:#E82C2C;
                                        font-weight:bold;
                                        font-size:20px;
                                    ">
                                        Rp {{ number_format($schedule->price, 0, ',', '.') }}
                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="
                            background:#FFF0F0;
                            padding:24px;
                            text-align:center;
                            color:#888888;
                            font-size:13px;
                            line-height:1.6;
                        ">

                            © {{ date('Y') }} GASSIN <br>
                            Sistem Manajemen Shuttle Modern.

                        </td>
                    </tr>

                </table>

            </td>
        </tr>

    </table>

</body>

</html>