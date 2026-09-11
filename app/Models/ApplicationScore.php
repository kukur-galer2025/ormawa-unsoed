<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationScore extends Model
{
    protected $fillable = [
        'application_id', 'criteria_id', 'actual_value', 'gap', 'bobot_gap',
    ];

    protected $casts = [
        'bobot_gap' => 'decimal:1',
    ];

    public function application() { return $this->belongsTo(Application::class); }
    public function criteria() { return $this->belongsTo(Criteria::class); }
}