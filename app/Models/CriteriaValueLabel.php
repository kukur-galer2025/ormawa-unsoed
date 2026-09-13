<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriteriaValueLabel extends Model
{
    protected $table = 'label_nilai_kriteria';
    protected $fillable = ['criteria_id', 'value', 'label'];
    public $timestamps = false;

    public function criteria() { return $this->belongsTo(Criteria::class); }
}
