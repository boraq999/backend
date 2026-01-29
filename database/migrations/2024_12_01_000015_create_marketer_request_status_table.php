<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketer_request_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('marketer_requests');
            $table->foreignId('marketer_id')->constrained('users');
            $table->foreignId('keeper_id')->constrained('users');
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketer_request_status');
    }
};