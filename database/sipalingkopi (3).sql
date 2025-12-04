-- ============================================
-- phpMyAdmin SQL Dump
-- Database: sipalingkopi
-- Cafe Management System
-- ============================================
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 26 Nov 2025 pada 16.12
-- Versi server: 9.1.0
-- Versi PHP: 8.4.0
-- ============================================

-- Set SQL mode untuk mencegah auto value pada zero
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

-- Mulai transaksi database
START TRANSACTION;

-- Set timezone ke UTC
SET time_zone = "+00:00";


-- Simpan setting character set lama
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

-- Set character set ke utf8mb4 untuk support emoji dan karakter internasional
/*!40101 SET NAMES utf8mb4 */;

-- ============================================
-- Database: `sipalingkopi`
-- ============================================

-- --------------------------------------------------------
-- TABEL CACHE
-- Tabel untuk menyimpan cache aplikasi Laravel
-- --------------------------------------------------------

-- Hapus tabel cache jika sudah ada
DROP TABLE IF EXISTS `cache`;

-- Buat tabel cache baru
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,        -- Key unik untuk cache
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,        -- Nilai cache yang disimpan
  `expiration` int NOT NULL,                                      -- Waktu kadaluarsa cache (timestamp)
  PRIMARY KEY (`key`)                                             -- Key sebagai primary key
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABEL CACHE LOCKS
-- Tabel untuk menyimpan lock cache (mencegah race condition)
-- --------------------------------------------------------

-- Hapus tabel cache_locks jika sudah ada
DROP TABLE IF EXISTS `cache_locks`;

-- Buat tabel cache_locks baru
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,        -- Key lock
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,      -- Pemilik lock
  `expiration` int NOT NULL,                                      -- Waktu kadaluarsa lock
  PRIMARY KEY (`key`)                                             -- Key sebagai primary key
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABEL CATEGORIES
-- Tabel untuk menyimpan kategori menu (Kopi Panas, Kopi Dingin, dll)
-- --------------------------------------------------------

-- Hapus tabel categories jika sudah ada
DROP TABLE IF EXISTS `categories`;

