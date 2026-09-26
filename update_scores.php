<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$division = \App\Models\RecruitmentDivision::where('nama', 'Kementerian Dalam Negeri')->first();

$andi = \App\Models\Application::where('recruitment_division_id', $division->id)->whereHas('user', function($q) { $q->where('name', 'like', '%Andi%'); })->first();
$dian = \App\Models\Application::where('recruitment_division_id', $division->id)->whereHas('user', function($q) { $q->where('name', 'like', '%Dian%'); })->first();

// Use exact criterion IDs for K1 to K5 for Kementerian Dalam Negeri
// IDs are 1 to 5 based on the previous output
$cIds = [1, 2, 3, 4, 5];

$andiScores = [
    $cIds[0] => 4, // Intelektual
    $cIds[1] => 2, // Problem Solving
    $cIds[2] => 4, // Sikap
    $cIds[3] => 5, // Bijaksana
    $cIds[4] => 4, // Tanggung Jawab
];

$dianScores = [
    $cIds[0] => 3,
    $cIds[1] => 5,
    $cIds[2] => 4,
    $cIds[3] => 5,
    $cIds[4] => 4,
];

foreach ($andiScores as $cId => $val) {
    \App\Models\ApplicationScore::updateOrCreate(
        ['application_id' => $andi->id, 'criteria_id' => $cId],
        ['actual_value' => $val]
    );
}

foreach ($dianScores as $cId => $val) {
    \App\Models\ApplicationScore::updateOrCreate(
        ['application_id' => $dian->id, 'criteria_id' => $cId],
        ['actual_value' => $val]
    );
}

\App\Models\ProfileMatchingResult::where('recruitment_division_id', $division->id)->delete();

echo "Skor Andi dan Dian berhasil diupdate di database. Silakan klik Hitung Ulang.\n";
