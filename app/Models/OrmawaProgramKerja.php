<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrmawaProgramKerja extends Model
{
    protected $fillable = ['ormawa_id', 'nama', 'deskripsi', 'foto'];

    public function ormawa()
    {
        return $this->belongsTo(Ormawa::class);
    }
}
