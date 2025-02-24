<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\FacturAIController;
use App\Http\Controllers\JobController;
use App\Http\Middleware\Admin;
use App\Http\Controllers\CompletedJobController;
use App\Http\Controllers\FileController;
use App\Http\Middleware\FileAccess;
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('facturai.index');
    });

    Route::get('/facturai', [FacturAIController::class, 'index'])->name('facturai.index');
    Route::post('/facturai/execute', [FacturAIController::class, 'execute'])->name('facturai.execute');

    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::get('/completedJobs', [CompletedJobController::class, 'index'])->name('completedJobs.index');

    Route::middleware(Admin::class)->group(function () {

        Route::get('/users', [UserController::class, 'index'])->name('user.index');
        Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
        Route::get('/user/edit/{user}', [UserController::class, 'edit'])->name('user.edit');
        Route::post('/user/edit/{user}', [UserController::class, 'update'])->name('user.update');
        Route::post('/user/create', [UserController::class, 'store']);

        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/reset', [SettingsController::class, 'reset'])->name('settings.reset');
        Route::post('/settings/save-default', [SettingsController::class, 'saveDefault'])->name('settings.saveDefault');

        Route::post('/completed-jobs/clean', [CompletedJobController::class, 'clean'])->name('completed-jobs.clean');
    });

    Route::middleware(FileAccess::class)->group(function () {
        Route::get('/download/{projectId}/{filename}', [FileController::class, 'download'])
        ->name('file.download');
    });

    // Managing 404 errors
    Route::fallback(function() {
        return redirect()->route('facturai.index')->with('status', [
            'message' => 'Error 404: sitio no encontrado',
            'class' => 'toast-danger'
        ]);
    });
});

require __DIR__ . '/auth.php';
