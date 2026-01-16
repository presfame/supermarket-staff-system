<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'name', 'start_time', 'end_time', 'hours', 'description', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'hours' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function schedules()
    {
        return $this->hasMany(ShiftSchedule::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Get employees scheduled for this shift today
    public function todayEmployees()
    {
        return $this->schedules()->where('schedule_date', today())->with('employee');
    }
}
