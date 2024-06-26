<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function show(int $id)
    {
        $group = Group::with([
            'students' => function($query) {
                $query->orderBy('full_name', 'asc');
            },
            'subjects' => function($query) {
                $query->orderBy('name', 'asc');
            }
        ])->findOrFail($id);

        return view('group', compact('group'));
    }

    public function createGroup(Request $request, int $departmentId): JsonResponse
    {
        $request->validate([
            'group_name' => 'required|string|max:255',
        ]);

        // Создаем группу
        $group = Group::create([
            'name' => $request->group_name,
            'department_id' => $departmentId,
            'author_id' => auth()->id()
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteGroup(int $id): JsonResponse
    {
        $group = Group::findOrFail($id);
        $group->delete();

        return response()->json(['success' => true]);
    }

    public function getGroupSubjects(Request $request, int $id)
    {
        $group = Group::with(['subjects' => function ($query) {
            $query->orderBy('name', 'asc');
        }])->findOrFail($id);

        return response()->json(['data' => $group]);
    }
}
