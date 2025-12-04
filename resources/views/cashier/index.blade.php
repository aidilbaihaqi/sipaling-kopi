@extends('layouts.cashier')

@section('content')
<div class="flex h-full">
    
    <!-- BAGIAN KIRI: KATALOG MENU -->
    <div class="w-7/12 flex flex-col h-full bg-gray-50 border-r border-gray-200">
        <div class="px-5 py-4 bg-white border-b border-gray-200 flex justify-between items-center shadow-sm z-10 shrink-0">
            <h2 class="font-bold text-gray-800 text-lg">Daftar Menu</h2>
            <div class="relative w-64">
                <span class="absolute left-3 top-2.5 text-gray-400 text-sm">🔍</span>
                <input type="text" id="search-menu" placeholder="Cari menu..." 
                    class="w-full bg-gray-100 border-none rounded-full py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-amber-500 transition">
            </div>
        </div>

        <div id="alertSuccess" class="mx-5 mt-3 bg-green-100 border-l-4 border-green-500 text-green-700 p-2 text-sm rounded shadow-sm hidden"></div>
        <div id="alertError" class="mx-5 mt-3 bg-red-100 border-l-4 border-red-500 text-red-700 p-2 text-sm rounded shadow-sm hidden"></div>

        <div class="flex-1 overflow-y-auto p-5 scrollbar-hide">
            <div class="grid grid-cols-3 gap-4" id="menu-container">
                <div class="col-span-3 text-center py-8 text-gray-500">Loading...</div>
            </div>
        </div>
    </div>

    <!-- BAGIAN KANAN: KERANJANG -->
    <div class="w-5/12 bg-white shadow-2xl flex flex-col h-full z-20">
        
        <!-- Header Keranjang -->
        <div class="bg-white shrink-0 z-20 shadow-sm border-b border-gray-200 relative">
            <div class="px-5 py-3 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-bold text-lg text-gray-800 flex items-center gap-2">🛒 Pesanan</h2>
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="takeaway" checked class="peer hidden" onchange="toggleTableInput()">
                        <div class="px-3 py-1.5 text-xs font-bold text-gray-500 rounded-md peer-checked:bg-white peer-checked:text-amber-600 peer-checked:shadow-sm transition">🥡 Take Away</div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="dine-in" class="peer hidden" onchange="toggleTableInput()">
                        <div class="px-3 py-1.5 text-xs font-bold text-gray-500 rounded-md peer-checked:bg-white peer-checked:text-amber-600 peer-checked:shadow-sm transition">🍽️ Dine In</div>
                    </label>
                </div>
            </div>
            <div class="px-5 py-3 bg-gray-50">
                <div class="grid grid-cols-3 gap-2">
                    <div class="col-span-3" id="customer-div">
                        <input type="text" id="customer_name" required class="w-full bg-white border border-gray-300 text-gray-800 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block p-2" placeholder="Nama Pelanggan...">
                    </div>
                    <div id="table-input-div" class="hidden">
                        <input type="text" id="table_no" class="w-full bg-white border border-gray-300 text-gray-800 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block p-2 text-center" placeholder="No. Meja">
                    </div>
                </div>
            </div>
        </div>

        <!-- List Items -->
        <div id="cart-items" class="flex-1 overflow-y-auto p-4 space-y-3 bg-white min-h-0">
            <div id="empty-cart" class="h-full flex flex-col items-center justify-center text-gray-300">
                <span class="text-6xl mb-4">🧾</span>
                <p class="text-lg font-medium">Keranjang Kosong</p>
                <p class="text-sm">Silakan pilih menu</p>
            </div>
        </div>

        <!-- Footer Pembayaran -->
        <div class="p-5 bg-white border-t border-gray-200 shadow-[0_-5px_20px_rgba(0,0,0,0.05)] shrink-0 z-30">
            <div class="flex gap-3 mb-3">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="payment_method" value="cash" checked class="peer hidden" onchange="togglePaymentMethod()">
                    <div class="py-2 text-center text-xs font-bold border border-gray-300 rounded-lg text-gray-600 peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 transition hover:bg-gray-50">💵 Tunai</div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="payment_method" value="qris" class="peer hidden" onchange="togglePaymentMethod()">
                    <div class="py-2 text-center text-xs font-bold border border-gray-300 rounded-lg text-gray-600 peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 transition hover:bg-gray-50">📱 QRIS</div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="payment_method" value="transfer" class="peer hidden" onchange="togglePaymentMethod()">
                    <div class="py-2 text-center text-xs font-bold border border-gray-300 rounded-lg text-gray-600 peer-checked:bg-purple-50 peer-checked:border-purple-500 peer-checked:text-purple-700 transition hover:bg-gray-50">🏦 Transfer</div>
                </label>
            </div>

            <div class="space-y-3">
                <div class="flex justify-between items-end border-b border-dashed border-gray-300 pb-3">
                    <span class="text-gray-600 text-sm font-medium">Total Tagihan</span>
                    <span class="font-extrabold text-gray-900 text-2xl leading-none" id="total-display">Rp 0</span>
                </div>
                <div class="flex gap-3">
                    <div class="w-1/2 relative">
                        <span class="absolute left-3 top-3 text-gray-400 font-bold text-sm">Rp</span>
                        <input type="number" id="payment-amount" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-lg rounded-xl focus:ring-amber-500 focus:border-amber-500 block pl-10 p-2.5 font-bold" placeholder="0">
                        <div class="absolute -bottom-5 right-0 text-xs text-gray-500 font-medium">
                            Kembali: <span id="change-display" class="text-emerald-600 font-bold">Rp 0</span>
                        </div>
                    </div>
                    <div class="w-1/2">
                        <button type="button" id="btn-pay" onclick="processCheckout()" disabled class="w-full h-full text-white bg-amber-600 hover:bg-amber-700 focus:ring-4 focus:ring-amber-300 font-bold rounded-xl text-base transition disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-amber-500/30">BAYAR</button>
                    </div>
                </div>
                <div class="h-2"></div>
            </div>
        </div>
    </div>
