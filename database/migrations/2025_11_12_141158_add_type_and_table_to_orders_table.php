<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddTypeAndTableToOrdersTable extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'type')) {
                $table->enum('type', ['dine-in', 'takeaway'])->default('takeaway')->after('user_id');
            }

            if (!Schema::hasColumn('orders', 'table_no')) {
                $table->string('table_no')->nullable()->after('type');
            }

            if (!Schema::hasColumn('orders', 'status_temp')) {
                $table->enum('status_temp', ['pending', 'processing', 'ready', 'cancelled'])->default('pending')->after('table_no');
            }
        });

        DB::table('orders')->select('id', 'status')->orderBy('id')->chunk(100, function ($orders) {
            foreach ($orders as $order) {
                $newStatus = match ($order->status) {
                    'completed' => 'ready',
                    'pending' => 'pending',
                    'processing' => 'processing',
                    'cancelled' => 'cancelled',
                    default => 'pending',
                };
                DB::table('orders')->where('id', $order->id)->update(['status_temp' => $newStatus]);
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('status_temp', 'status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['type', 'table_no']);
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending')->change();
        });
    }
}
