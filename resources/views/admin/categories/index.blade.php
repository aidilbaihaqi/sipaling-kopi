@extends('layouts.app')

@section('title', 'Manajemen Kategori')
@section('page-title', 'Manajemen Kategori')

@section('content')
<div class="space-y-6">
    <div id="alertSuccess" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded hidden"></div>
    <div id="alertError" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded hidden"></div>

    <div class="flex justify-end">
        <button onclick="openCreateModal()" class="px-4 py-2 bg-amber-900 text-white rounded-lg hover:bg-amber-800">
            + Tambah Kategori
        </button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-amber-900 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Jumlah Menu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody id="categoryTable" class="bg-white divide-y divide-gray-200">
                <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <h3 id="modalTitle" class="text-lg font-semibold mb-4">Tambah Kategori</h3>
        <form id="categoryForm">
            <input type="hidden" id="categoryId">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                <input type="text" id="categoryName" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-amber-900">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea id="categoryDescription" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-amber-900"></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-amber-900 text-white rounded-lg hover:bg-amber-800">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', loadCategories);

async function loadCategories() {
    try {
        const response = await categoryApi.getAll();
        renderCategoryTable(response.data);
    } catch (error) {
        showAlert('error', 'Gagal memuat data kategori');
    }
}

function renderCategoryTable(categories) {
    const tbody = document.getElementById('categoryTable');
    if (categories.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada kategori</td></tr>';
        return;
    }
    tbody.innerHTML = categories.map(cat => `
        <tr>
            <td class="px-6 py-4 font-medium text-gray-900">${cat.name}</td>
            <td class="px-6 py-4 text-gray-500">${cat.description || '-'}</td>
            <td class="px-6 py-4"><span class="px-2 py-1 text-xs bg-amber-100 text-amber-800 rounded">${cat.menus_count || 0} menu</span></td>
            <td class="px-6 py-4 text-sm">
                <button onclick="openEditModal(${cat.id})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                <button onclick="deleteCategory(${cat.id}, '${cat.name}')" class="text-red-600 hover:text-red-900">Hapus</button>
            </td>
        </tr>
    `).join('');
}

function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Kategori';
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryModal').classList.remove('hidden');
    document.getElementById('categoryModal').classList.add('flex');
}

async function openEditModal(id) {
    try {
        const response = await categoryApi.getOne(id);
        const cat = response.data;
        document.getElementById('modalTitle').textContent = 'Edit Kategori';
        document.getElementById('categoryId').value = cat.id;
        document.getElementById('categoryName').value = cat.name;
        document.getElementById('categoryDescription').value = cat.description || '';
        document.getElementById('categoryModal').classList.remove('hidden');
        document.getElementById('categoryModal').classList.add('flex');
    } catch (error) {
        showAlert('error', 'Gagal memuat data kategori');
    }
}

function closeModal() {
    document.getElementById('categoryModal').classList.add('hidden');
    document.getElementById('categoryModal').classList.remove('flex');
}

document.getElementById('categoryForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('categoryId').value;
    const data = {
        name: document.getElementById('categoryName').value,
        description: document.getElementById('categoryDescription').value,
    };
    try {
        if (id) {
            await categoryApi.update(id, data);
            showAlert('success', 'Kategori berhasil diupdate');
        } else {
            await categoryApi.create(data);
            showAlert('success', 'Kategori berhasil ditambahkan');
        }
        closeModal();
        loadCategories();
    } catch (error) {
        showAlert('error', error.data?.message || 'Gagal menyimpan kategori');
    }
});

async function deleteCategory(id, name) {
    if (!confirm(`Yakin hapus kategori "${name}"? Semua menu dalam kategori ini juga akan terhapus.`)) return;
    try {
        await categoryApi.delete(id);
        showAlert('success', 'Kategori berhasil dihapus');
        loadCategories();
    } catch (error) {
        showAlert('error', error.data?.message || 'Gagal menghapus kategori');
    }
}

function showAlert(type, message) {
    const alertEl = document.getElementById(type === 'success' ? 'alertSuccess' : 'alertError');
    alertEl.textContent = message;
    alertEl.classList.remove('hidden');
    setTimeout(() => alertEl.classList.add('hidden'), 3000);
}
</script>
@endsection
