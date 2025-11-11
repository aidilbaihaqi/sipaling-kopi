@extends('layouts.app')

@section('title', 'Tambah Menu')
@section('page-title', 'Tambah Menu')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('menus.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Kategori</label>
                <select name="category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nama Menu</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Harga</label>
                    <input type="number" name="price" required min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Stok</label>
                    <input type="number" name="stock" required min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
                </div>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_available" value="1" checked class="mr-2">
                    <span class="text-gray-700">Tersedia</span>
                </label>
            </div>

            <div class="flex space-x-3">
                <button type="submit" class="px-6 py-2 bg-amber-900 text-white rounded-lg hover:bg-amber-800">
                    Simpan
                </button>
                <a href="{{ route('menus.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
