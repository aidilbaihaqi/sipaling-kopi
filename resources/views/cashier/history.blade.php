@extends('layouts.cashier')

@section('content')
<div class="h-full flex flex-col bg-gray-50">
    <div class="px-8 py-6 bg-white border-b border-gray-200 shadow-sm flex justify-between items-center">
        <div>
            <h2 class="font-bold text-gray-800 text-2xl">Riwayat Transaksi</h2>
            <p class="text-sm text-gray-500 mt-1">Daftar transaksi yang dilakukan hari ini.</p>
        </div>
        <a href="{{ route('cashier.index') }}" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-5 py-2.5 rounded-lg font-bold text-sm transition flex items-center gap-2">
            <span>&laquo;</span> Kembali ke Kasir
        </a>
    </div>

    <div class="flex-1 overflow-auto p-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">No. Order</th>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4 text-right">Total Bayar</th>
                        <th class="px-6 py-4 text-center">Metode</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="historyTable" class="divide-y divide-gray-100">
                    <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', loadHistory);

async function loadHistory() {
    try {
        const response = await orderApi.getHistory();
        renderHistory(response.data);
    } catch (error) {
        console.error('Failed to load history:', error);
    }
}

function renderHistory(orders) {
    const tbody = document.getElementById('historyTable');
    
    if (!orders || orders.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                    <div class="flex flex-col items-center justify-center">
                        <span class="text-4xl mb-3">📂</span>
                        <p>Belum ada transaksi hari ini.</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }

    const statusColors = {
        pending: 'bg-yellow-100 text-yellow-700',
        processing: 'bg-blue-100 text-blue-700',
        ready: 'bg-purple-100 text-purple-700',
        completed: 'bg-green-100 text-green-700',
        cancelled: 'bg-red-100 text-red-700'
    };

    tbody.innerHTML = orders.map(order => `
        <tr class="hover:bg-amber-50 transition">
            <td class="px-6 py-4 font-bold text-gray-800">#${order.order_number || order.id}</td>
            <td class="px-6 py-4 text-sm text-gray-600">${new Date(order.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</td>
            <td class="px-6 py-4 font-medium text-gray-900">${order.customer_name}</td>
            <td class="px-6 py-4 text-right font-bold text-amber-600">Rp ${Number(order.total_amount).toLocaleString('id-ID')}</td>
            <td class="px-6 py-4 text-center">
                <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-600 font-bold border border-gray-200">${order.payment_method.toUpperCase()}</span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="px-3 py-1 text-xs rounded-full font-bold ${statusColors[order.status] || 'bg-gray-100 text-gray-600'}">${order.status}</span>
            </td>
            <td class="px-6 py-4 text-center">
                <a href="/cashier/print/${order.id}" target="_blank" class="text-amber-600 hover:text-amber-800 font-bold text-sm hover:underline flex justify-center items-center gap-1">🖨️ Cetak Struk</a>
            </td>
        </tr>
    `).join('');
}
</script>
@endsection
