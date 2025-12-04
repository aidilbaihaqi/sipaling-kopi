@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')
<div class="space-y-6">
    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-end space-x-4">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Tanggal Mulai</label>
                <input type="date" id="startDate" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Tanggal Akhir</label>
                <input type="date" id="endDate" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
            </div>
            <button onclick="loadReport()" class="px-6 py-2 bg-amber-900 text-white rounded-lg hover:bg-amber-800">Filter</button>
            <a id="exportLink" href="#" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 inline-flex items-center">📊 Download Excel</a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Total Pendapatan</p>
            <p id="totalRevenue" class="text-3xl font-bold text-amber-900">Loading...</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Total Transaksi</p>
            <p id="totalOrders" class="text-3xl font-bold text-amber-900">Loading...</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Rata-rata per Transaksi</p>
            <p id="avgTransaction" class="text-3xl font-bold text-amber-900">Loading...</p>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Metode Pembayaran</h3>
        <div id="paymentMethods" class="grid grid-cols-3 gap-4">
            <div class="text-center text-gray-500">Loading...</div>
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
            <tbody id="ordersTable" class="bg-white divide-y divide-gray-200">
                <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Set default dates
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    document.getElementById('startDate').value = firstDay.toISOString().split('T')[0];
    document.getElementById('endDate').value = today.toISOString().split('T')[0];
    loadReport();
});

async function loadReport() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    // Update export link
    document.getElementById('exportLink').href = `/api/reports/export?start_date=${startDate}&end_date=${endDate}`;
    
    try {
        const response = await reportApi.getData(`start_date=${startDate}&end_date=${endDate}`);
        const data = response.data;
        
        // Update summary
        document.getElementById('totalRevenue').textContent = 'Rp ' + Number(data.total_revenue).toLocaleString('id-ID');
        document.getElementById('totalOrders').textContent = data.total_orders;
        document.getElementById('avgTransaction').textContent = 'Rp ' + Number(data.average_per_transaction).toLocaleString('id-ID');
        
        // Update payment methods
        renderPaymentMethods(data.payment_methods);
        
        // Update orders table
        renderOrdersTable(data.orders);
    } catch (error) {
        console.error('Failed to load report:', error);
    }
}

function renderPaymentMethods(methods) {
    const container = document.getElementById('paymentMethods');
    if (!methods || Object.keys(methods).length === 0) {
        container.innerHTML = '<div class="col-span-3 text-center text-gray-500">Tidak ada data</div>';
        return;
    }
    
    container.innerHTML = Object.entries(methods).map(([method, data]) => `
        <div class="border rounded-lg p-4">
            <p class="text-gray-600 text-sm">${method.charAt(0).toUpperCase() + method.slice(1)}</p>
            <p class="text-xl font-bold">${data.count} transaksi</p>
            <p class="text-sm text-gray-500">Rp ${Number(data.total).toLocaleString('id-ID')}</p>
        </div>
    `).join('');
}

function renderOrdersTable(orders) {
    const tbody = document.getElementById('ordersTable');
    
    if (!orders || orders.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada transaksi dalam periode ini</td></tr>';
        return;
    }
    
    tbody.innerHTML = orders.map(order => `
        <tr>
            <td class="px-6 py-4 whitespace-nowrap font-medium">${order.order_number}</td>
            <td class="px-6 py-4 whitespace-nowrap">${new Date(order.created_at).toLocaleString('id-ID')}</td>
            <td class="px-6 py-4">
                ${order.details ? order.details.map(item => `<div class="text-sm">${item.quantity}x ${item.menu?.name || 'Menu'}</div>`).join('') : '-'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">${order.payment_method}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap font-bold">Rp ${Number(order.total_amount).toLocaleString('id-ID')}</td>
        </tr>
    `).join('');
}
</script>
@endsection
