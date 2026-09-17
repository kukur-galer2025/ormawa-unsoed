<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $table = 'fakultas';
    protected $fillable = ['nama_fakultas'];

    public function jurusans()
    {
        return $this->hasMany(Jurusan::class);
    }
}
