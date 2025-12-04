@extends('layouts.kitchen')

@section('content')
<div class="mb-8 border-b border-gray-300 pb-4">
    <h2 class="text-3xl font-extrabold text-gray-800 flex items-center gap-3">
        <svg class="w-8 h-8 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2m-7 6h7.75"></path></svg>
        Manajemen Stok Menu
    </h2>
    <p class="text-gray-600 mt-1">Perbarui ketersediaan menu dan stok secara real-time.</p>
</div>

<div id="alertSuccess" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-md hidden"></div>
<div id="alertError" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-md hidden"></div>

<!-- Filter -->
<div class="bg-white p-4 rounded-xl shadow-xl mb-6">
    <div class="flex flex-col md:flex-row gap-4 items-end">
        <input type="text" id="searchInput" placeholder="Cari menu..." class="border border-gray-300 rounded-lg px-3 py-2 flex-grow">
        <select id="categoryFilter" class="border border-gray-300 rounded-lg px-3 py-2">
            <option value="">Semua Kategori</option>
        </select>
        <select id="availabilityFilter" class="border border-gray-300 rounded-lg px-3 py-2">
            <option value="">Semua Status</option>
            <option value="available">Tersedia</option>
            <option value="unavailable">Habis</option>
        </select>
        <button onclick="loadStock()" class="bg-amber-900 hover:bg-amber-800 text-white font-medium px-4 py-2 rounded-lg shadow-md transition">🔍 Cari</button>
    </div>
</div>

<!-- Table -->
<div class="bg-white shadow-xl rounded-xl overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr class="bg-amber-900">
                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Nama Menu</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Harga</th>
                <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase">Sisa Stok</th>
                <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase">Status</th>
                <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase">Aksi</th>
                <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase">Update Stok</th>
            </tr>
        </thead>
        <tbody id="stockTable" class="divide-y divide-gray-200">
            <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', loadStock);

async function loadStock() {
    try {
        const search = document.getElementById('searchInput').value;
        const category = document.getElementById('categoryFilter').value;
        const availability = document.getElementById('availabilityFilter').value;
        
        let params = [];
        if (search) params.push(`search=${encodeURIComponent(search)}`);
        if (category) params.push(`category=${category}`);
        if (availability) params.push(`availability=${availability}`);
        
        const response = await stockApi.getAll(params.join('&'));
        
        // Populate categories filter
        const categorySelect = document.getElementById('categoryFilter');
        if (categorySelect.options.length <= 1 && response.data.categories) {
            response.data.categories.forEach(cat => {
                categorySelect.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
            });
        }
        
        renderStockTable(response.data.menus);
    } catch (error) {
        showAlert('error', 'Gagal memuat data stok');
    }
}

function renderStockTable(menus) {
    const tbody = document.getElementById('stockTable');
    
    if (!menus || menus.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-600 italic">Belum ada menu yang tercatat.</td></tr>';
        return;
    }
    
    tbody.innerHTML = menus.map(menu => `
        <tr class="${menu.is_available ? 'hover:bg-gray-50' : 'bg-red-50 hover:bg-red-100'}">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${menu.name}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp ${Number(menu.price).toLocaleString('id-ID')}</td>
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">${menu.stock}</td>
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${menu.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                    ${menu.is_available ? 'Tersedia' : 'Habis'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                <button onclick="toggleAvailability(${menu.id})" 
                    class="px-4 py-2 rounded-lg font-medium shadow-md transition ${menu.is_available ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white'}">
                    ${menu.is_available ? 'Tandai Habis' : 'Tandai Tersedia'}
                </button>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                <div class="flex items-center justify-center gap-2">
                    <input type="number" id="stock_${menu.id}" min="0" value="${menu.stock}" class="border border-gray-300 rounded px-2 py-1 w-20 text-center">
                    <button onclick="updateStock(${menu.id})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">Update</button>
                </div>
            </td>
        </tr>
    `).join('');
}

async function toggleAvailability(menuId) {
    try {
        await stockApi.toggleAvailability(menuId);
        showAlert('success', 'Status ketersediaan berhasil diubah!');
        loadStock();
    } catch (error) {
        showAlert('error', error.data?.message || 'Gagal mengubah status');
    }
}

async function updateStock(menuId) {
    try {
        const stock = document.getElementById(`stock_${menuId}`).value;
        await stockApi.updateStock(menuId, parseInt(stock));
        showAlert('success', 'Stok berhasil diperbarui!');
        loadStock();
    } catch (error) {
        showAlert('error', error.data?.message || 'Gagal memperbarui stok');
    }
}

function showAlert(type, message) {
    const alertEl = document.getElementById(type === 'success' ? 'alertSuccess' : 'alertError');
    alertEl.textContent = (type === 'success' ? '✅ ' : '❌ ') + message;
    alertEl.classList.remove('hidden');
    setTimeout(() => alertEl.classList.add('hidden'), 3000);
}
</script>
@endsection
