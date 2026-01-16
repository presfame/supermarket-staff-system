<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'hr', 'supervisor', 'employee'])->default('employee')->after('password');
            $table->foreignId('employee_id')->nullable()->after('role')->constrained()->onDelete('set null');
            $table->boolean('is_active')->default(true)->after('employee_id');
            $table->timestamp('last_login')->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'employee_id', 'is_active', 'last_login']);
        });
    }
};
