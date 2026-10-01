<?php

use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::view('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
    });

Route::middleware(['auth'])->group(function () {
    Route::livewire('invitations/{invitation}/accept', 'pages::teams.accept-invitation')->name('invitations.accept');
});

require __DIR__.'/settings.php';
require __DIR__.'/admin.php';

// --- Leave management: volunteer portal (access enforced by LeaveRequestPolicy) ---
Route::prefix('volunteer')->name('volunteer.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('leaves', [\App\Http\Controllers\Volunteer\LeaveRequestController::class, 'index'])->name('leaves.index');
    Route::get('leaves/create', [\App\Http\Controllers\Volunteer\LeaveRequestController::class, 'create'])->name('leaves.create');
    Route::post('leaves', [\App\Http\Controllers\Volunteer\LeaveRequestController::class, 'store'])->name('leaves.store');
    Route::get('leaves/{leave}', [\App\Http\Controllers\Volunteer\LeaveRequestController::class, 'show'])->name('leaves.show');
    Route::patch('leaves/{leave}/cancel', [\App\Http\Controllers\Volunteer\LeaveRequestController::class, 'cancel'])->name('leaves.cancel');
});
