<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\DoctorDashboardController; // Asegúrate de importar el controlador del doctor

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:secretary'])->group(function () {
    Route::resource('patients', PatientController::class);
});

Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::resource('patients', PatientController::class);
    // Aquí agregamos la ruta para el dashboard del doctor
    Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'todaysAppointments'])->name('doctor.dashboard');
});

Route::resource('appointments', AppointmentController::class)->middleware('auth');

Route::get('/setup', [SetupController::class, 'showSetupForm'])->name('setup.form');
Route::post('/setup', [SetupController::class, 'processSetupForm'])->name('setup.process');

Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::resource('patients', PatientController::class);
    Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'todaysAppointments'])->name('doctor.dashboard');
    Route::get('/patients/{patient}/medical-records/create/{appointment_id?}', [PatientController::class, 'createMedicalRecord'])->name('patients.medical-records.create');
    Route::post('/patients/{patient}/medical-records', [PatientController::class, 'storeMedicalRecord'])->name('patients.medical-records.store');
});

require __DIR__.'/auth.php';