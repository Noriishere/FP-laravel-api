<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Pembayaran Berhasil</title>
</head>

<body style="font-family: Arial, sans-serif; background:#F9F7F4; padding:40px;">

    <div style="max-width:600px; margin:auto; background:white; border-radius:20px; padding:40px;">

        <h1 style="color:#C00707;">
            Pembayaran Berhasil 🎉
        </h1>

        <p>
            Halo {{ $booking->user->name }},
        </p>

        <p>
            Pembayaran shuttle kamu berhasil diproses.
        </p>

        <hr style="margin:30px 0;">

        <h3>Detail Perjalanan</h3>

        <p>
            <strong>Rute:</strong>
            {{ $booking->schedule->origin }}
            →
            {{ $booking->schedule->destination }}
        </p>

        <p>
            <strong>Kursi:</strong>
            {{ $booking->seat_number }}
        </p>

        <p>
            <strong>Total:</strong>
            Rp {{ number_format($booking->total_price) }}
        </p>

        <div style="margin-top:40px;">

            <a href="{{ url('/booking/' . $booking->id) }}"
                style="
                    background:#C00707;
                    color:white;
                    padding:14px 24px;
                    text-decoration:none;
                    border-radius:10px;
                    font-weight:bold;
                ">
                Lihat Tiket
            </a>

        </div>

    </div>

</body>

</html>
