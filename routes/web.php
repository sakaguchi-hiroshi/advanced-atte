<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkTimeController;
use App\Http\Controllers\BreakTimeController;
use App\Http\Controllers\AttendaceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => '/work'], function() {
    Route::group(['middleware' => ['auth']], function() {
        Route::get('/stamp', [WorkTimeController::class, 'index'])->name('stamp');
        Route::post('/start', [WorkTimeController::class, 'create']);
        // Route::post('/end', [WorkTimeController::class, 'update'])->name('stamp');
        // Route::post('/break/in', [BreakTimeController::class, 'create'])->name('stamp');
        // Route::post('/break/out', [BreakTimeController::class, 'update'])->name('stamp');
        // Route::post('/date', [AttendanceController::class, 'index'])->name('calendar');
        // Route::post('/attendance', [AttendanceController::class, 'show'])->name('attendance');
    });
});

require __DIR__.'/auth.php';
