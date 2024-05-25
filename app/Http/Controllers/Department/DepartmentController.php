<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function show($id)
    {
        $department = Department::with(['groups' => function($query) {
            $query->orderBy('created_at', 'desc')->with(['students' => function($query) {
                $query->orderBy('created_at', 'desc');
            }]);
        }])->findOrFail($id);

        return view('department', compact('department'));
    }

    public function createDepartment(Request $request, int $facultyId): JsonResponse
    {
        $request->validate([
            // 'department_name' => 'array',
            'department_name' => 'required|string|max:255',
        ]);

        // foreach ($request->department_name as $departmentName) {
            Department::create([
                'name' => $request->department_name,
                'faculty_id' => $facultyId,
                'author_id' => auth()->id(),
            ]);
        // }

        return response()->json(['success' => true]);
    }

    public function deleteDepartment(int $id): JsonResponse
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json(['success' => true]);
    }
}
