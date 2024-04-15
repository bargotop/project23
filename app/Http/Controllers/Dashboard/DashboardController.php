<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $faculties = Faculty::with('departments')->get();

        return view('dashboard', compact('faculties'));
    }

    public function getFaculties()
    {
        // Получаем все факультеты
        $faculties = Faculty::with('departments')->get();

        return response()->json(['faculties' => $faculties], 200);
    }

    public function createFaculty(Request $request)
    {
        // Валидация входных данных
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Создание нового факультета
        $faculty = Faculty::create([
            'name' => $request->name,
            'author_id' => auth()->id(), // Идентификатор текущего пользователя
        ]);

        return $this->index();

        // return response()->json(['message' => 'Faculty created successfully', 'faculty' => $faculty], 201);
    }

    public function getDepartments($facultyId)
    {
        // Проверяем, существует ли факультет с указанным идентификатором
        $faculty = Faculty::findOrFail($facultyId);

        // Получаем все направления для данного факультета
        $departments = $faculty->departments;

        return response()->json(['departments' => $departments], 200);
    }

    public function createDepartment(Request $request, $facultyId)
    {
        // Проверяем, существует ли факультет с указанным идентификатором
        $faculty = Faculty::findOrFail($facultyId);

        // Валидация входных данных
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Создание нового направления внутри факультета
        $department = $faculty->departments()->create([
            'name' => $request->name,
            'author_id' => auth()->id(), // Идентификатор текущего пользователя
        ]);

        return $this->index();

        return response()->json(['message' => 'Department created successfully', 'department' => $department], 201);
    }
}