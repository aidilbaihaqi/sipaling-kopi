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
                    <p id="todaySales" class="text-3xl font-bold text-amber-900">Loading...</p>
                </div>
                <div class="text-4xl">💰</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Pesanan Hari Ini</p>
                    <p id="todayOrders" class="text-3xl font-bold text-amber-900">Loading...</p>
                </div>
                <div class="text-4xl">📦</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Menu Terlaris</p>
                    <p id="topMenuName" class="text-xl font-bold text-amber-900">Loading...</p>
                    <p id="topMenuSold" class="text-sm text-gray-500"></p>
                </div>
                <div class="text-4xl">⭐</div>
            </div>
        </div>
    </div>

    <!-- Grafik Penjualan -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Grafik Penjualan</h3>
            <div class="flex gap-2 items-center">
                <select id="chartDays" class="border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="7">7 Hari Terakhir</option>
                    <option value="14">14 Hari Terakhir</option>
                    <option value="30">30 Hari Terakhir</option>
                </select>
                <button onclick="loadDashboard()" class="bg-amber-900 text-white px-4 py-2 rounded hover:bg-amber-800 text-sm">Filter</button>
            </div>
        </div>
        <canvas id="salesChart" height="80"></canvas>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Pesanan Terbaru -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pesanan Terbaru</h3>
            <div id="recentOrders" class="space-y-3">
                <p class="text-gray-500 text-center py-4">Loading...</p>
            </div>
        </div>

        <!-- Stok Kritis -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">⚠️ Stok Kritis</h3>
            <div id="criticalStock" class="space-y-3">
                <p class="text-gray-500 text-center py-4">Loading...</p>
            </div>
        </div>
    </div>
</div>

<script>
let salesChart = null;

document.addEventListener('DOMContentLoaded', loadDashboard);

async function loadDashboard() {
    try {
        const chartDays = document.getElementById('chartDays').value;
        const response = await dashboardApi.getData(`chart_days=${chartDays}`);
        const data = response.data;

        // Update statistics
        document.getElementById('todaySales').textContent = 'Rp ' + Number(data.today_sales).toLocaleString('id-ID');
        document.getElementById('todayOrders').textContent = data.today_orders;
        
        if (data.top_menu) {
            document.getElementById('topMenuName').textContent = data.top_menu.name;
            document.getElementById('topMenuSold').textContent = data.top_menu.total_sold + ' terjual';
        } else {
            document.getElementById('topMenuName').textContent = 'Belum ada';
            document.getElementById('topMenuSold').textContent = '';
        }

        // Render recent orders
        renderRecentOrders(data.recent_orders);

        // Render critical stock
        renderCriticalStock(data.critical_stock);

        // Render chart
        renderSalesChart(data.sales_chart);
    } catch (error) {
        console.error('Failed to load dashboard:', error);
    }
}

function renderRecentOrders(orders) {
    const container = document.getElementById('recentOrders');
    if (!orders || orders.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-center py-4">Belum ada pesanan</p>';
        return;
    }
    const statusColors = {
        completed: 'bg-green-100 text-green-800',
        processing: 'bg-blue-100 text-blue-800',
        ready: 'bg-purple-100 text-purple-800',
        pending: 'bg-yellow-100 text-yellow-800',
        cancelled: 'bg-red-100 text-red-800'
    };
    container.innerHTML = orders.map(order => `
        <div class="flex justify-between items-center border-b pb-2">
            <div>
                <p class="font-semibold">${order.order_number}</p>
                <p class="text-sm text-gray-500">${new Date(order.created_at).toLocaleString('id-ID')}</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-amber-900">Rp ${Number(order.total_amount).toLocaleString('id-ID')}</p>
                <span class="text-xs px-2 py-1 rounded ${statusColors[order.status] || 'bg-gray-100 text-gray-800'}">${order.status}</span>
            </div>
        </div>
    `).join('');
}

function renderCriticalStock(menus) {
    const container = document.getElementById('criticalStock');
    if (!menus || menus.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-center py-4">Semua stok aman</p>';
        return;
    }
    container.innerHTML = menus.map(menu => `
        <div class="flex justify-between items-center border-b pb-2">
            <div>
                <p class="font-semibold">${menu.name}</p>
                <p class="text-sm text-gray-500">${menu.category?.name || '-'}</p>
            </div>
            <span class="px-3 py-1 bg-red-100 text-red-800 rounded font-bold">${menu.stock} tersisa</span>
        </div>
    `).join('');
}

function renderSalesChart(salesData) {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    if (salesChart) {
        salesChart.destroy();
    }

    salesChart = new Chart(ctx, {
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
</script>
@endsection
