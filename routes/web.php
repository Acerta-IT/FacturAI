<?php

use App\Http\Controllers\ClockingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\Admin;



Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, '__invoke'])->name('dashboard');

    Route::middleware(Admin::class)->group(function () {

        Route::get('/users', [UserController::class, 'index'])->name('user.index');
        Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
        Route::get('/user/edit/{user}', [UserController::class, 'edit'])->name('user.edit');
        Route::post('/user/edit/{user}', [UserController::class, 'update'])->name('user.update');
        Route::post('/user/create', [UserController::class, 'store']);
    });

    // Managing 404 errors
    Route::fallback(function() {
        return redirect()->route('dashboard')->with('status', [
            'message' => 'Error 404: sitio no encontrado',
            'class' => 'toast-danger'
        ]);
    });

    
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__ . '/auth.php';
