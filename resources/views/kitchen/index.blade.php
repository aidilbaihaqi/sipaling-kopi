@extends('layouts.kitchen')

@section('content')
<div class="mb-8 border-b border-gray-300 pb-4">
    <h2 class="text-3xl font-extrabold text-gray-800 flex items-center gap-3">
        <svg class="w-8 h-8 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2-3-.895-3-2 1.343-2 3-2z"></path></svg>
        Antrian Pesanan Dapur
    </h2>
    <p class="text-gray-600 mt-1">Pantau dan kelola pesanan yang masuk. Pesanan akan disegarkan otomatis setiap 10 detik.</p>
</div>

<div id="alertSuccess" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-md hidden"></div>
<div id="alertError" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-md hidden"></div>

<div id="ordersContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="col-span-full text-center py-8 text-gray-500">Loading...</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', loadOrders);

// Auto refresh every 10 seconds
setInterval(loadOrders, 10000);

async function loadOrders() {
    try {
        const response = await orderApi.getKitchen();
        renderOrders(response.data);
    } catch (error) {
        console.error('Failed to load orders:', error);
    }
}

function renderOrders(orders) {
    const container = document.getElementById('ordersContainer');
    
    if (!orders || orders.length === 0) {
        container.innerHTML = `
            <div class="col-span-full bg-white p-6 rounded-xl shadow-md border-t-4 border-gray-300">
                <p class="text-gray-600 italic text-center">🎉 Belum ada pesanan baru untuk dapur saat ini.</p>
            </div>
        `;
        return;
    }

    const statusColors = {
        pending: { border: 'border-red-500', badge: 'bg-red-100 text-red-700' },
        processing: { border: 'border-amber-500', badge: 'bg-amber-100 text-amber-700' },
        ready: { border: 'border-green-500', badge: 'bg-green-100 text-green-700' },
        cancelled: { border: 'border-gray-500', badge: 'bg-gray-100 text-gray-500' }
    };

    container.innerHTML = orders.map(order => {
        const colors = statusColors[order.status] || statusColors.pending;
        return `
            <div class="bg-white shadow-xl rounded-xl p-6 border-l-4 ${colors.border} transition hover:shadow-2xl">
                <div class="flex justify-between items-start mb-4 border-b pb-3">
                    <h3 class="font-bold text-xl text-amber-900">#${order.order_number}</h3>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full uppercase ${colors.badge}">${order.status.toUpperCase()}</span>
                </div>

                <div class="text-sm text-gray-700 space-y-1 mb-4">
                    <p><strong>Tipe:</strong> ${order.type}</p>
                    ${order.type === 'dine-in' ? `<p><strong>Meja:</strong> ${order.table_no || '-'}</p>` : ''}
                    <p><strong>Bayar:</strong> ${order.payment_method}</p>
                    <p><strong>Total:</strong> <span class="font-bold text-lg text-green-600">Rp ${Number(order.total_amount).toLocaleString('id-ID')}</span></p>
                </div>

                ${order.items && order.items.length > 0 ? `
                    <h4 class="font-bold text-base text-gray-800 mb-2 border-t pt-2">Daftar Menu:</h4>
                    <ul class="space-y-2 text-gray-700 ml-5 mb-4">
                        ${order.items.map(item => `
                            <li class="text-sm">
                                <div class="flex justify-between items-center">
                                    <span>${item.menu?.name || 'Menu tidak ditemukan'}</span>
                                    <span class="font-bold text-amber-700">x${item.quantity}</span>
                                </div>
                                ${item.note ? `<div class="text-xs text-gray-500 italic mt-0.5 pl-2 border-l-2 border-amber-300">📝 ${item.note}</div>` : ''}
                            </li>
                        `).join('')}
                    </ul>
                ` : ''}

                <div class="mt-4 pt-4 border-t flex flex-col gap-3">
                    <button onclick="updateStatus(${order.id}, 'processing')" 
                        class="w-full bg-amber-700 hover:bg-amber-800 text-white font-bold px-4 py-2 rounded-lg transition shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                        ${order.status !== 'pending' ? 'disabled' : ''}>
                        🍳 Mulai Masak
                    </button>
                    <button onclick="updateStatus(${order.id}, 'ready')"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold px-4 py-2 rounded-lg transition shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                        ${order.status !== 'processing' ? 'disabled' : ''}>
                        ✅ Siap Diambil
                    </button>
                </div>

                <div class="mt-6 border-t pt-4">
                    <label class="text-sm font-medium text-gray-700 block mb-2">Ubah Status Detail:</label>
                    <div class="flex gap-2 items-center">
                        <select id="status_${order.id}" class="border border-gray-300 rounded-lg px-3 py-2 flex-grow">
                            <option value="pending" ${order.status === 'pending' ? 'selected' : ''}>Pending</option>
                            <option value="processing" ${order.status === 'processing' ? 'selected' : ''}>Processing</option>
                            <option value="ready" ${order.status === 'ready' ? 'selected' : ''}>Ready</option>
                            <option value="cancelled" ${order.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                        </select>
                        <button onclick="updateStatusFromSelect(${order.id})" class="bg-amber-900 hover:bg-amber-800 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition">Update</button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

async function updateStatus(orderId, status) {
    try {
        await orderApi.updateStatus(orderId, status);
        showAlert('success', 'Status pesanan berhasil diubah!');
        loadOrders();
    } catch (error) {
        showAlert('error', error.data?.message || 'Gagal mengubah status');
    }
}

function updateStatusFromSelect(orderId) {
    const status = document.getElementById(`status_${orderId}`).value;
    updateStatus(orderId, status);
}

function showAlert(type, message) {
    const alertEl = document.getElementById(type === 'success' ? 'alertSuccess' : 'alertError');
    alertEl.textContent = (type === 'success' ? '✅ ' : '❌ ') + message;
    alertEl.classList.remove('hidden');
    setTimeout(() => alertEl.classList.add('hidden'), 3000);
}
</script>
@endsection
