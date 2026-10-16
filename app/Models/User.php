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
        'name', 'email', 'password', 'role', 'is_active',
        'google_id', 'google_avatar', 'fakultas_id',
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

    // -------------------------------------------------------
    // Role helpers
    // -------------------------------------------------------
    public function isSuperadmin(): bool    { return $this->role === 'superadmin'; }
    public function isAdmin(): bool         { return $this->role === 'admin'; }
    public function isMahasiswa(): bool     { return $this->role === 'mahasiswa'; }
    public function isWakilRektor(): bool   { return $this->role === 'wakil_rektor'; }
    public function isWakilDekan(): bool    { return $this->role === 'wakil_dekan'; }

    public function isPimpinan(): bool
    {
        return in_array($this->role, ['wakil_rektor', 'wakil_dekan']);
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------
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

    public function ormawa()
    {
        return $this->hasOneThrough(
            Ormawa::class, OrmawaAdmin::class,
            'user_id', 'id', 'id', 'ormawa_id'
        );
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Relation to Fakultas — used by wakil_dekan to define their scope.
     */
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }
}