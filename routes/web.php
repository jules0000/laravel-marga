<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Redirect to home landing page if it exists
    $homePage = \App\Models\Webpage::where('slug', 'home')->where('is_published', true)->first();
    if ($homePage) {
        return redirect()->route('webpages.show', $homePage);
    }
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('users', UserController::class);
    });

    // Webpage management routes (protected by permission middleware)
    Route::middleware('permission:manage-webpages')->group(function () {
        Route::resource('webpages', \App\Http\Controllers\WebpageController::class);
        // Redirect /webpages/{id}/sections to edit page
        Route::get('webpages/{webpage}/sections', function(\App\Models\Webpage $webpage) {
            return redirect()->route('webpages.edit', $webpage);
        });
        Route::post('webpages/{webpage}/sections', [\App\Http\Controllers\WebpageController::class, 'addSection'])->name('webpages.sections.store');
        Route::get('webpages/sections/{section}', [\App\Http\Controllers\WebpageController::class, 'getSection'])->name('webpages.sections.show');
        Route::put('webpages/sections/{section}', [\App\Http\Controllers\WebpageController::class, 'updateSection'])->name('webpages.sections.update');
        Route::delete('webpages/sections/{section}', [\App\Http\Controllers\WebpageController::class, 'deleteSection'])->name('webpages.sections.destroy');
        Route::post('webpages/{webpage}/reorder', [\App\Http\Controllers\WebpageController::class, 'reorderSections'])->name('webpages.reorder');
    });
});

// Public routes for viewing webpages
Route::get('/pages/{webpage:slug}', [\App\Http\Controllers\WebpageController::class, 'show'])->name('webpages.show');