</div>

<script>
let cart = {};
let menus = [];
let totalPrice = 0;

document.addEventListener('DOMContentLoaded', loadMenus);

async function loadMenus() {
    try {
        const response = await menuApi.getAvailable();
        menus = response.data;
        renderMenus(menus);
    } catch (error) {
        showAlert('error', 'Gagal memuat menu');
    }
}

function renderMenus(menuList) {
    const container = document.getElementById('menu-container');
    if (!menuList || menuList.length === 0) {
        container.innerHTML = '<div class="col-span-3 text-center py-8 text-gray-500">Tidak ada menu tersedia</div>';
        return;
    }
    container.innerHTML = menuList.map(menu => `
        <div onclick="addToCart(${menu.id}, '${menu.name}', ${menu.price}, ${menu.stock})"
            class="menu-item group bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 cursor-pointer transition-all duration-200 transform hover:-translate-y-1 overflow-hidden relative flex flex-col"
            data-name="${menu.name.toLowerCase()}">
            <div class="absolute top-2 right-2 px-2 py-0.5 rounded text-[10px] font-bold z-10 ${menu.stock > 5 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">Stok: ${menu.stock}</div>
            <div class="h-28 bg-amber-50 flex items-center justify-center group-hover:bg-amber-100 transition-colors overflow-hidden shrink-0">
                <span class="text-3xl">☕</span>
            </div>
            <div class="p-3 flex flex-col justify-between flex-1">
                <h3 class="font-bold text-gray-800 text-sm mb-1 leading-tight group-hover:text-amber-700 transition truncate">${menu.name}</h3>
                <p class="font-bold text-amber-600 text-sm">Rp ${Number(menu.price).toLocaleString('id-ID')}</p>
            </div>
        </div>
    `).join('');
}

document.getElementById('search-menu').addEventListener('keyup', function() {
    const val = this.value.toLowerCase();
    const items = document.querySelectorAll('.menu-item');
    items.forEach(item => {
        const name = item.getAttribute('data-name');
        item.style.display = name.includes(val) ? 'flex' : 'none';
    });
});

function toggleTableInput() {
    const type = document.querySelector('input[name="type"]:checked').value;
    const tableDiv = document.getElementById('table-input-div');
    const customerDiv = document.getElementById('customer-div');
    if (type === 'dine-in') {
        tableDiv.classList.remove('hidden');
        customerDiv.classList.remove('col-span-3');
        customerDiv.classList.add('col-span-2');
    } else {
        tableDiv.classList.add('hidden');
        document.getElementById('table_no').value = '';
        customerDiv.classList.remove('col-span-2');
        customerDiv.classList.add('col-span-3');
    }
}

function togglePaymentMethod() {
    const method = document.querySelector('input[name="payment_method"]:checked').value;
    const payInput = document.getElementById('payment-amount');
    if (method === 'qris' || method === 'transfer') {
        payInput.value = totalPrice;
        payInput.setAttribute('readonly', 'readonly');
        payInput.classList.add('bg-gray-100', 'text-gray-500');
    } else {
        payInput.value = '';
        payInput.removeAttribute('readonly');
        payInput.classList.remove('bg-gray-100', 'text-gray-500');
    }
    calculateChange();
}

function addToCart(id, name, price, stock) {
    if (cart[id]) {
        if (cart[id].qty < stock) {
            cart[id].qty++;
        } else {
            alert('Stok menu ini habis!');
            return;
        }
    } else {
        cart[id] = { name, price, qty: 1, stock, note: '' };
    }
    updateCartUI();
}

function updateQty(id, change) {
    if (cart[id]) {
        cart[id].qty += change;
        if (cart[id].qty <= 0) delete cart[id];
        else if (cart[id].qty > cart[id].stock) {
            cart[id].qty = cart[id].stock;
            alert('Maksimal stok tercapai!');
        }
    }
    updateCartUI();
}

function updateNote(id, val) {
    if (cart[id]) cart[id].note = val;
}

