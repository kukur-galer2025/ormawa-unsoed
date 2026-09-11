<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $table = 'criteria';

    protected $fillable = [
        'aspect_id', 'nama_kriteria', 'tipe', 'target_value', 'keterangan', 'urutan',
    ];

    public function aspect() { return $this->belongsTo(Aspect::class); }
    public function valueLabels() { return $this->hasMany(CriteriaValueLabel::class)->orderBy('value'); }
    public function applicationScores() { return $this->hasMany(ApplicationScore::class); }

    /**
     * Helper: get the division via aspect relationship
     */
    public function getDivisionAttribute()
    {
        return $this->aspect?->division;
    }
}