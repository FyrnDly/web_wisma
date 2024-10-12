<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\UpdatePassword;
use App\Filament\Auth\Login;

Route::get('/login', Login::class)->name('login');
Route::middleware(['auth'])->group(function () {
    Route::get('/update-password', UpdatePassword::class)->name('update-password');
});
