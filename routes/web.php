<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('reports.index');
    }
});

Route::get('/report', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
Route::get('/report/photo/{id}', [App\Http\Controllers\ReportController::class, 'showPhoto'])->name('reports.photo.show');
Route::get('/report/track/{token}', [App\Http\Controllers\ReportController::class, 'show'])->name('reports.share');
Route::post('/report/store', [App\Http\Controllers\ReportController::class, 'store'])->name('reports.store');
Route::patch('/reports/{report}/status', [App\Http\Controllers\ReportController::class, 'updateStatus'])->name('reports.update.status');

Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');

Route::get('/master/add', [App\Http\Controllers\AdminController::class, 'createNewAdmin'])->name('master.admin.create');
Route::post('/master/store', [App\Http\Controllers\AdminController::class, 'saveNewAdmin'])->name('master.admin.store');


Route::get('/test-broadcast', function () {
    event(new App\Events\ReportCreated(App\Models\Report::first()));
    return 'Broadcast test triggered!';
});
