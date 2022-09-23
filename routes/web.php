<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'home'])->name('home');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/weekly-calendar', function () {
    return view('weekly-calendar');
});
Route::get('/monthly-calendar', function () {
    return view('monthly-calendar');
});
Route::get('/year-calendar', function () {
    return view('year-calendar');
});
Route::get('/planning-task-input', function () {
    return view('planning-task-input');
});
Route::get('/execution-task-input', function () {
    return view('execution-task-input');
});
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
