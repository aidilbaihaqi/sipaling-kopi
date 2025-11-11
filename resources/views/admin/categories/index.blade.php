@extends('layouts.app')

@section('title', 'Manajemen Kategori')
@section('page-title', 'Manajemen Kategori')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Form Tambah/Edit -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Tambah Kategori</h3>
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nama Kategori</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900"></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-amber-900 text-white rounded-lg hover:bg-amber-800">
                Simpan
            </button>
        </form>
    </div>

    <!-- Tabel Kategori -->
    <div class="md:col-span-2 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Daftar Kategori</h3>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-amber-900 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Jumlah Menu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($categories as $category)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $category->name }}</td>
                    <td class="px-6 py-4">{{ Str::limit($category->description, 40) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded text-sm">
                            {{ $category->menus_count }} menu
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus kategori ini?')" 
                                class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
