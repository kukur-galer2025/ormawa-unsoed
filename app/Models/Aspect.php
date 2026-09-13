<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aspect extends Model
{
    protected $table = 'aspek';
    protected $fillable = [
        'recruitment_division_id', 'nama', 'bobot',
        'cf_percentage', 'sf_percentage', 'urutan',
    ];

    protected $casts = [
        'bobot' => 'decimal:2',
        'cf_percentage' => 'decimal:2',
        'sf_percentage' => 'decimal:2',
    ];

    public function division() { return $this->belongsTo(RecruitmentDivision::class, 'recruitment_division_id'); }
    public function criteria() { return $this->hasMany(Criteria::class)->orderBy('urutan'); }
    public function coreCriteria() { return $this->criteria()->where('tipe', 'core'); }
    public function secondaryCriteria() { return $this->criteria()->where('tipe', 'secondary'); }
}
