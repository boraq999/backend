<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_confirmation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('marketer_requests');
            $table->foreignId('keeper_id')->constrained('users');
            $table->string('signed_image', 255)->nullable();
            $table->timestamp('confirmed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_confirmation');
    }
};