<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UsersController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [UsersController::class, 'login'])->name('login');
Route::post('/login', [UsersController::class, 'doLogin'])->name('do_login');

Route::get('/register', [UsersController::class, 'register'])->name('register');
Route::post('/register', [UsersController::class, 'doRegister'])->name('do_register');

Route::get('/logout', [UsersController::class, 'doLogout'])->name('do_logout');

Route::get('/auth/google', [UsersController::class, 'redirectToGoogle'])->name('login_with_google');
Route::get('/auth/google/callback', [UsersController::class, 'handleGoogleCallback']);

// Campaigns
use App\Http\Controllers\Web\CampaignsController;
Route::get('/campaigns', [CampaignsController::class, 'index'])->name('campaigns_list');
Route::get('/campaigns/create', [CampaignsController::class, 'create'])->name('campaigns_create');
Route::post('/campaigns/store', [CampaignsController::class, 'store'])->name('campaigns_store');
Route::get('/campaigns/edit/{campaign}', [CampaignsController::class, 'edit'])->name('campaigns_edit');
Route::post('/campaigns/update/{campaign}', [CampaignsController::class, 'update'])->name('campaigns_update');
Route::post('/campaigns/delete/{campaign}', [CampaignsController::class, 'destroy'])->name('campaigns_delete');

// Reports
use App\Http\Controllers\Web\ReportsController;
Route::get('/campaigns/reports/impact', [ReportsController::class, 'impactReport'])->name('reports_impact');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UsersController::class, 'profile'])->name('profile');

    // Donations
    Route::get('/donations/create/{campaign}', [\App\Http\Controllers\Web\DonationsController::class, 'create'])->name('donations_create');
    Route::post('/donations/process/{campaign}', [\App\Http\Controllers\Web\DonationsController::class, 'process'])->name('donations_process');

    // Volunteers (Auth required to register or create tasks)
    Route::get('/volunteers/create', [\App\Http\Controllers\Web\VolunteerController::class, 'create'])->name('volunteers_create');
    Route::post('/volunteers/store', [\App\Http\Controllers\Web\VolunteerController::class, 'store'])->name('volunteers_store');
    Route::post('/volunteers/register/{task}', [\App\Http\Controllers\Web\VolunteerController::class, 'register'])->name('volunteers_register');
});

// Volunteer List (Public)
Route::get('/volunteer', [\App\Http\Controllers\Web\VolunteerController::class, 'index'])->name('volunteers_list');

