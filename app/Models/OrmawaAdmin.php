<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrmawaAdmin extends Model
{
    protected $fillable = ['user_id', 'ormawa_id'];

    public function user() { return $this->belongsTo(User::class); }
    public function ormawa() { return $this->belongsTo(Ormawa::class); }
}