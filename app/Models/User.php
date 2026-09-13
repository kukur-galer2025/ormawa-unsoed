<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active', 'google_id', 'google_avatar',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isSuperadmin(): bool { return $this->role === 'superadmin'; }
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isMahasiswa(): bool { return $this->role 
        === 'mahasiswa'; }

    public function mahasiswaProfile()
    {
        return $this->hasOne(MahasiswaProfile::class);
    }

    public function ormawaAdmins()
    {
        return $this->hasMany(OrmawaAdmin::class);
    }

    public function ormawas()
    {
        return $this->belongsToMany(Ormawa::class, 'admin_ormawa');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}