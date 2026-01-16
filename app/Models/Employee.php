<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'employee_number', 'first_name', 'last_name', 'id_number', 'phone', 'email',
        'date_of_birth', 'gender', 'address', 'department_id', 'position_id',
        'date_of_hire', 'employment_status', 'employment_type', 'pay_type',
        'basic_salary', 'hourly_rate', 'bank_name', 'bank_account', 'bank_branch',
        'kra_pin', 'nssf_number', 'nhif_number', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'date_of_hire' => 'date',
            'basic_salary' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function department() { return $this->belongsTo(Department::class); }
    public function position() { return $this->belongsTo(Position::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
    public function payrolls() { return $this->hasMany(Payroll::class); }
    
    public function shiftSchedules()
    {
        return $this->hasMany(ShiftSchedule::class);
    }

    // Get current/upcoming shift schedule
    public function todayShift()
    {
        return $this->shiftSchedules()->where('schedule_date', today())->with('shift')->first();
    }

    public function upcomingShifts($days = 7)
    {
        return $this->shiftSchedules()
            ->where('schedule_date', '>=', today())
            ->where('schedule_date', '<=', today()->addDays($days))
            ->with('shift')
            ->orderBy('schedule_date')
            ->get();
    }

    public function monthShifts($month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;
        
        return $this->shiftSchedules()
            ->whereMonth('schedule_date', $month)
            ->whereYear('schedule_date', $year)
            ->with('shift')
            ->orderBy('schedule_date')
            ->get();
    }

    public function getFullNameAttribute() { return "{$this->first_name} {$this->last_name}"; }
}
