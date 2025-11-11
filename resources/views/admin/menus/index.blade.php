@extends('layouts.app')

@section('title', 'Manajemen Menu')
@section('page-title', 'Manajemen Menu')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center">
        <div>
            <input type="text" id="searchMenu" placeholder="Cari menu..." 
                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
        </div>
        <a href="{{ route('menus.create') }}" class="px-4 py-2 bg-amber-900 text-white rounded-lg hover:bg-amber-800">
            + Tambah Menu
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-amber-900 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Stok</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="menuTable">
                @foreach($menus as $menu)
                <tr class="menu-row">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $menu->name }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($menu->description, 30) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs bg-amber-100 text-amber-800 rounded">
                            {{ $menu->category->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-semibold {{ $menu->stock < 30 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $menu->stock }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded {{ $menu->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $menu->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('menus.edit', $menu) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                        <form action="{{ route('menus.destroy', $menu) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus menu ini?')" 
                                class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('searchMenu').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('.menu-row');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
</script>
@endsection
