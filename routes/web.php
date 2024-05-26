<?php

use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Department\DepartmentController;
use App\Http\Controllers\Faculty\FacultyController;
use App\Http\Controllers\Group\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Schedule\ScheduleController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Subject\SubjectController;
use App\Http\Controllers\Teacher\TeacherController;
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
        Route::get('/{id}', [FacultyController::class, 'show'])->name('faculties.show');
        Route::get('/', [FacultyController::class, 'getFaculties'])->name('faculties.index');
        Route::post('/', [FacultyController::class, 'createFaculty'])->name('faculties.create');
        Route::delete('/{id}', [FacultyController::class, 'deleteFaculty'])->name('faculties.delete');
    });

    // Departments
    Route::prefix('departments')->group(function () {
        Route::get('/{id}', [DepartmentController::class, 'show'])->name('departments.show');
        Route::post('/{facultyId}', [DepartmentController::class, 'createDepartment'])->name('departments.create');
        Route::delete('/{id}', [DepartmentController::class, 'deleteDepartment'])->name('departments.delete');
    });

    // Groups
    Route::prefix('groups')->group(function () {
        Route::get('/{id}', [GroupController::class, 'show'])->name('groups.show');
        Route::post('/{departmentId}', [GroupController::class, 'createGroup'])->name('groups.create');
        Route::delete('/{id}', [GroupController::class, 'deleteGroup'])->name('groups.delete');
        Route::get('/{id}/subjects', [GroupController::class, 'getGroupSubjects'])->name('groups.subjects');
        Route::post('/{id}/assign-subjects', [GroupController::class, 'assignSubjectsToGroup'])->name('groups.assignSubjects');
    });

    // Students
    Route::prefix('students')->group(function () {
        Route::post('/{groupId}', [StudentController::class, 'createStudent'])->name('students.create');
        Route::delete('/{id}', [StudentController::class, 'deleteStudent'])->name('students.delete');
    });

    // Subjects
    Route::prefix('subjects')->group(function () {
        Route::post('/{groupId}', [SubjectController::class, 'createSubject'])->name('subjects.create');
        Route::delete('/{id}', [SubjectController::class, 'deleteSubject'])->name('subjects.delete');
    });


    // Teacher
    Route::prefix('teacher')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('teacher.index');
    });
    
    // Teacher
    Route::prefix('schedule')->group(function () {
        Route::get('/monday', [ScheduleController::class, 'monday'])->name('schedule.monday');
        Route::get('/tuesday', [ScheduleController::class, 'tuesday'])->name('schedule.tuesday');
        Route::get('/wednesday', [ScheduleController::class, 'wednesday'])->name('schedule.wednesday');
        Route::get('/thursday', [ScheduleController::class, 'thursday'])->name('schedule.thursday');
        Route::get('/friday', [ScheduleController::class, 'friday'])->name('schedule.friday');
        Route::get('/saturday', [ScheduleController::class, 'saturday'])->name('schedule.saturday');

        Route::post('/store', [ScheduleController::class, 'store'])->name('schedule.store');
    });

    // Attendance
    // Route::prefix('attendances')->group(function () {
    //     Route::post('/record', [AttendanceController::class, 'recordAttendance'])->name('attendances.record');
    // });

});

require __DIR__.'/auth.php';
