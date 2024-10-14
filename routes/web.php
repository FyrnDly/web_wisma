<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\UpdatePassword;

Route::middleware(['auth'])->group(function () {
    Route::get('/update-password', UpdatePassword::class)->name('update-password');
});
