<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketer_requests', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'waiting_documentation'])->default('pending')->after('marketer_id');
        });
    }

    public function down(): void
    {
        Schema::table('marketer_requests', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};