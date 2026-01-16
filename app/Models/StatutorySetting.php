<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatutorySetting extends Model
{
    protected $fillable = [
        'setting_name',
        'display_name',
        'category',
        'setting_value',
        'description',
        'effective_date',
        'is_active'
    ];

    protected function casts(): array
    {
        return [
            'setting_value' => 'decimal:4',
            'effective_date' => 'date',
            'is_active' => 'boolean',
        ];
    }
}
