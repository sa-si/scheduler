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
    Route::get('/planning-task-update', function () {
        return view('planning-task-update');
    });
    Route::get('/execution-task-update', function () {
        return view('execution-task-update');
    });
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
