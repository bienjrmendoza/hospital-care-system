<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Vital;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_DOCTOR = 'doctor';
    public const ROLE_USER = 'user';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'profile_image',
        'password',
        'role',
        'birthday',
        'chief_complaint'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function doctorProfile(): HasOne
    {
        return $this->hasOne(DoctorProfile::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'doctor_id');
    }

    public function scheduleRequests(): HasMany
    {
        return $this->hasMany(ScheduleRequest::class);
    }

    public function vitals()
    {
        return $this->hasMany(Vital::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isDoctor(): bool
    {
        return $this->role === self::ROLE_DOCTOR;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }
}
