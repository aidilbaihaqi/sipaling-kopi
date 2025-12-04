@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6">
    <div id="alertSuccess" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded hidden"></div>
    <div id="alertError" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded hidden"></div>

    <div class="flex justify-between items-center">
        <div class="flex gap-2">
            <input type="text" id="searchUser" placeholder="Cari pengguna..." 
                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-900">
            <select id="filterRole" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Semua Role</option>
                <option value="admin">Admin</option>
                <option value="cashier">Kasir</option>
                <option value="kitchen">Dapur</option>
            </select>
        </div>
        <button onclick="openCreateModal()" class="px-4 py-2 bg-amber-900 text-white rounded-lg hover:bg-amber-800">
            + Tambah Pengguna
        </button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-amber-900 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody id="userTable" class="bg-white divide-y divide-gray-200">
                <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <h3 id="modalTitle" class="text-lg font-semibold mb-4">Tambah Pengguna</h3>
        <form id="userForm">
            <input type="hidden" id="userId">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" id="userName" required class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="userEmail" required class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password <span id="passwordHint" class="text-xs text-gray-500">(kosongkan jika tidak ingin mengubah)</span></label>
                <input type="password" id="userPassword" class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select id="userRole" required class="w-full px-3 py-2 border rounded-lg">
                    <option value="admin">Admin</option>
                    <option value="cashier">Kasir</option>
                    <option value="kitchen">Dapur</option>
                </select>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-amber-900 text-white rounded-lg hover:bg-amber-800">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', loadUsers);

async function loadUsers(search = '', role = '') {
    try {
        let params = [];
        if (search) params.push(`search=${encodeURIComponent(search)}`);
        if (role) params.push(`role=${role}`);
        const response = await userApi.getAll(params.join('&'));
        renderUserTable(response.data);
    } catch (error) {
        showAlert('error', 'Gagal memuat data pengguna');
    }
}

function renderUserTable(users) {
    const tbody = document.getElementById('userTable');
    if (users.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada pengguna</td></tr>';
        return;
    }
    const roleColors = { admin: 'bg-purple-100 text-purple-800', cashier: 'bg-blue-100 text-blue-800', kitchen: 'bg-green-100 text-green-800' };
    const roleLabels = { admin: 'Admin', cashier: 'Kasir', kitchen: 'Dapur' };
    tbody.innerHTML = users.map(user => `
        <tr>
            <td class="px-6 py-4 font-medium text-gray-900">${user.name}</td>
            <td class="px-6 py-4 text-gray-500">${user.email}</td>
            <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded ${roleColors[user.role]}">${roleLabels[user.role]}</span></td>
            <td class="px-6 py-4 text-sm">
                <button onclick="openEditModal(${user.id})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                <button onclick="deleteUser(${user.id}, '${user.name}')" class="text-red-600 hover:text-red-900">Hapus</button>
            </td>
        </tr>
    `).join('');
}

document.getElementById('searchUser').addEventListener('input', function() {
    loadUsers(this.value, document.getElementById('filterRole').value);
});

document.getElementById('filterRole').addEventListener('change', function() {
    loadUsers(document.getElementById('searchUser').value, this.value);
});

function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Pengguna';
    document.getElementById('userId').value = '';
    document.getElementById('userForm').reset();
    document.getElementById('userPassword').required = true;
    document.getElementById('passwordHint').classList.add('hidden');
    document.getElementById('userModal').classList.remove('hidden');
    document.getElementById('userModal').classList.add('flex');
}

async function openEditModal(id) {
    try {
        const response = await userApi.getOne(id);
        const user = response.data;
        document.getElementById('modalTitle').textContent = 'Edit Pengguna';
        document.getElementById('userId').value = user.id;
        document.getElementById('userName').value = user.name;
        document.getElementById('userEmail').value = user.email;
        document.getElementById('userPassword').value = '';
        document.getElementById('userPassword').required = false;
        document.getElementById('passwordHint').classList.remove('hidden');
        document.getElementById('userRole').value = user.role;
        document.getElementById('userModal').classList.remove('hidden');
        document.getElementById('userModal').classList.add('flex');
    } catch (error) {
        showAlert('error', 'Gagal memuat data pengguna');
    }
}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
    document.getElementById('userModal').classList.remove('flex');
}

document.getElementById('userForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('userId').value;
    const data = {
        name: document.getElementById('userName').value,
        email: document.getElementById('userEmail').value,
        role: document.getElementById('userRole').value,
    };
    const password = document.getElementById('userPassword').value;
    if (password) data.password = password;
    
    try {
        if (id) {
            await userApi.update(id, data);
            showAlert('success', 'Pengguna berhasil diupdate');
        } else {
            await userApi.create(data);
            showAlert('success', 'Pengguna berhasil ditambahkan');
        }
        closeModal();
        loadUsers();
    } catch (error) {
        showAlert('error', error.data?.message || 'Gagal menyimpan pengguna');
    }
});

async function deleteUser(id, name) {
    if (!confirm(`Yakin hapus pengguna "${name}"?`)) return;
    try {
        await userApi.delete(id);
        showAlert('success', 'Pengguna berhasil dihapus');
        loadUsers();
    } catch (error) {
        showAlert('error', error.data?.message || 'Gagal menghapus pengguna');
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
