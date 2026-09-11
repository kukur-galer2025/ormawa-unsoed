<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriteriaValueLabel extends Model
{
    protected $fillable = ['criteria_id', 'value', 'label'];
    public $timestamps = false;

    public function criteria() { return $this->belongsTo(Criteria::class); }
}
