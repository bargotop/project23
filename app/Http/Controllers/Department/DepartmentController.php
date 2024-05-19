<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    public function show($departmentId)
    {
        $department = Department::with(['groups' => function($query) {
            $query->orderBy('created_at', 'desc')->with(['students' => function($query) {
                $query->orderBy('created_at', 'desc');
            }]);
        }])->findOrFail($departmentId);

        return view('department', compact('department'));
    }

    public function deleteDepartment(int $departmentId): JsonResponse
    {
        $department = Department::findOrFail($departmentId);
        $department->delete();

        return response()->json(['success' => true]);
    }
}
