<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\StripeWebhookController;
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

// Forgot Password
Route::get('/forgot-password', [UsersController::class, 'forgotPassword'])->name('forgot_password');
Route::post('/forgot-password', [UsersController::class, 'doForgotPassword'])->name('forgot_password_process');
Route::get('/reset-password/{token}', [UsersController::class, 'resetPassword'])->name('reset_password');
Route::post('/reset-password', [UsersController::class, 'doResetPassword'])->name('reset_password_process');

// Campaigns
use App\Http\Controllers\Web\CampaignsController;
Route::get('/campaigns', [CampaignsController::class, 'index'])->name('campaigns_list');
Route::get('/campaigns/create', [CampaignsController::class, 'create'])->name('campaigns_create');
Route::post('/campaigns/store', [CampaignsController::class, 'store'])->name('campaigns_store');
Route::get('/campaigns/edit/{campaign}', [CampaignsController::class, 'edit'])->name('campaigns_edit');
Route::post('/campaigns/update/{campaign}', [CampaignsController::class, 'update'])->name('campaigns_update');
Route::post('/campaigns/delete/{campaign}', [CampaignsController::class, 'destroy'])->name('campaigns_delete');
Route::get('/campaigns/{campaign}', [CampaignsController::class, 'show'])->name('campaigns_show');

// Reports
use App\Http\Controllers\Web\ReportsController;
Route::get('/reports/impact', [ReportsController::class, 'impactReport'])->name('reports_impact');
Route::get('/reports/donations', [ReportsController::class, 'allDonations'])->name('reports_donations');
Route::get('/reports/volunteers', [ReportsController::class, 'allVolunteers'])->name('reports_volunteers');
Route::post('/reports/volunteers/remove/{registration}', [ReportsController::class, 'removeRegistration'])->name('reports_volunteers_remove');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UsersController::class, 'profile'])->name('profile');
    Route::get('/users', [UsersController::class, 'index'])->name('users_index');

    // Donations
    Route::get('/donations/create/{campaign}', [\App\Http\Controllers\Web\DonationsController::class, 'create'])->name('donations_create');
    Route::post('/donations/process/{campaign}', [\App\Http\Controllers\Web\DonationsController::class, 'process'])->name('donations_process');
    Route::get('/donations/success/{campaign}', [\App\Http\Controllers\Web\DonationsController::class, 'success'])->name('donations_success');
    Route::get('/donations/cancel/{campaign}', [\App\Http\Controllers\Web\DonationsController::class, 'cancel'])->name('donations_cancel');
    Route::get('/donations/certificate/{donation}', [\App\Http\Controllers\Web\CertificateController::class, 'download'])->name('donations_certificate');

    // Volunteers (Auth required to register or create tasks)
    Route::get('/volunteers/create', [\App\Http\Controllers\Web\VolunteerController::class, 'create'])->name('volunteers_create');
    Route::post('/volunteers/store', [\App\Http\Controllers\Web\VolunteerController::class, 'store'])->name('volunteers_store');
    Route::get('/volunteers/edit/{task}', [\App\Http\Controllers\Web\VolunteerController::class, 'edit'])->name('volunteers_edit');
    Route::post('/volunteers/update/{task}', [\App\Http\Controllers\Web\VolunteerController::class, 'update'])->name('volunteers_update');
    Route::post('/volunteers/delete/{task}', [\App\Http\Controllers\Web\VolunteerController::class, 'destroy'])->name('volunteers_delete');
    Route::post('/volunteers/register/{task}', [\App\Http\Controllers\Web\VolunteerController::class, 'register'])->name('volunteers_register');
});

// Volunteer List (Public)
Route::get('/volunteer', [\App\Http\Controllers\Web\VolunteerController::class, 'index'])->name('volunteers_list');

// Certificate Verification (Public)
Route::get('/donations/certificate/{donation}/verify', [\App\Http\Controllers\Web\CertificateController::class, 'verify'])->name('donations_certificate_verify');

// ── Stripe Webhook (CSRF-exempt — excluded in bootstrap/app.php) ──────────────
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');
