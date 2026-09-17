<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\SocialiteController;

// ============================================================
// AUTH ROUTES (Guest)
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:6,1');
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:6,1');

    // Google OAuth
    Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
});

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');

use App\Http\Controllers\KatalogOrmawaController;

// Landing Page
Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            default => redirect()->route('login'),
        };
    }

    $stats = [
        'ormawa' => \App\Models\Ormawa::where('is_active', true)->count(),
        'rekrutmen' => \App\Models\Recruitment::where('status', 'dibuka')->count(),
        'mahasiswa' => \App\Models\User::where('role', 'mahasiswa')->count(),
    ];
    $openCount = \App\Models\Recruitment::where('status', 'dibuka')
        ->where('tanggal_tutup', '>=', now())->count();
    $ormawas = \App\Models\Ormawa::where('is_active', true)
        ->withCount('recruitments')->take(6)->get();

    return view('welcome', compact('stats', 'openCount', 'ormawas'));
})->name('home');

// Public catalog routes removed. Users must login to view the catalog inside the dashboard.

// ============================================================
// SUPERADMIN ROUTES
// ============================================================
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Superadmin\DashboardController::class, 'index'])->name('dashboard');

    // Ormawa CRUD
    Route::resource('ormawa', \App\Http\Controllers\Superadmin\OrmawaController::class);

    // Admin Management
    Route::get('/admin', [\App\Http\Controllers\Superadmin\AdminManagementController::class, 'index'])->name('admin.index');
    Route::get('/admin/create', [\App\Http\Controllers\Superadmin\AdminManagementController::class, 'create'])->name('admin.create');
    Route::post('/admin', [\App\Http\Controllers\Superadmin\AdminManagementController::class, 'store'])->name('admin.store');
    Route::get('/admin/{admin}/edit', [\App\Http\Controllers\Superadmin\AdminManagementController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/{admin}', [\App\Http\Controllers\Superadmin\AdminManagementController::class, 'update'])->name('admin.update');
    Route::patch('/admin/{admin}/toggle', [\App\Http\Controllers\Superadmin\AdminManagementController::class, 'toggleActive'])->name('admin.toggle');

    // Mahasiswa Management
    Route::get('/mahasiswa', [\App\Http\Controllers\Superadmin\MahasiswaManagementController::class, 'index'])->name('mahasiswa.index');
    Route::patch('/mahasiswa/{mahasiswa}/toggle', [\App\Http\Controllers\Superadmin\MahasiswaManagementController::class, 'toggle'])->name('mahasiswa.toggle');

    // Monitoring Rekrutmen (read-only)
    Route::get('/recruitment-monitoring', [\App\Http\Controllers\Superadmin\RecruitmentMonitoringController::class, 'index'])->name('recruitment-monitoring.index');
});

use App\Http\Controllers\Admin\PrestasiController;
use App\Http\Controllers\Admin\ProgramKerjaController;

