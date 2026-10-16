<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ormawa extends Model
{
    protected $table = 'ormawa';
    protected $fillable = [
        'nama',
        'slug',
        'tingkat',
        'fakultas_id',
        'jurusan_id',
        'deskripsi',
        'visi',
        'misi',
        'logo',
        'cover_photo',
        'kontak_email',
        'kontak_instagram',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Ormawa $ormawa) {
            if (empty($ormawa->slug)) {
                $ormawa->slug = Str::slug($ormawa->nama);
            }
        });
    }

    public function ormawaAdmins() { return $this->hasMany(OrmawaAdmin::class); }
    public function admins() { return $this->belongsToMany(User::class, 'admin_ormawa'); }
    public function admin()
    {
        return $this->hasOneThrough(
            User::class, OrmawaAdmin::class,
            'ormawa_id', 'id', 'id', 'user_id'
        );
    }
    public function recruitments() { return $this->hasMany(Recruitment::class); }
    public function prestasis() { return $this->hasMany(OrmawaPrestasi::class); }
    public function programKerjas() { return $this->hasMany(OrmawaProgramKerja::class); }
    public function fakultasRel() { return $this->belongsTo(Fakultas::class, 'fakultas_id'); }
    public function jurusanRel() { return $this->belongsTo(Jurusan::class, 'jurusan_id'); }

    /**
     * Scope: limit ormawa visible to a given user based on their role.
     *
     * - wakil_rektor : sees ALL ormawa
     * - wakil_dekan  : sees ormawa belonging to their faculty (via fakultas_id or
     *                  via jurusan.fakultas_id). University-level ormawa excluded.
     * - others       : no restriction (caller must apply their own logic)
     */
    public function scopeVisibleTo($query, User $user)
    {
        if ($user->isWakilRektor()) {
            // No additional restriction
            return $query;
        }

        if ($user->isWakilDekan()) {
            $fakultasId = $user->fakultas_id;

            return $query->where(function ($q) use ($fakultasId) {
                // Ormawa directly linked to this faculty
                $q->where('fakultas_id', $fakultasId)
                  // OR Ormawa linked to a jurusan that belongs to this faculty
                  ->orWhereHas('jurusanRel', function ($q2) use ($fakultasId) {
                      $q2->where('fakultas_id', $fakultasId);
                  });
            })
            // Exclude university-level ormawa (tingkat = 'Universitas')
            ->where('tingkat', '!=', 'Universitas');
        }

        return $query;
    }
}