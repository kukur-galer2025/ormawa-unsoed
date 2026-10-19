<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model
{
    protected $table = 'rekrutmen';
    protected $fillable = [
        'ormawa_id', 'judul', 'deskripsi', 'persyaratan',
        'tanggal_buka', 'tanggal_tutup', 'status', 'is_announced',
        'pesan_setelah_mendaftar',
    ];

    protected $casts = [
        'tanggal_buka' => 'date',
        'tanggal_tutup' => 'date',
        'is_announced' => 'boolean',
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
        if ($this->status === 'ditutup' || ($this->tanggal_tutup && now()->startOfDay() > $this->tanggal_tutup->endOfDay())) {
            return 'Pendaftaran untuk rekrutmen ini sudah ditutup.';
        }

        if ($this->status !== 'dibuka') {
            return 'Rekrutmen ini sedang tidak aktif atau belum dipublikasikan sepenuhnya.';
        }

        if ($this->tanggal_buka && now()->startOfDay() < $this->tanggal_buka->startOfDay()) {
            return 'Rekrutmen ini belum dibuka. Pendaftaran baru dimulai pada ' . $this->tanggal_buka->format('d M Y') . '.';
        }

        return null; // Rekrutmen sedang buka
    }

    /**
     * Scope: limit recruitments visible to a given user based on their role.
     * Delegates to the Ormawa's scopeVisibleTo for consistency.
     *
     * - wakil_rektor : all recruitments
     * - wakil_dekan  : only recruitments from ormawa in their faculty
     */
    public function scopeVisibleTo($query, User $user)
    {
        if ($user->isWakilRektor()) {
            return $query;
        }

        if ($user->isWakilDekan()) {
            $fakultasId = $user->fakultas_id;

            return $query->whereHas('ormawa', function ($q) use ($fakultasId) {
                $q->where(function ($q2) use ($fakultasId) {
                    $q2->where('fakultas_id', $fakultasId)
                       ->orWhereHas('jurusanRel', function ($q3) use ($fakultasId) {
                           $q3->where('fakultas_id', $fakultasId);
                       });
                })
                ->where('tingkat', '!=', 'Universitas');
            });
        }

        return $query;
    }
}