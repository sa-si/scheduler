<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PlanningTaskController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'home'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/weekly-calendar', function () {
        return view('weekly-calendar');
    });
    Route::get('/monthly-calendar', function () {
        return view('monthly-calendar');
    });
    Route::get('/year-calendar', function () {
        return view('year-calendar');
    });
    Route::get('/planning-task-input', [PlanningTaskController::class, 'create'])->name('p-task.create');
    Route::post('/planning-task-input', [PlanningTaskController::class, 'store'])->name('p-task.store');
    Route::get('/planning-task-update/{id}', [PlanningTaskController::class, 'edit'])->name('p-task.edit');
    Route::post('/planning-task-update/{id}', [PlanningTaskController::class, 'update'])->name('p-task.update');
    Route::get('/planning-task-destroy/{id}', [PlanningTaskController::class, 'destroy'])->name('p-task.destroy');

    Route::get('/execution-task-input', [ExecutionTaskController::class, 'create'])->name('e-task.create');
    Route::post('/execution-task-input', [ExecutionTaskController::class, 'store'])->name('e-task.store');
    Route::get('/execution-task-update/{id}', [ExecutionTaskController::class, 'edit'])->name('e-task.edit');
    Route::post('/execution-task-update/{id}', [ExecutionTaskController::class, 'update'])->name('e-task.update');
    Route::get('/execution-task-destroy/{id}', [ExecutionTaskController::class, 'destroy'])->name('e-task.destroy');

    Route::get('/trash-can', function () {
        return view('trash-can');
    });
    Route::get('/analysis', function () {
        return view('analysis');
    });
    Route::get('/profile', function () {
        return view('profile');
    });
    Route::get('/profile', [HomeController::class, 'edit'])->name('user.edit');
    Route::post('/profile', [HomeController::class, 'update'])->name('user.update');
});
