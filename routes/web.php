<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkTimeController;
use App\Http\Controllers\BreakTimeController;
use App\Http\Controllers\AttendanceController;

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

        Route::post('/start', [WorkTimeController::class, 'create'])->name('stamp');

        Route::post('/end', [WorkTimeController::class, 'update'])->name('stamp');

        Route::post('/break/in', [BreakTimeController::class, 'create'])->name('stamp');

        Route::post('/break/out', [BreakTimeController::class, 'update'])->name('stamp');

        Route::get('/calendar', [AttendanceController::class, 'index'])->name('calendar');

        Route::post('/calendar/sub/month', [AttendanceController::class, 'selectedCalendar'])->name('calendar');

        Route::post('/calendar/add/month', [AttendanceController::class, 'selectedCalendar'])->name('calendar');

        Route::post('/calendar/select/month', [AttendanceController::class, 'selectedMonthCalendar'])->name('calendar');

        Route::get('/attendance/{date}', [AttendanceController::class, 'show'])->name('attendance');

        Route::post('/attendance/date/prev', [AttendanceController::class, 'operationDate'])->name('attendance');

        Route::post('/attendance/date/next', [AttendanceController::class, 'operationDate'])->name('attendance');

    });
});

require __DIR__.'/auth.php';
