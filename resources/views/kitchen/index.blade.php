@extends('layouts.kitchen')

@section('content')

{{-- Judul dan Deskripsi bertema Dapur --}}
<div class="mb-8 border-b border-gray-300 pb-4">
    <h2 class="text-3xl font-extrabold text-gray-800 flex items-center gap-3">
        <svg class="w-8 h-8 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2-3-.895-3-2 1.343-2 3-2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2z"></path></svg>
        Antrian Pesanan Dapur
    </h2>
    <p class="text-gray-600 mt-1">Pantau dan kelola pesanan yang masuk. Pesanan akan disegarkan otomatis setiap 10 detik.</p>
</div>


@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-md">
    ✅ **Sukses!** {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"> {{-- Tampilan Grid --}}
@forelse ($orders as $order)
{{-- Kartu Pesanan dengan Shadow dan border tebal untuk status --}}
<div class="bg-white shadow-xl rounded-xl p-6 border-l-4 
    @if($order->status == 'pending') border-red-500
    @elseif($order->status == 'processing') border-amber-500
    @elseif($order->status == 'ready') border-green-500
    @else border-gray-500
    @endif
    transition hover:shadow-2xl">

    <div class="flex justify-between items-start mb-4 border-b pb-3">
        <h3 class="font-bold text-xl text-amber-900">
            #{{ $order->order_number }}
        </h3>
        <span class="px-3 py-1 text-xs font-semibold rounded-full uppercase
            @if($order->status == 'pending') bg-red-100 text-red-700 
            @elseif($order->status == 'processing') bg-amber-100 text-amber-700
            @elseif($order->status == 'ready') bg-green-100 text-green-700
            @elseif($order->status == 'cancelled') bg-gray-100 text-gray-500
            @endif">
            {{ strtoupper($order->status) }}
        </span>
    </div>

    {{-- Detail Pesanan --}}
    <div class="text-sm text-gray-700 space-y-1 mb-4">
        <p><strong>Tipe:</strong> <span class="font-semibold">{{ ucfirst($order->type) }}</span></p>
        @if($order->type === 'dine-in')
        <p><strong>Meja:</strong> {{ $order->table_no ?? '-' }}</p>
        @endif
        <p><strong>Bayar:</strong> {{ ucfirst($order->payment_method) }}</p>
        <p><strong>Total:</strong> <span class="font-bold text-lg text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
    </div>

    @if(isset($order->items) && $order->items->count() > 0)
    <h4 class="font-bold text-base text-gray-800 mb-2 border-t pt-2">Daftar Menu:</h4>
    <ul class="space-y-1 text-gray-700 list-disc ml-5 mb-4">
        @foreach ($order->items as $item)
        <li class="flex justify-between items-center text-sm">
            <span>{{ $item->menu->name ?? 'Menu tidak ditemukan' }}</span>
            <span class="font-bold text-amber-700">x{{ $item->quantity }}</span>
        </li>
        @endforeach
    </ul>
    @endif

    {{-- Tombol aksi cepat --}}
    <div class="mt-4 pt-4 border-t flex flex-col gap-3">
        
        {{-- Tombol Mulai Masak --}}
        <form action="{{ route('kitchen.updateStatus', $order->id) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="processing">
            <button type="submit"
                class="w-full bg-amber-700 hover:bg-amber-800 text-white font-bold px-4 py-2 rounded-lg transition shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                @if($order->status !== 'pending') disabled @endif>
                🍳 Mulai Masak
            </button>
        </form>

        {{-- Tombol Selesai --}}
        <form action="{{ route('kitchen.updateStatus', $order->id) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="ready">
            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold px-4 py-2 rounded-lg transition shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                @if($order->status !== 'processing') disabled @endif>
                ✅ Siap Diambil
            </button>
        </form>
    </div>

    {{-- KEMBALIKAN: Form update status lengkap (Dropdown) --}}
    <form action="{{ route('kitchen.updateStatus', $order->id) }}" method="POST" class="mt-6 border-t pt-4">
        @csrf

        <label for="status_{{ $order->id }}" class="text-sm font-medium text-gray-700 block mb-2">Ubah Status Detail:</label>
        <div class="flex gap-2 items-center">
            <select name="status" id="status_{{ $order->id }}"
                class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500 flex-grow">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <button type="submit"
                class="bg-amber-900 hover:bg-amber-800 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition whitespace-nowrap">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356-2A8.001 8.001 0 004.582 19.918L4 20.5v-5h.582m15.356-2A8.001 8.001 0 004.582 4.082L4 3.5v5"></path></svg>
                Update
            </button>
        </div>
    </form>

</div>
@empty
<div class="col-span-full bg-white p-6 rounded-xl shadow-md border-t-4 border-gray-300">
    <p class="text-gray-600 italic text-center">🎉 Belum ada pesanan baru untuk dapur saat ini. Waktunya istirahat.</p>
</div>
@endforelse
</div> {{-- End Grid --}}

<script>
    setTimeout(() => {
        location.reload();
    }, 10000);
</script>

@endsection