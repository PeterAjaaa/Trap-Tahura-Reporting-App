<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/report', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
Route::get('/report/track', [App\Http\Controllers\ReportController::class, 'track'])->name('reports.track');
Route::get('/report/photo/{id}', [App\Http\Controllers\ReportController::class, 'showPhoto'])->name('reports.photo.show');


Route::post('/report/store', [App\Http\Controllers\ReportController::class, 'store'])->name('reports.store');
