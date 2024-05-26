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
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\RedirectAuthenticated;
use App\Http\Middleware\TeacherMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware([RedirectAuthenticated::class]);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Only admin routes
    Route::middleware([AdminMiddleware::class])->group(function () {

        // Dashboard
        Route::prefix('dashboard')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        });

        // Faculties
        Route::prefix('faculties')->group(function () {
            Route::get('/', [FacultyController::class, 'getFaculties'])->name('faculties.index');
            Route::get('/{id}', [FacultyController::class, 'show'])->name('faculties.show');
            Route::post('/', [FacultyController::class, 'createFaculty'])->name('faculties.create');
            Route::delete('/{id}', [FacultyController::class, 'deleteFaculty'])->name('faculties.delete');
        });
    
        // Departments
        Route::prefix('departments')->group(function () {
            Route::get('/{id}', [DepartmentController::class, 'show'])->name('departments.show');
            Route::post('/{facultyId}', [DepartmentController::class, 'createDepartment'])->name('departments.create');
            Route::delete('/{id}', [DepartmentController::class, 'deleteDepartment'])->name('departments.delete');
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
    });

    // Groups
    Route::prefix('groups')->group(function () {
        Route::get('/')->name('groups');
        Route::get('/{id}', [GroupController::class, 'show'])->name('groups.show')->middleware([AdminMiddleware::class]);
        Route::post('/{departmentId}', [GroupController::class, 'createGroup'])->name('groups.create')->middleware([AdminMiddleware::class]);
        Route::delete('/{id}', [GroupController::class, 'deleteGroup'])->name('groups.delete')->middleware([AdminMiddleware::class]);
        Route::get('/{id}/subjects', [GroupController::class, 'getGroupSubjects'])->name('groups.subjects');
    });

    // Teacher
    Route::prefix('teacher')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('teacher.index');
    });
    
    // Schedule
    Route::prefix('schedule')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('schedule.index');
        Route::post('/store', [ScheduleController::class, 'store'])->name('schedule.store');
        Route::delete('/{id}', [ScheduleController::class, 'deleteSchedule'])->name('schedule.delete');
        Route::post('/subjects-by-date', [ScheduleController::class, 'getSubjectsByDate'])->name('schedule.subjects.by-date');

        Route::get('/monday', [ScheduleController::class, 'monday'])->name('schedule.monday');
        Route::get('/tuesday', [ScheduleController::class, 'tuesday'])->name('schedule.tuesday');
        Route::get('/wednesday', [ScheduleController::class, 'wednesday'])->name('schedule.wednesday');
        Route::get('/thursday', [ScheduleController::class, 'thursday'])->name('schedule.thursday');
        Route::get('/friday', [ScheduleController::class, 'friday'])->name('schedule.friday');
        Route::get('/saturday', [ScheduleController::class, 'saturday'])->name('schedule.saturday');
    });

    // Attendance
    Route::prefix('attendances')->group(function () {
        Route::get('/')->name('attendances');
        Route::get('/{scheduleId}', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::post('/{scheduleId}', [AttendanceController::class, 'store'])->name('attendances.store');
    });

});

require __DIR__.'/auth.php';
