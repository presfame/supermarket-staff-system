<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $table = 'payroll';
    
    protected $fillable = [
        'employee_id', 'pay_period_month', 'pay_period_year',
        'days_worked', 'days_absent', 'days_leave',
        'basic_salary', 'overtime_hours', 'overtime_pay', 'gross_salary',
        'nssf_deduction', 'shif_deduction', 'paye_deduction', 'housing_levy',
        'other_deductions', 'total_deductions', 'net_salary', 
        'status', 'payment_date', 'processed_by'
    ];

    protected function casts(): array
    {
        return [
            'basic_salary' => 'decimal:2',
            'gross_salary' => 'decimal:2',
            'net_salary' => 'decimal:2',
            'nssf_deduction' => 'decimal:2',
            'shif_deduction' => 'decimal:2',
            'paye_deduction' => 'decimal:2',
            'housing_levy' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function employee() { return $this->belongsTo(Employee::class); }
    public function processor() { return $this->belongsTo(User::class, 'processed_by'); }
}
