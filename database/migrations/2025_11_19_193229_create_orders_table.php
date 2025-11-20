<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabel Utama Transaksi
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name'); // Nama Pelanggan
            $table->decimal('total_price', 10, 2); // Total Tagihan
            $table->decimal('payment_amount', 10, 2); // Uang Dibayar
            $table->enum('payment_method', ['cash', 'qris', 'transfer'])->default('cash');
            $table->enum('status', ['pending', 'processing', 'ready', 'completed', 'canceled'])->default('pending'); // Default pending agar masuk Kitchen
            $table->foreignId('user_id')->constrained('users'); // Kasir yang menangani
            $table->timestamps();
        });

        // Tabel Detail Item (Menu yang dibeli)
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('menu_id')->constrained('menus'); // Relasi ke Menu
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // Harga saat transaksi (snapshot)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');
    }
};