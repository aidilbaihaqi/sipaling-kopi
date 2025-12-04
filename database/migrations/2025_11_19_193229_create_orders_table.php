<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel Orders (Gabungan semua kebutuhan)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // ORD-XXXX
            $table->string('customer_name');          // Nama Pelanggan
            
            // Tambahan dari file yang dihapus tadi
            $table->enum('type', ['dine-in', 'takeaway'])->default('dine-in'); 
            $table->string('table_no')->nullable(); 

            $table->decimal('total_amount', 10, 2);   
            $table->decimal('payment_amount', 10, 2); 
            $table->enum('payment_method', ['cash', 'qris', 'transfer'])->default('cash');
            $table->enum('status', ['pending', 'processing', 'ready', 'completed', 'canceled'])->default('pending');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        // 2. Tabel Order Details
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('menu_id')->constrained('menus');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');
    }
};