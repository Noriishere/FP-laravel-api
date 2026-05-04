@extends('layouts.app')

@section('content')
    <div class="bg-white p-5 rounded-xl shadow">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Daftar Jadwal</h2>

            <a href="{{ route('schedules.create') }}" class="bg-primary text-white px-4 py-2 rounded text-sm">
                + Tambah Jadwal
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-2">Route</th>
                        <th class="pb-2">Driver</th>
                        <th class="pb-2">Kendaraan</th>
                        <th class="pb-2">Berangkat</th>
                        <th class="pb-2">Harga</th>
                        <th class="pb-2">Status</th>
                        <th class="pb-2">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($schedules as $schedule)
                        <tr class="border-b hover:bg-gray-50">

                            {{-- ROUTE --}}
                            <td class="py-3">
                                {{ $schedule->route->origin_name ?? '-' }}
                                →
                                {{ $schedule->route->destination_name ?? '-' }}
                            </td>

                            {{-- DRIVER --}}
                            <td class="py-3">
                                {{ $schedule->driver->user->name ?? '-' }}
                            </td>

                            {{-- VEHICLE --}}
                            <td class="py-3">
                                {{ $schedule->vehicle->name ?? '-' }}
                            </td>

                            {{-- TIME --}}
                            <td class="py-3">
                                {{ \Carbon\Carbon::parse($schedule->departure_time)->format('d M Y H:i') }}
                            </td>

                            {{-- PRICE --}}
                            <td class="py-3">
                                Rp {{ number_format($schedule->price, 0, ',', '.') }}
                            </td>

                            {{-- STATUS --}}
                            <td class="py-3">
                                @if ($schedule->status == 'on-going')
                                    <span class="text-green-600 text-xs">● Berjalan</span>
                                @elseif($schedule->status == 'scheduled')
                                    <span class="text-yellow-600 text-xs">● Dijadwalkan</span>
                                @else
                                    <span class="text-gray-500 text-xs">● Selesai</span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="py-3">
                                <div class="flex gap-2 items-center">
                                    <a href="{{ route('schedules.show', $schedule->id) }}" class="text-blue-600 text-xs">Detail</a>
                                    <a href="#" class="text-yellow-600 text-xs">Edit</a>

                                    <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin mau hapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 text-xs">Hapus</button>
                                    </form>
                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-400">
                                Tidak ada jadwal
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
@endsection
