<?php

use Livewire\Volt\Volt;
use App\Livewire\ProfileSearch;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/search', ProfileSearch::class)->name('profile.search');

    // Route for viewing individual profiles - needed for search results linking
    // Route::get('/profile/{user}', [App\Http\Controllers\ProfileController::class, 'view'])->name('profile.view');


    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
