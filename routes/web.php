<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // 設定関連
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    // 製品管理
    Volt::route('products', 'products.index')->name('products.index');
    Volt::route('products/create', 'products.create')->name('products.create');
    Volt::route('products/{product}/edit', 'products.edit')->name('products.edit');
});

require __DIR__.'/auth.php';
