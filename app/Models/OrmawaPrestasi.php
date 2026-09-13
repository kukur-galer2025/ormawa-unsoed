<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrmawaPrestasi extends Model
{
    protected $table = 'prestasi_ormawa';
    protected $fillable = ['ormawa_id', 'judul', 'deskripsi', 'tahun', 'foto'];

    public function ormawa()
    {
        return $this->belongsTo(Ormawa::class);
    }
}
