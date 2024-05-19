<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/direction', function () {
    return view('direction');
})->name('direction');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Faculties
        Route::prefix('faculties')->group(function () {
            Route::post('/', [DashboardController::class, 'createFaculty'])->name('createFaculty');
            Route::delete('/{facultyId}', [DashboardController::class, 'deleteFaculty'])->name('deleteFaculty');
            Route::get('/', [DashboardController::class, 'getFaculties'])->name('getFaculties');

            // Departments
            Route::prefix('departments')->group(function () {
                Route::post('/', [DashboardController::class, 'createDepartment'])->name('createDepartment');
                Route::delete('/{departmentId}', [DashboardController::class, 'deleteDepartment'])->name('deleteDepartment');
                Route::get('/{facultyId}', [DashboardController::class, 'getDepartments'])->name('getDepartments');
            });
        });

        // Faculty with Department
        Route::post('/faculty-with-department', [DashboardController::class, 'createFacultyWithDepartment'])->name('createFacultyWithDepartment');
    });
});

require __DIR__.'/auth.php';
