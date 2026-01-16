<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statutory_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_name', 100)->unique();
            $table->string('display_name', 100)->nullable();
            $table->string('category', 50)->default('general');
            $table->decimal('setting_value', 12, 4);
            $table->text('description')->nullable();
            $table->date('effective_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statutory_settings');
    }
};
