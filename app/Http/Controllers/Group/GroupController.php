<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function show(int $groupId)
    {
        $group = Group::with(['students' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($groupId);

        return view('group', compact('group'));
    }

    public function createGroup(Request $request, int $departmentId): JsonResponse
    {
        $request->validate([
            'group_name' => 'required|string|max:255',
            'student_name' => 'array',
            'student_name.*' => 'nullable|string|max:255'
        ]);

        // foreach (range(1, 30) as $number) {
            // Создаем группу с сохранением author_id
            $group = Group::create([
                'name' => $request->group_name,
                'department_id' => $departmentId,
                'author_id' => auth()->id()
            ]);

            // Для каждого студента создаем запись в базе
            foreach ($request->student_name as $studentName) {
                if (empty($studentName)) {
                    continue;
                }
                Student::create([
                    'full_name' => $studentName,
                    'group_id' => $group->id,
                    'author_id' => auth()->id()
                ]);
            }
        // }

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

        return redirect()->back()->with('success', 'Subjects assigned to group successfully');
    }
}
