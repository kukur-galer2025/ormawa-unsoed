<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaProfile extends Model
{
    protected $table = 'profil_mahasiswa';
    protected $fillable = [
        'user_id',
        'nim',
        'fakultas_id',
        'jurusan_id',
        'angkatan',
        'no_hp',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fakultasRel()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    public function jurusanRel()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
}