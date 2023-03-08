<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PlanningTaskController;
use App\Http\Controllers\ExecutionTaskController;
use App\Http\Controllers\Calendar\CalendarDayController;
use App\Http\Controllers\Calendar\CalendarWeekController;
use App\Http\Controllers\Calendar\CalendarMonthController;
use App\Http\Controllers\Calendar\CalendarYearController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\TrashCanController;
use App\Http\Controllers\Calendar\HeaderCalendarController;

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

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/async-form', [CalendarDayController::class, 'form'])->name('form');
    Route::get('/async-header-calendar-previous', [HeaderCalendarController::class, 'showPreviousMonth'])->name('header-calendar-previous');
    Route::get('/async-header-calendar-next', [HeaderCalendarController::class, 'showNextMonth'])->name('header-calendar-next');
    Route::get('/async-header-calendar-initialize', [HeaderCalendarController::class, 'initialize'])->name('header-calendar-initialize');
    Route::get('/replaced-task-display/{day}', [PlanningTaskController::class, 'getDailyTasksInJson'])->name('replaced-task-display');
    Route::get('/day/{year?}/{month?}/{day?}', [CalendarDayController::class, 'index'])->where( ['year' => '\d{4}', 'month' => '[1-9]|1[0-2]', 'day' => '[1-9]|1[0-9]|2[0-9]|3[0-1]'] )->name('day');
    Route::get('/week/{year?}/{month?}/{day?}', [CalendarWeekController::class, 'index'])->where( ['year' => '\d{4}', 'month' => '[1-9]|1[0-2]', 'day' => '[1-9]|1[0-9]|2[0-9]|3[0-1]'] )->name('week');
    Route::get('/month/{year?}/{month?}/{day?}', [CalendarMonthController::class, 'index'])->where( ['year' => '\d{4}', 'month' => '[1-9]|1[0-2]', 'day' => '[1-9]|1[0-9]|2[0-9]|3[0-1]'] )->name('month');
    Route::get('/year/{year?}/{month?}/{day?}', [CalendarYearController::class, 'index'])->where( ['year' => '\d{4}', 'month' => '[1-9]|1[0-2]', 'day' => '[1-9]|1[0-9]|2[0-9]|3[0-1]'] )->name('year');
    Route::redirect('/', '/month')->name('route');
    Route::post('/planning-task-input', [PlanningTaskController::class, 'store'])->name('p-task.store');
    Route::get('/toggle-completion-checks/{id}', [PlanningTaskController::class, 'toggleCompletionChecks'])->name('toggle-completion-checks');
    Route::post('/planning-task-update', [PlanningTaskController::class, 'update'])->name('p-task.update');
    Route::get('/task-destroy/{id}', [PlanningTaskController::class, 'destroy'])->name('task.destroy');
    Route::get('/trash-can', [TrashCanController::class, 'index'])->name('trash-can');
    Route::post('/trash-can', [TrashCanController::class, 'update'])->name('trash-can.update');
    Route::get('/profile', [HomeController::class, 'edit'])->name('user.edit');
    Route::post('/profile', [HomeController::class, 'update'])->name('user.update');
});
