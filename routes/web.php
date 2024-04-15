<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Маршрут для создания факультета
    Route::post('/faculty', [DashboardController::class, 'createFaculty'])->name('createFaculty');

    // Маршрут для создания направления внутри факультета
    Route::post('/faculty/{facultyId}/department', [DashboardController::class, 'createDepartment'])->name('createDepartment');

    // Маршрут для просмотра всех факультетов
    Route::get('/faculties', [DashboardController::class, 'getFaculties'])->name('getFaculties');

    // Маршрут для просмотра всех направлений внутри определенного факультета
    Route::get('/faculty/{facultyId}/departments', [DashboardController::class, 'getDepartments'])->name('getDepartments');
});

require __DIR__.'/auth.php';
