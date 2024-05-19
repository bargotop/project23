<?php

use App\Http\Controllers\Department\DepartmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
            Route::post('/', [DashboardController::class, 'createFaculty'])->name('faculties.create');
            Route::delete('/{facultyId}', [DashboardController::class, 'deleteFaculty'])->name('faculties.delete');
            Route::get('/', [DashboardController::class, 'getFaculties'])->name('getFaculties');

            // Departments
            Route::prefix('departments')->group(function () {
                Route::post('/', [DashboardController::class, 'createDepartment'])->name('departments.create');
                Route::delete('/{departmentId}', [DashboardController::class, 'deleteDepartment'])->name('departments.delete');
                Route::get('/{facultyId}', [DashboardController::class, 'getDepartments'])->name('departments.list');

                Route::prefix('groups')->group(function () {
                    Route::delete('/{groupId}', [DepartmentController::class, 'deleteGroup'])->name('groups.delete');
                    Route::prefix('students')->group(function () {
                        Route::delete('/{studentId}', [DepartmentController::class, 'deleteStudent'])->name('students.delete');
                    });
                });

                // Route::delete('/groups', [DashboardController::class, 'deleteDepartment'])->name('departments.delete');
                Route::get('/{departmentId}/groups-and-students', [DepartmentController::class, 'showGroupsAndStudents'])->name('departments.groups-and-students.show');
                Route::post('/{departmentId}/groups-with-students', [DepartmentController::class, 'createGroupWithStudents'])->name('departments.groups-with-students.create');

                // Route::get('/{departmentId}/groups', [DepartmentController::class, 'listGroups'])->name('groups.list');
                // Route::post('/{departmentId}/groups', [DepartmentController::class, 'createGroup'])->name('groups.create');

                // Route::get('/{departmentId}/groups/{groupId}/students', [DepartmentController::class, 'listStudents'])->name('students.list');
                // Route::post('/{departmentId}/groups/{groupId}/students', [DepartmentController::class, 'createStudent'])->name('students.create');
            });
        });

        // Faculty with Department
        Route::post('/faculty-with-department', [DashboardController::class, 'createFacultyWithDepartment'])->name('faculties.with-department.create');
    });
});

require __DIR__.'/auth.php';
