<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileMatchingResult extends Model
{
    protected $table = 'hasil_profile_matching';
    protected $fillable = [
        'application_id', 'recruitment_division_id', 'detail_per_aspek', 'total_score', 'ranking',
    ];

    protected $casts = [
        'detail_per_aspek' => 'array',
        'total_score' => 'decimal:5',
    ];

    public function application() { return $this->belongsTo(Application::class); }
    public function division() { return $this->belongsTo(RecruitmentDivision::class, 'recruitment_division_id'); }
}