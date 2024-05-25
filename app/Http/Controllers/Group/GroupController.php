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
        $group = Group::with(['students' => function($query) {
            $query->orderBy('created_at', 'desc');
        }, 'subjects'])->findOrFail($id);

        // $subjects = Subject::all();

        // $users = User::all();

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

    public function deleteGroup(int $groupId): JsonResponse
    {
        $group = Group::findOrFail($groupId);
        $group->delete();

        return response()->json(['success' => true]);
    }

    public function assignSubjectsToGroup(Request $request, $groupId)
    {
        $request->validate([
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        $group = Group::findOrFail($groupId);
        $group->subjects()->sync($request->subject_ids);

        return response()->json(['success' => true]);
        // return redirect()->back()->with('success', 'Subjects assigned to group successfully');
    }
}
