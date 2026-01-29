<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketer_return_requests', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('marketer_id')->constrained('users');
            $table->foreignId('keeper_id')->nullable()->constrained('users');
            $table->enum('status', ['pending', 'approved', 'rejected', 'documented'])->default('pending');
            $table->string('stamped_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketer_return_requests');
    }
};
