# 📋 Panduan Halaman Admin - Sipaling Kopi

## 🔐 Kredensial Login Admin

**Email:** admin@sipalingkopi.com  
**Password:** GakNgopiGakGacor123

---

## 🚀 Cara Menjalankan Aplikasi

### 1. Persiapan Awal

```bash
# Install dependencies PHP
composer install

# Install dependencies Node.js
npm install

# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 2. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipaling_kopi
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Migrasi Database

```bash
# Jalankan migrasi dan seeder
php artisan migrate --seed
```

### 4. Menjalankan Aplikasi

Buka 2 terminal terpisah:

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 - Vite Dev Server:**
```bash
npm run dev
```

Aplikasi akan berjalan di: `http://localhost:8000`

---

## 📱 Alur Halaman Admin

### 1. Login (`/login`)
- Halaman pertama yang muncul saat mengakses aplikasi
- User memasukkan email dan password
- Setelah berhasil login, akan diarahkan ke Dashboard

### 2. Dashboard (`/admin/dashboard`)
Halaman utama admin yang menampilkan:
- **Statistik Hari Ini:**
  - Total Penjualan
  - Total Pesanan
  - Menu Terlaris
- **Grafik Penjualan:** Visualisasi penjualan 7 hari terakhir
- **Pesanan Terbaru:** Daftar pesanan terkini dengan status
- **Stok Kritis:** Peringatan untuk menu dengan stok menipis

### 3. Manajemen Menu (`/admin/menus`)
Fitur untuk mengelola menu kopi:
- **Lihat Semua Menu:** Daftar lengkap menu dengan informasi harga dan stok
- **Tambah Menu Baru:** Form untuk menambahkan menu baru
- **Edit Menu:** Mengubah informasi menu yang sudah ada
- **Hapus Menu:** Menghapus menu dari sistem
- **Filter berdasarkan Kategori**

### 4. Manajemen Kategori (`/admin/categories`)
Fitur untuk mengelola kategori menu:
- **Lihat Semua Kategori:** Daftar kategori (Kopi, Minuman, Makanan, dll)
- **Tambah Kategori:** Membuat kategori baru
- **Edit Kategori:** Mengubah nama atau deskripsi kategori
- **Hapus Kategori:** Menghapus kategori (jika tidak ada menu terkait)

### 5. Manajemen User (`/admin/users`)
Fitur untuk mengelola pengguna sistem:
- **Lihat Semua User:** Daftar admin dan staff
- **Tambah User Baru:** Membuat akun user baru
- **Edit User:** Mengubah informasi user
- **Hapus User:** Menghapus user dari sistem
- **Atur Role:** Admin atau Staff

### 6. Laporan (`/admin/reports`)
Fitur pelaporan dan analisis:
- **Laporan Penjualan:** Per hari, minggu, bulan
- **Laporan Menu Terlaris:** Analisis produk populer
- **Laporan Stok:** Monitoring inventory
- **Export Data:** Download laporan dalam format Excel/PDF

### 7. Logout
- Tombol logout tersedia di navigation bar
- Setelah logout, user akan diarahkan kembali ke halaman login

---

## 🗂️ Struktur File Admin

```
app/Http/Controllers/Admin/
├── DashboardController.php    # Controller untuk dashboard
├── MenuController.php          # Controller untuk manajemen menu
├── CategoryController.php      # Controller untuk manajemen kategori
├── UserController.php          # Controller untuk manajemen user
└── ReportController.php        # Controller untuk laporan

resources/views/admin/
├── dashboard.blade.php         # View dashboard
├── menus/                      # Views untuk menu
├── categories/                 # Views untuk kategori
├── users/                      # Views untuk user
└── reports/                    # Views untuk laporan

routes/
└── web.php                     # Routing halaman admin
```

---

## 🔒 Middleware & Keamanan

- Semua route admin dilindungi dengan middleware `auth`
- Session akan otomatis regenerate setelah login
- CSRF protection aktif untuk semua form
- Password di-hash menggunakan bcrypt

---

## 💡 Tips Penggunaan

1. **Backup Data Berkala:** Selalu backup database secara rutin
2. **Monitor Stok:** Perhatikan notifikasi stok kritis di dashboard
3. **Analisis Laporan:** Gunakan laporan untuk strategi bisnis
4. **Kelola User:** Berikan akses sesuai kebutuhan (Admin/Staff)

---

## 🐛 Troubleshooting

### Error: "SQLSTATE[HY000] [1049] Unknown database"
```bash
# Buat database terlebih dahulu
mysql -u root -p
CREATE DATABASE sipaling_kopi;
exit;
php artisan migrate --seed
```

### Error: "Vite manifest not found"
```bash
# Pastikan Vite dev server berjalan
npm run dev
```

### Lupa Password Admin
```bash
# Reset password melalui tinker
php artisan tinker
$user = App\Models\User::where('email', 'admin@sipalingkopi.com')->first();
$user->password = bcrypt('PasswordBaru123');
$user->save();
exit;
```

---

## 📞 Kontak & Support

Jika ada pertanyaan atau masalah, silakan hubungi tim development.

**Selamat menggunakan Sipaling Kopi Admin Panel! ☕**