-- Buat tabel categories baru
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,                  -- ID kategori (auto increment)
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,       -- Nama kategori
  `description` text COLLATE utf8mb4_unicode_ci,                 -- Deskripsi kategori
  `created_at` timestamp NULL DEFAULT NULL,                       -- Waktu dibuat
  `updated_at` timestamp NULL DEFAULT NULL,                       -- Waktu diupdate
  PRIMARY KEY (`id`)                                              -- ID sebagai primary key
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- DATA CATEGORIES
-- Insert data kategori menu
-- --------------------------------------------------------

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Kopi Panas', 'Berbagai macam kopi panas', '2025-11-26 08:07:52', '2025-11-26 08:07:52'),
(2, 'Kopi Dingin', 'Berbagai macam kopi dingin', '2025-11-26 08:07:52', '2025-11-26 08:07:52'),
(3, 'Non Kopi', 'Minuman non kopi', '2025-11-26 08:07:52', '2025-11-26 08:07:52'),
(4, 'Makanan', 'Makanan pendamping', '2025-11-26 08:07:52', '2025-11-26 08:07:52'),
(5, 'ice cream', 'yeay', '2025-11-26 08:44:38', '2025-11-26 08:44:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE IF NOT EXISTS `menus` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menus_category_id_foreign` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `menus`
--

INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `stock`, `is_available`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Espresso', 'Kopi hitam pekat', 15000.00, 100, 1, NULL, '2025-11-26 08:07:52', '2025-11-26 08:07:52'),
(2, 1, 'Americano', 'Espresso dengan air panas', 18000.00, 100, 1, NULL, '2025-11-26 08:07:52', '2025-11-26 08:07:52'),
(3, 1, 'Cappuccino', 'Espresso dengan susu foam', 22000.00, 79, 1, NULL, '2025-11-26 08:07:52', '2025-11-26 08:50:00'),
(4, 1, 'Latte', 'Espresso dengan susu', 25000.00, 89, 1, NULL, '2025-11-26 08:07:52', '2025-11-26 08:50:00'),
(5, 2, 'Es Kopi Susu', 'Kopi susu dingin', 20000.00, 118, 1, NULL, '2025-11-26 08:07:52', '2025-11-26 09:04:46'),
(6, 2, 'Ice Latte', 'Latte dingin', 28000.00, 85, 1, NULL, '2025-11-26 08:07:52', '2025-11-26 08:07:52'),
(7, 2, 'Cold Brew', 'Kopi seduh dingin', 30000.00, 60, 1, NULL, '2025-11-26 08:07:52', '2025-11-26 08:07:52'),
(8, 3, 'Chocolate', 'Coklat panas/dingin', 22000.00, 70, 1, NULL, '2025-11-26 08:07:52', '2025-11-26 08:07:52'),
(9, 3, 'Matcha Latte', 'Teh hijau dengan susu', 25000.00, 48, 1, NULL, '2025-11-26 08:07:52', '2025-11-26 09:04:46'),
(10, 4, 'Croissant', 'Roti croissant', 18000.00, 28, 1, NULL, '2025-11-26 08:07:52', '2025-11-26 09:04:46'),
(11, 4, 'Sandwich', 'Sandwich isi ayam', 28000.00, 23, 1, NULL, '2025-11-26 08:07:52', '2025-11-26 09:04:46'),
(13, 5, 'vanilla', 'ahhay', 100000.00, 97, 0, NULL, '2025-11-26 08:45:24', '2025-11-26 08:55:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_04_051853_create_personal_access_tokens_table', 1),
(5, '2025_11_08_115410_create_categories_table', 1),
(6, '2025_11_19_181958_create_menus_table', 1),
(7, '2025_11_19_193229_create_orders_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('dine-in','takeaway') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dine-in',
  `table_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','qris','transfer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `status` enum('pending','processing','ready','completed','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `customer_name`, `type`, `table_no`, `total_amount`, `payment_amount`, `payment_method`, `status`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'ORD-1764172071', 'bahlil', 'dine-in', NULL, 100000.00, 200000.00, 'cash', 'ready', 2, '2025-11-26 08:47:51', '2025-11-26 08:53:56'),
(2, 'ORD-1764172200', 'bahlil', 'dine-in', NULL, 47000.00, 50000.00, 'cash', 'pending', 2, '2025-11-26 08:50:00', '2025-11-26 08:50:00'),
(3, 'ORD-1764172520', 'bahlil', 'dine-in', NULL, 100000.00, 200000.00, 'cash', 'pending', 3, '2025-11-26 08:55:20', '2025-11-26 08:55:20'),
(4, 'ORD-1764173013', 'inobahlil', 'dine-in', NULL, 91000.00, 100000.00, 'cash', 'ready', 1, '2025-11-26 09:03:33', '2025-11-26 09:04:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_details`
--

DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `menu_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_details_order_id_foreign` (`order_id`),
  KEY `order_details_menu_id_foreign` (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `menu_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 13, 1, 100000.00, '2025-11-26 08:47:51', '2025-11-26 08:47:51'),
(2, 2, 3, 1, 22000.00, '2025-11-26 08:50:00', '2025-11-26 08:50:00'),
(3, 2, 4, 1, 25000.00, '2025-11-26 08:50:00', '2025-11-26 08:50:00'),
(4, 3, 13, 1, 100000.00, '2025-11-26 08:55:20', '2025-11-26 08:55:20'),
(5, 4, 5, 1, 20000.00, '2025-11-26 09:03:33', '2025-11-26 09:03:33'),
(6, 4, 9, 1, 25000.00, '2025-11-26 09:03:34', '2025-11-26 09:03:34'),
(7, 4, 10, 1, 18000.00, '2025-11-26 09:03:34', '2025-11-26 09:03:34'),
(8, 4, 11, 1, 28000.00, '2025-11-26 09:03:34', '2025-11-26 09:03:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('wrf9sg7WZSVOPtQJbgHfp6X59TxPfueOtsY9mUKL', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM1pDSlFjZ1h5RnRoNHVuTHpPOXpCOTIwUklBVGFRb1ZITUx0Z3FpYyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9raXRjaGVuIjtzOjU6InJvdXRlIjtzOjEzOiJraXRjaGVuLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1764173517);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','cashier','kitchen') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cashier',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'KopiTillDie', 'admin@sipalingkopi.com', NULL, '$2y$12$VGO1bVBoYE12kmezUdmjhu0B8Em/F3snsHvGdY24StcRpRnl.cbwO', 'admin', NULL, '2025-11-26 08:07:52', '2025-11-26 08:07:52'),
(2, 'Staff Kasir', 'kasir@sipalingkopi.com', NULL, '$2y$12$gank8M9l6O4CfSrweTdpVOi5ONOdHMUXHgxJJ.CdIjzw9C0WQ9OBC', 'cashier', NULL, '2025-11-26 08:28:48', '2025-11-26 08:28:48'),
(3, 'Staff Dapur', 'kitchen@sipalingkopi.com', NULL, '$2y$12$8FhJfTCEDqQ6JmnOTskXouqwlyE0jdb4N6p9nNUZwLP3YcNK6HIe6', 'kitchen', NULL, '2025-11-26 08:33:04', '2025-11-26 08:33:04');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