function updateCartUI() {
    const container = document.getElementById('cart-items');
    const totalDisplay = document.getElementById('total-display');
    
    let html = '';
    totalPrice = 0;
    let hasItems = false;

    for (let id in cart) {
        hasItems = true;
        const item = cart[id];
        const subtotal = item.price * item.qty;
        totalPrice += subtotal;

        html += `
            <div class="flex flex-col mb-3 pb-3 border-b border-gray-100 last:border-0 bg-white p-2 rounded-lg border border-gray-100 hover:border-amber-200 transition relative group">
                <div class="flex justify-between items-start">
                    <div class="pr-2 flex-1">
                        <h4 class="font-bold text-sm text-gray-800 leading-tight">${item.name}</h4>
                        <div class="text-xs text-gray-500 mt-1 font-medium">@ Rp ${item.price.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <div class="font-bold text-sm text-amber-600">Rp ${subtotal.toLocaleString('id-ID')}</div>
                        <div class="flex items-center gap-2 bg-gray-100 rounded-lg px-1.5 py-0.5 border border-gray-200">
                            <button type="button" onclick="updateQty(${id}, -1)" class="w-6 h-6 flex items-center justify-center bg-white rounded text-gray-600 hover:text-red-500 font-bold shadow-sm transition hover:bg-red-50">-</button>
                            <span class="text-sm font-bold w-6 text-center text-gray-800">${item.qty}</span>
                            <button type="button" onclick="updateQty(${id}, 1)" class="w-6 h-6 flex items-center justify-center bg-white rounded text-gray-600 hover:text-green-500 font-bold shadow-sm transition hover:bg-green-50">+</button>
                        </div>
                    </div>
                </div>
                <div class="mt-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none"><span class="text-[10px]">📝</span></div>
                    <input type="text" placeholder="Catatan..." value="${item.note}" oninput="updateNote(${id}, this.value)"
                        class="w-full text-xs border border-gray-200 rounded-md focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none py-1.5 pl-7 bg-gray-50 text-gray-700 italic placeholder-gray-400 transition">
                </div>
            </div>
        `;
    }

    if (!hasItems) {
        html = `<div class="h-full flex flex-col items-center justify-center text-gray-300">
            <span class="text-6xl mb-4">🧾</span>
            <p class="text-lg font-medium">Keranjang Kosong</p>
            <p class="text-sm">Pilih menu di sebelah kiri</p>
        </div>`;
    }

    container.innerHTML = html;
    totalDisplay.innerText = 'Rp ' + totalPrice.toLocaleString('id-ID');
    togglePaymentMethod();
}

const paymentInput = document.getElementById('payment-amount');
const changeDisplay = document.getElementById('change-display');
const btnPay = document.getElementById('btn-pay');

function calculateChange() {
    const pay = parseFloat(paymentInput.value) || 0;
    if (totalPrice > 0 && pay >= totalPrice) {
        changeDisplay.innerText = 'Rp ' + (pay - totalPrice).toLocaleString('id-ID');
        btnPay.disabled = false;
    } else {
        changeDisplay.innerText = 'Rp 0';
        btnPay.disabled = true;
    }
}

paymentInput.addEventListener('input', calculateChange);

async function processCheckout() {
    const customerName = document.getElementById('customer_name').value;
    if (!customerName) {
        showAlert('error', 'Nama pelanggan wajib diisi!');
        return;
    }

    const type = document.querySelector('input[name="type"]:checked').value;
    const tableNo = document.getElementById('table_no').value;
    if (type === 'dine-in' && !tableNo) {
        showAlert('error', 'Nomor meja wajib diisi untuk Dine In!');
        return;
    }

    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    const paymentAmount = parseFloat(paymentInput.value) || 0;

    const cartItems = [];
    for (let id in cart) {
        cartItems.push({
            menu_id: parseInt(id),
            qty: cart[id].qty,
            note: cart[id].note
        });
    }

    if (cartItems.length === 0) {
        showAlert('error', 'Keranjang kosong!');
        return;
    }

    btnPay.disabled = true;
    btnPay.textContent = 'Processing...';

    try {
        const response = await orderApi.create({
            customer_name: customerName,
            type: type,
            table_no: tableNo,
            payment_method: paymentMethod,
            cart: cartItems,
            payment_amount: paymentAmount,
            total_price: totalPrice
        });

        if (response.status === 'success') {
            showAlert('success', 'Transaksi berhasil!');
            // Redirect to print page
            window.open(`/cashier/print/${response.data.id}`, '_blank');
            // Reset cart
            cart = {};
            totalPrice = 0;
            document.getElementById('customer_name').value = '';
            document.getElementById('table_no').value = '';
            paymentInput.value = '';
            updateCartUI();
            loadMenus(); // Refresh menu stock
        }
    } catch (error) {
        showAlert('error', error.data?.message || 'Transaksi gagal!');
    } finally {
        btnPay.disabled = false;
        btnPay.textContent = 'BAYAR';
    }
}

function showAlert(type, message) {
    const alertEl = document.getElementById(type === 'success' ? 'alertSuccess' : 'alertError');
    alertEl.textContent = message;
    alertEl.classList.remove('hidden');
    setTimeout(() => alertEl.classList.add('hidden'), 3000);
}

toggleTableInput();
</script>
@endsection
