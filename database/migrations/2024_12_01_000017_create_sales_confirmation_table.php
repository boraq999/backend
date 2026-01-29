<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_confirmation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_invoice_id')->constrained('sales_invoices');
            $table->foreignId('keeper_id')->constrained('users');
            $table->string('stamped_invoice_image', 255);
            $table->timestamp('confirmed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_confirmation');
    }
};