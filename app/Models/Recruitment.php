<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model
{
    protected $table = 'rekrutmen';
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

    public function scopeReallyOpen($query)
    {
        return $query->where('status', 'dibuka')
                     ->where(function ($q) {
                         $q->whereNull('tanggal_tutup')
                           ->orWhere('tanggal_tutup', '>=', now()->startOfDay());
                     });
    }

    public function scopeReallyClosed($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'ditutup')
              ->orWhere(function ($q2) {
                  $q2->where('status', 'dibuka')
                     ->whereNotNull('tanggal_tutup')
                     ->where('tanggal_tutup', '<', now()->startOfDay());
              });
        });
    }

    /**
     * Mengembalikan pesan spesifik mengapa rekrutmen tidak bisa dilamar.
     * Dipakai di controller sebagai pengganti pengecekan manual tanggal.
     */
    public function getClosureMessageAttribute(): ?string
    {
        if ($this->status !== 'dibuka') {
            return 'Rekrutmen ini sedang tidak aktif atau belum dipublikasikan sepenuhnya.';
        }

        if (now()->startOfDay() < $this->tanggal_buka->startOfDay()) {
            return 'Rekrutmen ini belum dibuka. Pendaftaran baru dimulai pada ' . $this->tanggal_buka->format('d M Y') . '.';
        }

        if (now()->startOfDay() > $this->tanggal_tutup->endOfDay()) {
            return 'Rekrutmen ini sudah ditutup sejak ' . $this->tanggal_tutup->format('d M Y') . '.';
        }

        return null; // Rekrutmen sedang buka
    }
}