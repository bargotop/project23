<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\JsonResponse;

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

    public function deleteDepartment(int $id): JsonResponse
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json(['success' => true]);
    }
}
