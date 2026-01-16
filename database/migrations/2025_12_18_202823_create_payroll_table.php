<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->integer('pay_period_month');
            $table->integer('pay_period_year');

            // Days tracking
            $table->integer('days_worked')->default(0);
            $table->integer('days_absent')->default(0);
            $table->integer('days_leave')->default(0);
            
            // Earnings
            $table->decimal('basic_salary', 12, 2);
            $table->decimal('overtime_hours', 6, 2)->default(0);
            $table->decimal('overtime_pay', 12, 2)->default(0);
            $table->decimal('gross_salary', 12, 2);

            // Statutory deductions
            $table->decimal('nssf_deduction', 10, 2)->default(0);
            $table->decimal('shif_deduction', 10, 2)->default(0);
            $table->decimal('paye_deduction', 10, 2)->default(0);
            $table->decimal('housing_levy', 10, 2)->default(0);
            $table->decimal('other_deductions', 10, 2)->default(0);
            $table->decimal('total_deductions', 12, 2);
            $table->decimal('net_salary', 12, 2);

            $table->enum('status', ['Draft', 'Processed', 'Paid'])->default('Draft');
            $table->date('payment_date')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
            $table->unique(['employee_id', 'pay_period_month', 'pay_period_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll');
    }
};
