@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Kartu Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Penjualan Hari Ini</p>
                    <p class="text-3xl font-bold text-amber-900">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
                </div>
                <div class="text-4xl">💰</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Pesanan Hari Ini</p>
                    <p class="text-3xl font-bold text-amber-900">{{ $todayOrders }}</p>
                </div>
                <div class="text-4xl">📦</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Menu Terlaris</p>
                    <p class="text-xl font-bold text-amber-900">{{ $topMenu->name ?? 'Belum ada' }}</p>
                    @if($topMenu)
                        <p class="text-sm text-gray-500">{{ $topMenu->total_sold }} terjual</p>
                    @endif
                </div>
                <div class="text-4xl">⭐</div>
            </div>
        </div>
    </div>

    <!-- Grafik Penjualan -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Penjualan 7 Hari Terakhir</h3>
        <canvas id="salesChart" height="80"></canvas>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Pesanan Terbaru -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pesanan Terbaru</h3>
            <div class="space-y-3">
                @forelse($recentOrders as $order)
                    <div class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-semibold">{{ $order->order_number }}</p>
                            <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-amber-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            <span class="text-xs px-2 py-1 rounded 
                                @if($order->status == 'completed') bg-green-100 text-green-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Belum ada pesanan</p>
                @endforelse
            </div>
        </div>

        <!-- Stok Kritis -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">⚠️ Stok Kritis</h3>
            <div class="space-y-3">
                @forelse($criticalStock as $menu)
                    <div class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-semibold">{{ $menu->name }}</p>
                            <p class="text-sm text-gray-500">{{ $menu->category->name }}</p>
                        </div>
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded font-bold">
                            {{ $menu->stock }} tersisa
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Semua stok aman</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const salesData = @json($salesChart);
    if (salesData && salesData.length > 0) {
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: salesData.map(item => new Date(item.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })),
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: salesData.map(item => item.total),
                    borderColor: '#78350f',
                    backgroundColor: 'rgba(120, 53, 15, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true, position: 'top' } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: value => 'Rp ' + value.toLocaleString('id-ID') }
                    }
                }
            }
        });
    }
});
</script>
@endsection
