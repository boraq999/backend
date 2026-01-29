<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_stock_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('invoice_type', ['factory', 'marketer_request', 'sales_return']);
            $table->integer('invoice_id');
            $table->foreignId('keeper_id')->constrained('users');
            $table->enum('action', ['add', 'withdraw', 'return']);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_stock_logs');
    }
};