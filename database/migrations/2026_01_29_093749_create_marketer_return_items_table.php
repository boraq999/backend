<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketer_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_request_id')->constrained('marketer_return_requests')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketer_return_items');
    }
};
