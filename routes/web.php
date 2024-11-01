<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;


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
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/create-account', [AdminController::class, 'showCreateAccountForm'])->name('admin.create-account');
    Route::post('admin/create-account', [AdminController::class, 'createAccount']);
});

// Doctor routes
Route::middleware(['auth', 'doctor'])->group(function () {
    Route::get('doctor/dashboard', [DoctorController::class, 'index'])->name('doctor.dashboard');
    Route::get('/doctor/find-patient', [DoctorController::class, 'findPatient'])->name('doctor.findPatient');
    Route::get('/doctor/selectPatient', [DoctorController::class, 'selectPatient'])->name('doctor.selectPatient');
    Route::get('/doctor/diagnosis/{patient_id}/{record_id}', [DoctorController::class, 'showDiagnosis'])->name('doctor.diagnosis');
    Route::post('/store-diagnosis', [DoctorController::class, 'storeDiagnosis'])->name('store.diagnosis');
    Route::get('/doctor/prescription/{patient_id}/{record_id}', [DoctorController::class, 'prescription'])->name('doctor.prescription');
    Route::post('/doctor/store-prescription', [DoctorController::class, 'storePrescription'])->name('doctor.storePrescription');
    Route::get('/doctor/docRefill', [DoctorController::class, 'docRefill'])->name('doctor.docRefill');
    Route::post('/doctor/submitRefill/{id}', [DoctorController::class, 'submitRefill'])->name('doctor.submitRefill');
    Route::delete('/doctor/prescription/remove/{id}', [DoctorController::class, 'removePrescription'])->name('doctor.removePrescription');

});

// Nurse routes
Route::middleware(['auth', 'nurse'])->group(function () {
    Route::get('nurse/dashboard', [NurseController::class, 'index'])->name('nurse.dashboard');
    Route::get('/nurse/create-patient', [NurseController::class, 'createPatient'])->name('nurse.createPatient');
    Route::post('/nurse/save-patient', [NurseController::class, 'savePatient'])->name('nurse.savePatient');
    Route::get('/nurse/admit-patient', [NurseController::class, 'admitPatient'])->name('nurse.admitPatient'); // No parameters
    Route::get('/find-patient', [NurseController::class, 'findPatient'])->name('nurse.findPatient');
    Route::post('/nurse/admit-patient', [NurseController::class, 'storeAdmitPatient'])->name('admit.patient.store');
    Route::get('/nurse/patient/{id}/records', [NurseController::class, 'viewPatientRecord'])->name('nurse.viewPatientRecord');
    Route::get('/nurse/selectPatient', [NurseController::class, 'selectPatient'])->name('nurse.selectPatient');
    Route::post('/nurse/existing-patient', [NurseController::class, 'storeExistingPatient'])->name('nurse.admitexisting');
    Route::get('/nurse/patient/{patient_id}/record/{record_id}', [NurseController::class, 'viewExistingPatientRecord'])->name('nurse.viewExistingPatientRecord');
    Route::get('/nurse/prescriptions', [NurseController::class, 'prescriptionList'])->name('nurse.prescription_list');
    Route::get('/nurse/find_refillpatient', [NurseController::class, 'findRefillPatient'])->name('nurse.find_refillpatient');
    Route::get('/nurse/defer', [NurseController::class, 'deferPatient'])->name('nurse.deferPatient');
    Route::post('/nurse/defer-refill-date', [NurseController::class, 'deferRefillDate'])->name('nurse.deferRefillDate');
    Route::get('/nurse/refill-patient', [NurseController::class, 'refillPatient'])->name('nurse.refillPatient');
    Route::post('/nurse/refill', [NurseController::class, 'storeRefill'])->name('nurse.refill');


});

Route::get('/file/{id}', [FileController::class, 'show'])->name('files.view');


Route::get('/', function () {
    return view('auth.login');
});
