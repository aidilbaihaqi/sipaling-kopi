@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')
<div class="space-y-6">
    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" class="flex items-end space-x-4">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate }}" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
            </div>
            <button type="submit" class="px-6 py-2 bg-amber-900 text-white rounded-lg hover:bg-amber-800">
                Filter
            </button>
            <button type="button" onclick="window.print()" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                📄 Cetak/PDF
            </button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Total Pendapatan</p>
            <p class="text-3xl font-bold text-amber-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Total Transaksi</p>
            <p class="text-3xl font-bold text-amber-900">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Rata-rata per Transaksi</p>
            <p class="text-3xl font-bold text-amber-900">
                Rp {{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders, 0, ',', '.') : 0 }}
            </p>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Metode Pembayaran</h3>
        <div class="grid grid-cols-3 gap-4">
            @foreach($paymentMethods as $method => $data)
            <div class="border rounded-lg p-4">
                <p class="text-gray-600 text-sm">{{ ucfirst($method) }}</p>
                <p class="text-xl font-bold">{{ $data['count'] }} transaksi</p>
                <p class="text-sm text-gray-500">Rp {{ number_format($data['total'], 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Detail Transaksi -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Detail Transaksi</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-amber-900 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">No. Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Item</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Pembayaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $order->order_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4">
                        @foreach($order->items as $item)
                            <div class="text-sm">{{ $item->quantity }}x {{ $item->menu->name }}</div>
                        @endforeach
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">
                            {{ ucfirst($order->payment_method) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap font-bold">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada transaksi dalam periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
@media print {
    aside, header button, .no-print { display: none !important; }
    body { background: white; }
}
</style>
@endsection
