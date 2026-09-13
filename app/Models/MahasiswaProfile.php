<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaProfile extends Model
{
    protected $table = 'profil_mahasiswa';
    protected $fillable = [
        'user_id', 'nim', 'fakultas', 'jurusan', 'angkatan', 'no_hp', 'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}