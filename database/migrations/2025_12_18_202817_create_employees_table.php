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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number', 50)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('id_number', 20)->unique();
            $table->string('phone', 20);
            $table->string('email', 255)->unique()->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->text('address')->nullable();

            $table->foreignId('department_id')->constrained();
            $table->foreignId('position_id')->constrained();
            $table->date('date_of_hire');
            $table->enum('employment_status', ['Active', 'Inactive', 'Suspended', 'Terminated'])->default('Active');
            $table->enum('employment_type', ['Full-time', 'Part-time', 'Contract'])->default('Full-time');

            $table->enum('pay_type', ['Hourly', 'Monthly'])->default('Monthly');
            $table->decimal('basic_salary', 12, 2);
            $table->decimal('hourly_rate', 10, 2)->nullable();

            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account', 50)->nullable();
            $table->string('bank_branch', 100)->nullable();

            $table->string('kra_pin', 20)->nullable();
            $table->string('nssf_number', 20)->nullable();
            $table->string('nhif_number', 20)->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
