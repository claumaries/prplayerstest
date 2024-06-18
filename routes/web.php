<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Default Laravel profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserController::class, 'show'])
        ->where('id', '[0-9]+')
        ->name('users.show');
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])
        ->where('id', '[0-9]+')
        ->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])
        ->where('id', '[0-9]+')
        ->name('users.update');
    Route::delete('/users/destroy', [UserController::class, 'destroy'])->name('users.destroy');
    Route::softDeletes('users', UserController::class);
});

require __DIR__.'/auth.php';
