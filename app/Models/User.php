<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'employee_id', 'is_active'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function employee() { return $this->belongsTo(Employee::class); }
    public function isAdmin() { return $this->role === 'admin'; }
    public function isHR() { return $this->role === 'hr'; }
    public function isSupervisor() { return $this->role === 'supervisor'; }
}
