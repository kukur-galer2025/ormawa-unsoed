<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentDivision extends Model
{
    protected $table = 'divisi_rekrutmen';
    protected $fillable = [
        'recruitment_id', 'nama', 'deskripsi', 'kuota', 'is_finalized',
    ];

    protected $casts = [
        'is_finalized' => 'boolean',
    ];

    public function recruitment() { return $this->belongsTo(Recruitment::class); }
    public function aspects() { return $this->hasMany(Aspect::class)->orderBy('urutan'); }
    public function applications() { return $this->hasMany(Application::class); }
    public function profileMatchingResults() { return $this->hasMany(ProfileMatchingResult::class); }

    /**
     * Get all criteria across all aspects (for convenience).
     */
    public function allCriteria()
    {
        return Criteria::whereIn('aspect_id', $this->aspects()->pluck('id'));
    }
}
