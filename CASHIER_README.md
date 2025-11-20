📋 Panduan Halaman Kasir - Sipaling Kopi
🔐 Kredensial Login Kasir
Email: kasir@sipalingkopi.com Password: CuanLancarJaya99

🚀 Cara Menjalankan Aplikasi
Ikuti langkah standar seperti halaman Admin dan Kitchen:

1. Persiapan Environment
Bash

composer install
npm install
cp .env.example .env
php artisan key:generate
Konfigurasi database di .env, lalu jalankan migrasi:

Bash

php artisan migrate --seed
2. Menjalankan Server
Buka 2 terminal terpisah agar fitur real-time berjalan lancar:

Terminal 1 (Laravel):

Bash

php artisan serve
Terminal 2 (Vite Assets):

Bash

npm run dev
Akses aplikasi di: http://localhost:8000

📱 Alur Halaman Kasir (Point of Sales)
1. Login (/login)
Halaman masuk khusus untuk staff kasir.

Setelah login berhasil, sistem langsung mengarahkan ke halaman Transaksi (POS).

2. Halaman Transaksi / POS (/cashier)
Halaman utama tempat kasir bekerja. Fitur mencakup:

Katalog Menu: Menampilkan grid menu dengan foto, nama, dan harga.

Keranjang (Cart): Di sebelah kanan, menampilkan item yang dipilih pelanggan.

Input Pesanan: Klik menu untuk menambahkan ke keranjang.

Kalkulasi Otomatis: Subtotal dan Total akan terhitung otomatis.

3. Checkout & Pembayaran
Input Nama Pelanggan: Wajib diisi untuk identifikasi saat pemanggilan.

Input Nominal Bayar: Masukkan uang yang diterima dari pelanggan.

Hitung Kembalian: Sistem otomatis menampilkan jumlah kembalian.

Proses Transaksi: Klik "Bayar" untuk menyelesaikan pesanan.

Stok menu otomatis berkurang.

Pesanan otomatis masuk ke layar Kitchen dengan status pending.

4. Cetak Struk (/cashier/print/{id})
Setelah transaksi sukses, tombol "Cetak Struk" akan muncul.

Mencetak detail pesanan, total bayar, dan kembalian untuk pelanggan.

5. Riwayat Transaksi (/cashier/history)
Melihat daftar transaksi yang sudah selesai hari ini.

Fitur untuk mencetak ulang struk jika diperlukan.

6. Logout
Tombol logout di pojok kanan atas.

Mengembalikan user ke halaman login.

🗂️ Struktur File Kasir
Berikut adalah lokasi file kodingan untuk modul Kasir:

app/Http/Controllers/Cashier/
└── CashierController.php       # Controller logika POS, checkout, dan print

resources/views/cashier/
├── index.blade.php             # View utama (Daftar Menu & Keranjang)
├── history.blade.php           # View riwayat transaksi harian
├── print.blade.php             # View template struk belanja
└── layouts/cashier.blade.php   # Layout khusus halaman kasir (tanpa sidebar admin)

routes/web.php                  # Routing group prefix 'cashier'
🔒 Middleware & Keamanan
Route dilindungi middleware auth dan role check cashier.

Validasi Stok: Sistem menolak pesanan jika jumlah di keranjang melebihi stok tersedia.

Validasi Pembayaran: Transaksi tidak bisa diproses jika uang bayar kurang dari total tagihan.

💡 Tips Penggunaan
Cek Stok Fisik vs Sistem: Pastikan stok di layar sesuai dengan ketersediaan nyata sebelum jam sibuk.

Konfirmasi Pesanan: Bacakan ulang pesanan ke pelanggan sebelum menekan tombol "Bayar".

Refresh Halaman: Jika menu baru ditambahkan oleh Admin, lakukan refresh browser.

Printer Struk: Pastikan printer sudah terkoneksi dan kertas tersedia.

🐛 Troubleshooting
Tombol "Bayar" tidak berfungsi
Pastikan input "Nama Pelanggan" sudah diisi.

Pastikan "Nominal Bayar" lebih besar atau sama dengan Total.

Pesanan tidak muncul di Dapur
Cek koneksi internet/lokal.

Pastikan status pesanan di database tersimpan sebagai pending.

Stok menu jadi minus
Hubungi admin untuk reset stok.

Pastikan jangan melakukan force reload saat proses saving sedang berjalan.

📞 Kontak & Support
Jika printer macet atau ada selisih uang kas, segera lapor ke Manajer.

Semangat Pejuang Omzet! 💸☕