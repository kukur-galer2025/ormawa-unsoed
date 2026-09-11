<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model
{
    protected $fillable = [
        'ormawa_id', 'judul', 'deskripsi', 'persyaratan',
        'tanggal_buka', 'tanggal_tutup', 'status',
    ];

    protected $casts = [
        'tanggal_buka' => 'date',
        'tanggal_tutup' => 'date',
    ];

    public function ormawa() { return $this->belongsTo(Ormawa::class); }
    public function divisions() { return $this->hasMany(RecruitmentDivision::class); }
    public function applications() { return $this->hasMany(Application::class); }

    public function getStatusAttribute($value)
    {
        if ($value === 'dibuka' && $this->tanggal_tutup && $this->tanggal_tutup < now()->startOfDay()) {
            return 'ditutup';
        }
        return $value;
    }

    public function isOpen(): bool
    {
        return $this->status === 'dibuka' && now()->between($this->tanggal_buka, $this->tanggal_tutup->endOfDay());
    }
}