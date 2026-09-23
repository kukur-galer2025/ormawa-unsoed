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
    public function recruitments() { return $this->hasMany(Recruitment::class); }
    public function prestasis() { return $this->hasMany(OrmawaPrestasi::class); }
    public function programKerjas() { return $this->hasMany(OrmawaProgramKerja::class); }
    public function fakultasRel() { return $this->belongsTo(Fakultas::class, 'fakultas_id'); }
    public function jurusanRel() { return $this->belongsTo(Jurusan::class, 'jurusan_id'); }
}