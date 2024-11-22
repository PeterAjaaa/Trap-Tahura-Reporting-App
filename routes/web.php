<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/report', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
Route::get('/report/photo/{id}', [App\Http\Controllers\ReportController::class, 'showPhoto'])->name('reports.photo.show');
Route::get('/report/track/{token}', [App\Http\Controllers\ReportController::class, 'show'])->name('reports.share');

Route::post('/report/store', [App\Http\Controllers\ReportController::class, 'store'])->name('reports.store');
