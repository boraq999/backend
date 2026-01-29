<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketer_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('withdrawal_request_id')->constrained('marketer_withdrawal_requests');
            $table->foreignId('marketer_id')->constrained('users');
            $table->decimal('amount', 12, 2);
            $table->foreignId('keeper_id')->constrained('users');
            $table->string('signed_receipt_image', 255);
            $table->timestamp('confirmed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketer_withdrawals');
    }
};