// ============================================================
// ADMIN ROUTES
// ============================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin', 'ormawa.access'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Ormawa Profile Management
    Route::get('/ormawa-profile', [\App\Http\Controllers\Admin\OrmawaProfileController::class, 'edit'])->name('ormawa-profile.edit');
    Route::put('/ormawa-profile', [\App\Http\Controllers\Admin\OrmawaProfileController::class, 'update'])->name('ormawa-profile.update');

    // Prestasi
    Route::resource('prestasi', PrestasiController::class)->except(['show']);
    
    // Program Kerja
    Route::resource('proker', ProgramKerjaController::class)->except(['show']);

    // Recruitment CRUD (includes divisions)
    Route::resource('recruitment', \App\Http\Controllers\Admin\RecruitmentController::class);

    // Criteria (nested under recruitment)
    Route::get('/recruitment/{recruitment}/criteria', [\App\Http\Controllers\Admin\CriteriaController::class, 'index'])->name('criteria.index');
    Route::get('/recruitment/{recruitment}/criteria/create', [\App\Http\Controllers\Admin\CriteriaController::class, 'create'])->name('criteria.create');
    Route::post('/recruitment/{recruitment}/criteria', [\App\Http\Controllers\Admin\CriteriaController::class, 'store'])->name('criteria.store');
    Route::get('/recruitment/{recruitment}/criteria/{criterion}/edit', [\App\Http\Controllers\Admin\CriteriaController::class, 'edit'])->name('criteria.edit');
    Route::put('/recruitment/{recruitment}/criteria/{criterion}', [\App\Http\Controllers\Admin\CriteriaController::class, 'update'])->name('criteria.update');
    Route::delete('/recruitment/{recruitment}/criteria/{criterion}', [\App\Http\Controllers\Admin\CriteriaController::class, 'destroy'])->name('criteria.destroy');

    // Aspects (nested under recruitment)
    Route::get('/recruitment/{recruitment}/aspects', [\App\Http\Controllers\Admin\AspectController::class, 'index'])->name('aspect.index');
    Route::post('/recruitment/{recruitment}/aspects', [\App\Http\Controllers\Admin\AspectController::class, 'store'])->name('aspect.store');
    Route::put('/recruitment/{recruitment}/aspects/{aspect}', [\App\Http\Controllers\Admin\AspectController::class, 'update'])->name('aspect.update');
    Route::delete('/recruitment/{recruitment}/aspects/{aspect}', [\App\Http\Controllers\Admin\AspectController::class, 'destroy'])->name('aspect.destroy');

    // Applicants
    Route::get('/recruitment/{recruitment}/applicants', [\App\Http\Controllers\Admin\ApplicantController::class, 'index'])->name('applicants.index');
    Route::get('/recruitment/{recruitment}/applicants/{application}', [\App\Http\Controllers\Admin\ApplicantController::class, 'show'])->name('applicants.show');

    // Scoring
    Route::get('/recruitment/{recruitment}/scoring', [\App\Http\Controllers\Admin\ScoringController::class, 'index'])->name('scoring.index');
    Route::post('/recruitment/{recruitment}/scoring/{application}', [\App\Http\Controllers\Admin\ScoringController::class, 'store'])->name('scoring.store');

    // Profile Matching (SEPARATE MENU — not nested under recruitment)
    Route::get('/profile-matching', [\App\Http\Controllers\Admin\ProfileMatchingController::class, 'index'])->name('profile-matching.index');
    Route::get('/profile-matching/{recruitment}', [\App\Http\Controllers\Admin\ProfileMatchingController::class, 'show'])->name('profile-matching.show');
    Route::post('/profile-matching/{recruitment}/division/{division}/calculate', [\App\Http\Controllers\Admin\ProfileMatchingController::class, 'calculate'])->name('profile-matching.calculate');
    Route::get('/profile-matching/{recruitment}/division/{division}/result', [\App\Http\Controllers\Admin\ProfileMatchingController::class, 'result'])->name('profile-matching.result');
});

// ============================================================
// MAHASISWA ROUTES
// ============================================================
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Profile routes — TIDAK pakai middleware profile.complete (mencegah infinite redirect)
    Route::get('/profile', [\App\Http\Controllers\Mahasiswa\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Mahasiswa\ProfileController::class, 'update'])->name('profile.update');

    // Semua route lain — WAJIB profil lengkap
    Route::middleware('profile.complete')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Mahasiswa\DashboardController::class, 'index'])->name('dashboard');

        // Browse Recruitments (Catalog)
        Route::get('/recruitment', [\App\Http\Controllers\Mahasiswa\RecruitmentBrowseController::class, 'index'])->name('recruitment.index');
        Route::get('/recruitment/{ormawa:slug}', [\App\Http\Controllers\Mahasiswa\RecruitmentBrowseController::class, 'show'])->name('recruitment.show');

        // Apply (per division)
        Route::post('/recruitment/{recruitment}/apply', [\App\Http\Controllers\Mahasiswa\ApplicationController::class, 'store'])->name('recruitment.apply');

        // Application History
        Route::get('/applications', [\App\Http\Controllers\Mahasiswa\ApplicationController::class, 'history'])->name('applications.history');
    });
});