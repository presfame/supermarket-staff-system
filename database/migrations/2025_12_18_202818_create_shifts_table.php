<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('hours', 4, 2)->default(8);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Shift schedule - assigns employees to shifts on specific dates
        Schema::create('shift_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('shift_id')->constrained()->onDelete('cascade');
            $table->date('schedule_date');
            $table->enum('status', ['Scheduled', 'Confirmed', 'Completed', 'Absent'])->default('Scheduled');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->unique(['employee_id', 'schedule_date']); // One shift per employee per day
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_schedules');
        Schema::dropIfExists('shifts');
    }
};
