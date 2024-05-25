<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index()
    {
        $faculties = Faculty::with('departments')->orderBy('created_at', 'desc')->get();

        return view('dashboard', compact('faculties'));
    }

    public function getFaculties()
    {
        $faculties = Faculty::with('departments')->get();

        return response()->json(['faculties' => $faculties], 200);
    }

    public function deleteFaculty(int $id): JsonResponse
    {
        $faculty = Faculty::findOrFail($id);
        $faculty->delete();

        return response()->json(['success' => true]);
    }

    public function createFaculty(Request $request): JsonResponse
    {
        $request->validate([
            'faculty_name' => 'required|string|max:255',
            // 'department_name' => 'array',
            // 'department_name.*' => 'nullable|string|max:255',
        ]);

        $faculty = Faculty::create([
            'name' => $request->faculty_name,
            'author_id' => auth()->id(),
        ]);

        // foreach ($request->department_name as $departmentName) {
        //     if (empty($departmentName)) {
        //         continue;
        //     }
        //     Department::create([
        //         'name' => $departmentName,
        //         'faculty_id' => $faculty->id,
        //         'author_id' => auth()->id(),
        //     ]);
        // }

        return response()->json(['success' => true]);
    }
}