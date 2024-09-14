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

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\NurseController;

// Authentication routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/create-account', [AdminController::class, 'showCreateAccountForm'])->name('admin.create-account');
    Route::post('admin/create-account', [AdminController::class, 'createAccount']);
});

// Doctor routes
Route::middleware(['auth', 'doctor'])->group(function () {
    Route::get('doctor/dashboard', [DoctorController::class, 'index'])->name('doctor.dashboard');
});

// Nurse routes
Route::middleware(['auth', 'nurse'])->group(function () {
    Route::get('nurse/dashboard', [NurseController::class, 'index'])->name('nurse.dashboard');
});


Route::get('/', function () {
    return view('welcome');
});
