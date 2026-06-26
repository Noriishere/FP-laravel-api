<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ChatbotConversation;
use App\Models\ChatMessage;
use App\Models\Schedule;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    private TelegramService $telegramService;

    private array $badwords = [

        // Indonesia
        'anjing',
        'bangsat',
        'bajingan',
        'tolol',
        'goblok',
        'idiot',
        'kampret',
        'brengsek',
        'keparat',
        'setan',
        'sialan',
        'asu',
        'jancok',
        'cuk',
        'cok',
        'tai',
        'bego',

        // Inggris
        'fuck',
        'fucking',
        'motherf',
        'bitch',
        'asshole',
        'bastard',
        'dick',
        'cock',
        'pussy',
        'slut',
        'whore',
        'damn',
        'crap',

        // Malaysia
        'bodoh',
        'babi',
        'celaka',
        'sial',

        // Filipina
        'putang',
        'gago',
        'ulol',
        'tanga',

        // Thailand
        'kwai',
        'hia',

        // Jepang (romaji)
        'baka',
        'kuso',
        'aho',
        'shine',

        // Korea (romanized)
        'ssibal',
        'gaesaekki',
        'byeongsin',

        // Mandarin (pinyin)
        'shabi',
        'caonima',
        'tamade',

        // Spanyol
        'puta',
        'mierda',
        'cabron',

        // Portugis
        'caralho',
        'porra',

        // Prancis
        'merde',
        'putain',

        // Jerman
        'scheisse',
        'arschloch',

        // Rusia (romanized)
        'blyat',
        'suka',

        // Turki
        'amk',
        'orospu',

        // Arab (romanized)
        'kalb',
        'ibnkalb',
    ];

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    private function searchNearestSchedule($origin, $destination)
    {
        $query = Schedule::with([
            'route:id,name',
            'route.stops' => function ($q) {
                $q->orderBy('order');
            },
            'vehicle:id,name,plate_number',
            'driver:id,user_id',
            'driver.user:id,name',
            'seats:id,schedule_id,seat_number',

            'bookings' => function ($q) {
                $q->whereIn('payment_status', ['paid', 'pending'])
                    ->select('id', 'schedule_id', 'pickup_stop_id', 'dropoff_stop_id');
            },

            'bookings.pickupStop:id,order',
            'bookings.dropoffStop:id,order',
            'bookings.bookingSeats:id,booking_id,seat_id',
        ]);

        $query->whereHas('route.stops', function ($q) use ($origin) {
            $q->where('name', 'like', "%{$origin}%")
                ->where('is_pickup', true);
        });

        $query->whereHas('route.stops', function ($q) use ($destination) {
            $q->where('name', 'like', "%{$destination}%")
                ->where('is_dropoff', true);
        });

        // Minimal berangkat 2 jam dari sekarang
        $query->where('departure_time', '>=', now()->addHours(2));

        return $query
            ->orderBy('departure_time')
            ->limit(3)
            ->get()
            ->map(function ($schedule) {

                $stops = $schedule->route?->stops?->values();

                $pickup = $stops
                    ?->first(fn ($s) => str_contains(strtolower($s->name), strtolower(request('origin'))));

                $dropoff = $stops
                    ?->first(fn ($s) => str_contains(strtolower($s->name), strtolower(request('destination'))));

                $availableSeats = $schedule->seats->count();

                if ($pickup && $dropoff) {

                    foreach ($schedule->segment_availability ?? [] as $segment) {

                        if (
                            $segment['from_stop']['id'] == $pickup->id &&
                            $segment['to_stop']['id'] == $dropoff->id
                        ) {

                            $availableSeats = $segment['available_seats'];
                            break;
                        }
                    }
                }

                return [
                    'route' => $schedule->route->name,
                    'vehicle' => $schedule->vehicle->name,
                    'plate' => $schedule->vehicle->plate_number,
                    'driver' => $schedule->driver?->user?->name,
                    'departure' => Carbon::parse($schedule->departure_time)
                        ->translatedFormat('l, d F Y H:i'),
                    'arrival' => Carbon::parse($schedule->arrival_time)
                        ->translatedFormat('H:i'),
                    'price' => number_format($schedule->price, 0, ',', '.'),
                    'available_seats' => $availableSeats,
                ];
            });
    }

    public function message(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = strtolower(trim($request->message));

        foreach ($this->badwords as $word) {
            if (str_contains($message, $word)) {
                return response()->json([
                    'success' => false,
                    'message' => '⚠️ Pesan mengandung kata yang tidak pantas. Silakan gunakan bahasa yang sopan.',
                ]);
            }
        }
        $conversation = ChatbotConversation::firstOrCreate(
            [
                'user_id' => auth('api')->id(),
            ],
            [
                'state' => 'main_menu',
                'data' => [],
            ]
        );
        if (in_array($message, ['halo', 'hai', 'hi', 'menu', 'start'])) {

            $conversation->update([
                'state' => 'main_menu',
                'data' => [],
            ]);

            return response()->json([
                'success' => true,
                'message' => "Halo 👋 Selamat datang di Chatbot GASSIN.\n\n"
                    ."Silakan pilih layanan:\n\n"
                    ."1. 🚐 Cari Jadwal\n"
                    ."2. 📖 Cara Booking\n"
                    ."3. 📄 Kebijakan Pembatalan\n"
                    ."4. 💬 Hubungi Admin\n"
                    .'5. 🎟️ Refund Ticket',
            ]);
        }
        if ($conversation->state == 'main_menu' && $message == '1') {

            $conversation->update([
                'state' => 'ask_origin',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Silakan masukkan kota keberangkatan Anda.',
            ]);
        }
        if ($conversation->state == 'main_menu' && $message == '2') {

            return response()->json([
                'success' => true,
                'message' => "📖 Cara Booking Shuttle GASSIN\n\n".
                    "1️⃣ Pilih menu Jadwal Shuttle.\n".
                    "2️⃣ Tentukan lokasi penjemputan dan tujuan.\n".
                    "3️⃣ Pilih jadwal keberangkatan.\n".
                    "4️⃣ Pilih kursi yang tersedia.\n".
                    "5️⃣ Lakukan pembayaran.\n".
                    "6️⃣ Setelah pembayaran berhasil, E-Ticket akan otomatis tersedia pada menu Riwayat Booking.\n\n".
                    'Ketik MENU untuk kembali ke menu utama.',
            ]);

        }
        if ($conversation->state == 'main_menu' && $message == '3') {

            return response()->json([
                'success' => true,
                'message' => "📄 Kebijakan Pembatalan GASSIN\n\n".
                    "• Booking dapat dibatalkan sebelum jadwal keberangkatan sesuai ketentuan operator.\n".
                    "• Pembayaran yang telah berhasil dilakukan tidak dapat dikembalikan (non-refundable).\n".
                    "• Jika perjalanan dibatalkan oleh pihak GASSIN, pengguna akan dihubungi oleh admin untuk proses penanganan lebih lanjut.\n\n".
                    "Jika membutuhkan bantuan, silakan pilih menu Hubungi Admin.\n\n".
                    'Ketik MENU untuk kembali ke menu utama.',
            ]);

        }
        if ($conversation->state == 'main_menu' && $message == '4') {

            $conversation->update([
                'state' => 'ask_admin_message',
            ]);

            return response()->json([
                'success' => true,
                'message' => "💬 Hubungi Admin\n\n".
                    "Silakan tuliskan kendala atau pertanyaan Anda.\n\n".
                    "Contoh:\n".
                    "• Saya salah memilih jadwal.\n".
                    "• Pembayaran saya belum masuk.\n".
                    "• Saya ingin menanyakan lokasi penjemputan.\n\n".
                    'Pesan Anda akan diteruskan langsung ke admin.',
            ]);
        }
        if ($conversation->state == 'main_menu' && $message == '5') {

            $conversation->update([
                'state' => 'ask_refund_invoice',
                'data' => [],
            ]);

            return response()->json([
                'success' => true,
                'message' => "🎟️ Pengajuan Refund Ticket\n\n".
                    "Silakan masukkan Kode Booking / Invoice Anda.\n\n".
                    "Contoh:\n".
                    'INV-202606260001',
            ]);
        }
        if (
            $conversation->state == 'ask_admin_message' &&
            in_array($message, ['menu', 'selesai', 'exit'])
        ) {

            $conversation->update([
                'state' => 'main_menu',
                'data' => [],
            ]);

            return response()->json([
                'success' => true,
                'message' => "Anda telah keluar dari percakapan dengan admin.\n\nKetik MENU untuk melihat layanan.",
            ]);
        }
        if ($conversation->state == 'ask_admin_message') {

            $user = auth('api')->user();

            $telegramMessage =
                "🚨 *Pesan Baru dari Chatbot GASSIN*\n\n".
                "👤 *Nama* : {$user->name}\n".
                "📧 *Email* : {$user->email}\n";

            if (! empty($user->phone)) {
                $telegramMessage .= "📱 *No. HP* : {$user->phone}\n";
            }

            $telegramMessage .=
                "🆔 *User ID* : {$user->id}\n\n".
                "━━━━━━━━━━━━━━\n".
                "💬 *Pesan*\n\n".
                "{$request->message}\n\n".
                "━━━━━━━━━━━━━━\n".
                '🕒 '.now()->format('d-m-Y H:i');

            $response = $this->telegramService->send($telegramMessage);

            ChatMessage::create([
                'user_id' => $user->id,
                'sender' => 'customer',
                'message' => $request->message,
                'telegram_message_id' => $response['result']['message_id'],
            ]);
            $conversation->update([
                'state' => 'chat_with_admin',
                'data' => [],
            ]);

            return response()->json([
                'success' => true,
                'message' => "✅ Pesan berhasil dikirim ke admin.\n\n".
                    "Terima kasih telah menghubungi GASSIN.\n".
                    "Admin akan menindaklanjuti pesan Anda sesegera mungkin.\n\n".
                    'Ketik *MENU* untuk kembali ke menu utama.',
            ]);
        }
        if ($conversation->state == 'chat_with_admin') {

            if (in_array($message, ['menu', 'selesai', 'exit'])) {

                $conversation->update([
                    'state' => 'main_menu',
                    'data' => [],
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Percakapan dengan admin telah diakhiri. Ketik MENU untuk melihat layanan.',
                ]);
            }

            $user = auth('api')->user();

            $telegramMessage =
                "💬 *Balasan Customer*\n\n".
                "👤 {$user->name}\n".
                "🆔 User ID : {$user->id}\n\n".
                $request->message;

            $response = $this->telegramService->send($telegramMessage);

            ChatMessage::create([
                'user_id' => $user->id,
                'sender' => 'customer',
                'message' => $request->message,
                'telegram_message_id' => $response['result']['message_id'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim ke admin.',
            ]);
        }
        if ($conversation->state == 'ask_origin') {

            $conversation->update([
                'state' => 'ask_destination',
                'data' => [
                    'origin' => $request->message,
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sekarang masukkan kota tujuan Anda.',
            ]);
        }
        if ($conversation->state == 'ask_destination') {

            $origin = $conversation->data['origin'];
            $destination = $request->message;

            $schedules = $this->searchNearestSchedule($origin, $destination);

            if ($schedules->isEmpty()) {

                $conversation->update([
                    'state' => 'main_menu',
                    'data' => [],
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Maaf, tidak ada jadwal yang tersedia dalam 2 jam ke depan.',
                ]);
            }

            $text = "🚐 Jadwal ditemukan\n\n";

            foreach ($schedules as $schedule) {

                $text .= "━━━━━━━━━━━━━━━━━━\n";

                $text .= "📍 Rute\n";
                $text .= $schedule['route']."\n\n";

                $text .= "🕒 Berangkat\n";
                $text .= $schedule['departure']."\n\n";

                $text .= "🕔 Tiba\n";
                $text .= $schedule['arrival']."\n\n";

                $text .= "💰 Harga\n";
                $text .= 'Rp '.$schedule['price']."\n\n";

                $text .= "💺 Kursi tersedia\n";
                $text .= $schedule['available_seats']." kursi\n\n";

                $text .= "🚌 Kendaraan\n";
                $text .= $schedule['vehicle']."\n";
                $text .= $schedule['plate']."\n\n";

                $text .= "👨‍✈️ Driver\n";
                $text .= ($schedule['driver'] ?? '-')."\n\n";
            }
            $text .= "━━━━━━━━━━━━━━━━━━\n";
            $text .= 'Ketik MENU untuk kembali.';

            $conversation->update([
                'state' => 'main_menu',
                'data' => [],
            ]);

            return response()->json([
                'success' => true,
                'message' => $text,
            ]);
        }
        if ($conversation->state == 'ask_refund_invoice') {

            $orderId = strtoupper(trim($request->message));

            // Validasi format Order ID
            if (! preg_match('/^INV-[A-Z0-9-]+$/', $orderId)) {
                return response()->json([
                    'success' => false,
                    'message' => "❌ Format Order ID tidak valid.\n\n".
                        "Contoh:\n".
                        'INV-ABC123-XYZ789',
                ]);
            }

            $booking = Booking::with('schedule')
                ->where('order_id', $orderId)
                ->where('user_id', auth('api')->id())
                ->first();
            $exitmessage = "\nKetik 'MENU' untuk keluar dari menu refund";
            if (! $booking) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Order ID tidak ditemukan atau bukan milik Anda.'.$exitmessage ,
                ]);
            }

            if ($booking->payment_status !== 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Booking belum berhasil dibayar sehingga tidak dapat direfund.'.$exitmessage ,
                ]);
            }

            if ($booking->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Booking ini sudah direfund sebelumnya.'.$exitmessage ,
                ]);
            }

            if (Carbon::parse($booking->schedule->departure_time)->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Refund tidak dapat diajukan karena perjalanan telah dimulai.'.$exitmessage ,
                ]);
            }

            if (
                $booking->paid_at &&
                Carbon::parse($booking->paid_at)->addDay()->isPast()
            ) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Refund hanya dapat diajukan maksimal 1 x 24 jam setelah pembayaran.'.$exitmessage ,
                ]);
            }

            $conversation->update([
                'state' => 'ask_refund_account_name',
                'data' => [
                    'booking_id' => $booking->id,
                    'invoice' => $booking->order_id,
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => "✅ Order ID ditemukan.\n\n".
                    "Masukkan nama pemilik rekening / E-Wallet.\n\n".
                    "Contoh:\n".
                    'Bagas Nurdiansyah',
            ]);
        }
        if ($conversation->state == 'ask_refund_account_name') {

            $name = trim($request->message);

            if (strlen($name) < 3 || is_numeric($name)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nama pemilik tidak valid.',
                ]);
            }

            $data = $conversation->data;
            $data['account_name'] = $name;

            $conversation->update([
                'state' => 'ask_refund_account_type',
                'data' => $data,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Masukkan Bank atau E-Wallet.\n\n".
                    "Contoh:\n".
                    "BCA\n".
                    "BRI\n".
                    "BNI\n".
                    "Mandiri\n".
                    "DANA\n".
                    "GoPay\n".
                    "OVO\n".
                    'ShopeePay',
            ]);
        }
        if ($conversation->state == 'ask_refund_account_type') {

            $type = trim($request->message);

            if (strlen($type) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bank / E-Wallet tidak valid.',
                ]);
            }

            $data = $conversation->data;
            $data['account_type'] = strtoupper($type);

            $conversation->update([
                'state' => 'ask_refund_account_number',
                'data' => $data,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Masukkan nomor rekening / nomor E-Wallet tujuan refund.',
            ]);
        }
        if ($conversation->state == 'ask_refund_account_number') {

            $number = preg_replace('/\s+/', '', $request->message);

            if (! preg_match('/^[0-9]{8,20}$/', $number)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor rekening / E-Wallet tidak valid.',
                ]);
            }

            $data = $conversation->data;
            $data['account_number'] = $number;

            $conversation->update([
                'state' => 'ask_refund_reason',
                'data' => $data,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Tuliskan alasan pengajuan refund.\n\n".
                    "Contoh:\n".
                    "• Salah memilih jadwal\n".
                    "• Tidak bisa berangkat\n".
                    "• Kendaraan dibatalkan\n".
                    '• Alasan lainnya',
            ]);
        }
        if ($conversation->state == 'ask_refund_reason') {

            $reason = trim($request->message);

            if (strlen($reason) < 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mohon tuliskan alasan refund yang lebih lengkap.',
                ]);
            }

            $user = auth('api')->user();

            $data = $conversation->data;

            $telegramMessage =
                "🎟️ *Pengajuan Refund Ticket*\n\n".
                "👤 *Nama User* : {$user->name}\n".
                "📧 *Email* : {$user->email}\n";

            if (! empty($user->phone)) {
                $telegramMessage .= "📱 *No. HP* : {$user->phone}\n";
            }

            $telegramMessage .=
                "🆔 *User ID* : {$user->id}\n\n".
                "━━━━━━━━━━━━━━\n".
                "🧾 *Invoice*\n".
                "{$data['invoice']}\n\n".
                "👤 *Nama Pemilik*\n".
                "{$data['account_name']}\n\n".
                "🏦 *Bank / E-Wallet*\n".
                "{$data['account_type']}\n\n".
                "💳 *Nomor Rekening / E-Wallet*\n".
                "{$data['account_number']}\n\n".
                "📝 *Alasan Refund*\n".
                "{$reason}\n\n".
                "━━━━━━━━━━━━━━\n".
                '🕒 '.now()->format('d-m-Y H:i');

            $response = $this->telegramService->send($telegramMessage);

            ChatMessage::create([
                'user_id' => $user->id,
                'sender' => 'customer',
                'message' => "Refund Ticket\n".
                    "Invoice: {$data['invoice']}\n".
                    "Nama: {$data['account_name']}\n".
                    "Bank/E-Wallet: {$data['account_type']}\n".
                    "Nomor: {$data['account_number']}\n".
                    "Alasan: {$reason}",
                'telegram_message_id' => $response['result']['message_id'],
            ]);

            $conversation->update([
                'state' => 'main_menu',
                'data' => [],
            ]);

            return response()->json([
                'success' => true,
                'message' => "✅ Pengajuan refund berhasil dikirim.\n\n".
                    "Admin akan melakukan verifikasi dan menghubungi Anda apabila diperlukan.\n\n".
                    'Ketik MENU untuk kembali ke menu utama.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ketik "menu" untuk melihat daftar layanan.',
        ]);
    }

    public function history()
    {
        return response()->json([
            'success' => true,
            'data' => ChatMessage::where(
                'user_id',
                auth('api')->id()
            )
                ->orderBy('created_at')
                ->get(),
        ]);
    }
}
