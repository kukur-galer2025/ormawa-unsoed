<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $table = 'kriteria';

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
        if ($this->relationLoaded('aspect') && $this->aspect) {
            return $this->aspect->relationLoaded('division') 
                ? $this->aspect->division 
                : $this->aspect->division()->first();
        }
        return $this->aspect()->with('division')->first()?->division;
    }
}