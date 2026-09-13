<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'pendaftaran';
    protected $fillable = [
        'recruitment_id', 'recruitment_division_id', 'user_id', 'motivasi', 'berkas_pendukung', 'status',
    ];

    public function recruitment() { return $this->belongsTo(Recruitment::class); }
    public function division() { return $this->belongsTo(RecruitmentDivision::class, 'recruitment_division_id'); }
    public function user() { return $this->belongsTo(User::class); }
    public function scores() { return $this->hasMany(ApplicationScore::class); }
    public function profileMatchingResult() { return $this->hasOne(ProfileMatchingResult::class); }
}