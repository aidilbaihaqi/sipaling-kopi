@extends('layouts.app')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nama</label>
                <input type="text" name="name" value="{{ $user->name }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" name="email" value="{{ $user->email }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Password Baru (kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Role</label>
                <select name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="cashier" {{ $user->role == 'cashier' ? 'selected' : '' }}>Cashier</option>
                    <option value="kitchen" {{ $user->role == 'kitchen' ? 'selected' : '' }}>Kitchen</option>
                </select>
            </div>

            <div class="flex space-x-3">
                <button type="submit" class="px-6 py-2 bg-amber-900 text-white rounded-lg hover:bg-amber-800">
                    Update
                </button>
                <a href="{{ route('users.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
