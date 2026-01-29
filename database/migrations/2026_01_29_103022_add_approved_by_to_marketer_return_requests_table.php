<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketer_return_requests', function (Blueprint $table) {
            $table->foreignId('approved_by')->nullable()->after('keeper_id')->constrained('users');
            $table->foreignId('documented_by')->nullable()->after('approved_by')->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::table('marketer_return_requests', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['documented_by']);
            $table->dropColumn(['approved_by', 'documented_by']);
        });
    }
};
