<?php

use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Department\DepartmentController;
use App\Http\Controllers\Faculty\FacultyController;
use App\Http\Controllers\Group\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Student\StudentController;
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
    });

    // Faculties
    Route::prefix('faculties')->group(function () {
        Route::get('/', [FacultyController::class, 'getFaculties'])->name('faculties.show');
        Route::post('/', [FacultyController::class, 'createFaculty'])->name('faculties.create');
        Route::delete('/{facultyId}', [FacultyController::class, 'deleteFaculty'])->name('faculties.delete');
    });

    // Departments
    Route::prefix('departments')->group(function () {
        Route::get('/{departmentId}', [DepartmentController::class, 'show'])->name('departments.show');
        Route::delete('/{departmentId}', [DepartmentController::class, 'deleteDepartment'])->name('departments.delete');
    });

    // Groups
    Route::prefix('groups')->group(function () {
        Route::get('/{groupId}', [GroupController::class, 'show'])->name('groups.show');
        Route::post('/{departmentId}', [GroupController::class, 'createGroup'])->name('groups.create');
        Route::delete('/{groupId}', [GroupController::class, 'deleteGroup'])->name('groups.delete');
        Route::post('/{groupId}/assign-subjects', [GroupController::class, 'assignSubjectsToGroup'])->name('groups.assignSubjects');
    });

    // Students
    Route::prefix('students')->group(function () {
        Route::post('/{groupId}', [StudentController::class, 'createStudent'])->name('students.create');
        Route::delete('/{studentId}', [StudentController::class, 'deleteStudent'])->name('students.delete');
    });

    // Attendance
    Route::prefix('attendances')->group(function () {
        Route::post('/record', [AttendanceController::class, 'recordAttendance'])->name('attendances.record');
    });

});

require __DIR__.'/auth.php';
