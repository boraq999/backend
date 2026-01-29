<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_return_confirmation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained('sales_returns');
            $table->foreignId('keeper_id')->constrained('users');
            $table->string('stamped_image', 255)->nullable();
            $table->timestamp('confirmed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_return_confirmation');
    }
};