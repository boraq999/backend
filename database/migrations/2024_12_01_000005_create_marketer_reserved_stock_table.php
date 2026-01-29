<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketer_reserved_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketer_id')->constrained('users');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('reserved_quantity')->default(0);
            $table->timestamps();
            
            $table->unique(['marketer_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketer_reserved_stock');
    }
};