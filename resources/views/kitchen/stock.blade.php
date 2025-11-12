@extends('layouts.kitchen')

@section('content')

{{-- Judul Halaman --}}
<div class="mb-8 border-b border-gray-300 pb-4">
    <h2 class="text-3xl font-extrabold text-gray-800 flex items-center gap-3">
        <svg class="w-8 h-8 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2m-7 6h7.75"></path></svg>
        Manajemen Stok Menu
    </h2>
    <p class="text-gray-600 mt-1">Perbarui ketersediaan menu dan stok secara real-time untuk menghindari kelebihan pesanan.</p>
</div>

{{-- Notifikasi sukses/error --}}
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-md">
    ✅ **Sukses!** {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-md">
    ❌ {{ session('error') }}
</div>
@endif

{{-- Form pencarian dan filter --}}
<div class="bg-white p-4 rounded-xl shadow-xl mb-6">
    <form method="GET" action="{{ route('kitchen.stock') }}" class="flex flex-col md:flex-row gap-4 items-end">
        <input
            type="text"
            name="search"
            placeholder="Cari menu..."
            value="{{ request('search') }}"
            class="border border-gray-300 rounded-lg px-3 py-2 flex-grow focus:ring-amber-700 focus:border-amber-700"
        />

        <select name="availability" class="border border-gray-300 rounded-lg px-3 py-2">
            <option value="">Semua Status</option>
            <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Tersedia</option>
            <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Habis</option>
        </select>

        <button type="submit" class="bg-amber-900 hover:bg-amber-800 text-white font-medium px-4 py-2 rounded-lg shadow-md transition w-full md:w-auto">
            🔍 Cari
        </button>
    </form>
</div>

{{-- Tabel Stok Menu --}}
<div class="bg-white shadow-xl rounded-xl overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr class="bg-amber-900"> 
                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Nama Menu</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Harga</th>
                <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Sisa Stok</th>
                <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Status Ketersediaan</th>
                <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Update Stok</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($menus as $menu)
            <tr class="{{ $menu->is_available ? 'hover:bg-gray-50' : 'bg-red-50 hover:bg-red-100' }}">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $menu->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">{{ $menu->stock }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                    @if($menu->is_available)
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tersedia</span>
                    @else
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Habis</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                    <form action="{{ route('kitchen.toggleMenu', $menu->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 rounded-lg font-medium shadow-md transition
                            {{ $menu->is_available ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}">
                            {{ $menu->is_available ? 'Tandai Habis' : 'Tandai Tersedia' }}
                        </button>
                    </form>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                    <form action="{{ route('kitchen.updateStock', $menu->id) }}" method="POST" class="flex items-center justify-center gap-2">
                        @csrf
                        <input
                            type="number"
                            name="stock"
                            min="0"
                            value="{{ $menu->stock }}"
                            class="border border-gray-300 rounded px-2 py-1 w-20 text-center"
                            required
                        />
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                            Update
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-600 italic">Belum ada menu yang tercatat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
