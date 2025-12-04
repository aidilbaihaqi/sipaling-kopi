# 📋 Panduan Halaman Kitchen - Sipaling Kopi

## 🔐 Kredensial Login Kitchen

**Email:** kitchen@sipalingkopi.com
**Password:** dapurGacor45

## 🚀 Cara Menjalankan Aplikasi

Ikuti panduan yang sama dengan halaman Admin:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Konfigurasi `.env` untuk database, dan jalankan migrasi jika perlu:

```bash
php artisan migrate --seed
```

Jalankan server:

```bash
php artisan serve
npm run dev
```

---

## 📱 Alur Halaman Kitchen

### 1. Login (`/login`)

* Login untuk staf dapur dengan email dan password.
* Setelah login, diarahkan ke halaman antrian pesanan.

### 2. Antrian Pesanan (`/kitchen`)

* Menampilkan daftar pesanan dengan status `pending`, `processing`, `canceled`, dan `ready`.
* Update status pesanan secara real-time (jika sudah pakai WebSocket atau polling).
* Tampilkan detail menu yang dipesan.

### 3. Manajemen Stok Menu (`/kitchen/stock`)

* Lihat stok dan status ketersediaan menu.
* Filter pencarian menu berdasarkan nama dan status ketersediaan.
* Tandai menu sebagai tersedia atau habis.
* Stok akan otomatis berkurang saat pesanan selesai (`ready`).

### 4. Update Status Pesanan

* Ubah status pesanan dari `pending` → `processing` → `ready`.
* Saat status `ready`, stok menu terkait berkurang secara otomatis.

### 5. Logout

* Tersedia tombol logout di navigation bar.
* Setelah logout, diarahkan kembali ke halaman login.

---

## 🗂️ Struktur File Kitchen

```
app/Http/Controllers/
└── Kitchen/KitchenController.php         # Controller untuk antrian & stok dapur

resources/views/kitchen/
├── index.blade.php               # View antrian pesanan
├── stock.blade.php               # View manajemen stok menu
└── layouts/kitchen.blade.php     # Layout khusus halaman kitchen

routes/web.php                   # Routing untuk halaman kitchen
```

---

## 🔒 Middleware & Keamanan

* Semua route kitchen dilindungi dengan middleware `auth`.
* Session dan CSRF protection aktif.
* Password di-hash menggunakan bcrypt.

---

## 💡 Tips Penggunaan

1. **Selalu update status pesanan tepat waktu** agar dapur dan admin sinkron.
2. **Pantau stok menu secara rutin** untuk menghindari kehabisan bahan.
3. **Gunakan fitur pencarian dan filter** untuk percepat akses menu.
4. **Laporkan segera jika ada masalah stok atau pesanan**.

---

## 🐛 Troubleshooting

### Stok tidak berkurang saat pesanan selesai

* Pastikan status pesanan diubah ke `ready`.
* Pastikan fungsi pengurangan stok di `KitchenController@updateStatus` sudah berjalan.

### Halaman antrian tidak update otomatis

* Cek konfigurasi polling AJAX atau WebSocket jika ada.

---

## 📞 Kontak & Support

Hubungi tim development jika ada pertanyaan atau kendala.

**Selamat bekerja di dapur! ☕🔥**

---