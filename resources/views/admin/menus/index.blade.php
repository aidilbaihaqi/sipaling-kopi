@extends('layouts.app')

@section('title', 'Manajemen Menu')
@section('page-title', 'Manajemen Menu')

@section('content')
<div class="space-y-6">
    <div id="alertSuccess" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded hidden"></div>
    <div id="alertError" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded hidden"></div>

    <div class="flex justify-between items-center">
        <div class="flex gap-2">
            <input type="text" id="searchMenu" placeholder="Cari menu..." 
                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
            <select id="filterCategory" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Semua Kategori</option>
            </select>
        </div>
        <button onclick="openCreateModal()" class="px-4 py-2 bg-amber-900 text-white rounded-lg hover:bg-amber-800">
            + Tambah Menu
        </button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-amber-900 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Stok</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody id="menuTable" class="bg-white divide-y divide-gray-200">
                <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="menuModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <h3 id="modalTitle" class="text-lg font-semibold mb-4">Tambah Menu</h3>
        <form id="menuForm">
            <input type="hidden" id="menuId">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select id="categoryId" required class="w-full px-3 py-2 border rounded-lg"></select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Menu</label>
                <input type="text" id="menuName" required class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea id="menuDescription" rows="2" class="w-full px-3 py-2 border rounded-lg"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                    <input type="number" id="menuPrice" required min="0" class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                    <input type="number" id="menuStock" required min="0" class="w-full px-3 py-2 border rounded-lg">
                </div>
            </div>
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" id="menuAvailable" class="mr-2">
                    <span class="text-sm text-gray-700">Tersedia</span>
                </label>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-amber-900 text-white rounded-lg hover:bg-amber-800">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
let categories = [];

document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    loadMenus();
});

async function loadCategories() {
    try {
        const response = await categoryApi.getAll();
        categories = response.data;
        const filterSelect = document.getElementById('filterCategory');
        const formSelect = document.getElementById('categoryId');
        categories.forEach(function(cat) {
            filterSelect.innerHTML += '<option value="' + cat.id + '">' + cat.name + '</option>';
            formSelect.innerHTML += '<option value="' + cat.id + '">' + cat.name + '</option>';
        });
    } catch (error) {
        showAlert('error', 'Gagal memuat kategori');
    }
}

async function loadMenus(search, categoryId) {
    search = search || '';
    categoryId = categoryId || '';
    try {
        let params = [];
        if (search) params.push('search=' + encodeURIComponent(search));
        if (categoryId) params.push('category_id=' + categoryId);
        const response = await menuApi.getAll(params.join('&'));
        renderMenuTable(response.data);
    } catch (error) {
        showAlert('error', 'Gagal memuat data menu');
    }
}

function renderMenuTable(menus) {
    const tbody = document.getElementById('menuTable');
    if (!menus || menus.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada menu</td></tr>';
        return;
    }
    let html = '';
    menus.forEach(function(menu) {
        const desc = menu.description ? menu.description.substring(0, 30) : '-';
        const catName = menu.category ? menu.category.name : '-';
        const stockClass = menu.stock < 30 ? 'text-red-600' : 'text-green-600';
        const availClass = menu.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        const availText = menu.is_available ? 'Tersedia' : 'Tidak Tersedia';
        html += '<tr>';
        html += '<td class="px-6 py-4"><div class="font-medium text-gray-900">' + menu.name + '</div><div class="text-sm text-gray-500">' + desc + '</div></td>';
        html += '<td class="px-6 py-4"><span class="px-2 py-1 text-xs bg-amber-100 text-amber-800 rounded">' + catName + '</span></td>';
        html += '<td class="px-6 py-4">Rp ' + Number(menu.price).toLocaleString('id-ID') + '</td>';
        html += '<td class="px-6 py-4"><span class="font-semibold ' + stockClass + '">' + menu.stock + '</span></td>';
        html += '<td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded ' + availClass + '">' + availText + '</span></td>';
        html += '<td class="px-6 py-4 text-sm">';
        html += '<button onclick="openEditModal(' + menu.id + ')" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>';
        html += '<button onclick="deleteMenu(' + menu.id + ', \'' + menu.name.replace(/'/g, "\\'") + '\')" class="text-red-600 hover:text-red-900">Hapus</button>';
        html += '</td>';
        html += '</tr>';
    });
    tbody.innerHTML = html;
}

document.getElementById('searchMenu').addEventListener('input', function() {
    loadMenus(this.value, document.getElementById('filterCategory').value);
});

document.getElementById('filterCategory').addEventListener('change', function() {
    loadMenus(document.getElementById('searchMenu').value, this.value);
});

function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Menu';
    document.getElementById('menuId').value = '';
    document.getElementById('menuForm').reset();
    document.getElementById('menuAvailable').checked = true;
    document.getElementById('menuModal').classList.remove('hidden');
    document.getElementById('menuModal').classList.add('flex');
}

async function openEditModal(id) {
    try {
        const response = await menuApi.getOne(id);
        const menu = response.data;
        document.getElementById('modalTitle').textContent = 'Edit Menu';
        document.getElementById('menuId').value = menu.id;
        document.getElementById('categoryId').value = menu.category_id;
        document.getElementById('menuName').value = menu.name;
        document.getElementById('menuDescription').value = menu.description || '';
        document.getElementById('menuPrice').value = menu.price;
        document.getElementById('menuStock').value = menu.stock;
        document.getElementById('menuAvailable').checked = menu.is_available;
        document.getElementById('menuModal').classList.remove('hidden');
        document.getElementById('menuModal').classList.add('flex');
    } catch (error) {
        showAlert('error', 'Gagal memuat data menu');
    }
}

function closeModal() {
    document.getElementById('menuModal').classList.add('hidden');
    document.getElementById('menuModal').classList.remove('flex');
}

document.getElementById('menuForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const id = document.getElementById('menuId').value;
    const data = {
        category_id: document.getElementById('categoryId').value,
        name: document.getElementById('menuName').value,
        description: document.getElementById('menuDescription').value,
        price: parseFloat(document.getElementById('menuPrice').value),
        stock: parseInt(document.getElementById('menuStock').value),
        is_available: document.getElementById('menuAvailable').checked
    };
    try {
        if (id) {
            await menuApi.update(id, data);
            showAlert('success', 'Menu berhasil diupdate');
        } else {
            await menuApi.create(data);
            showAlert('success', 'Menu berhasil ditambahkan');
        }
        closeModal();
        loadMenus();
    } catch (error) {
        showAlert('error', error.data ? error.data.message : 'Gagal menyimpan menu');
    }
});

async function deleteMenu(id, name) {
    if (!confirm('Yakin hapus menu "' + name + '"?')) return;
    try {
        await menuApi.delete(id);
        showAlert('success', 'Menu berhasil dihapus');
        loadMenus();
    } catch (error) {
        showAlert('error', error.data ? error.data.message : 'Gagal menghapus menu');
    }
}

function showAlert(type, message) {
    const alertEl = document.getElementById(type === 'success' ? 'alertSuccess' : 'alertError');
    alertEl.textContent = message;
    alertEl.classList.remove('hidden');
    setTimeout(function() { alertEl.classList.add('hidden'); }, 3000);
}
</script>
@endsection
