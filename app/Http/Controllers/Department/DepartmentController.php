<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class DepartmentController extends Controller
{
    public function createGroupWithStudents(Request $request, int $departmentId): RedirectResponse
    {
        $request->validate([
            'group_name' => 'required|string|max:255',
            'student_name' => 'required|array',
            'student_name.*' => 'required|string|max:255'
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
                Student::create([
                    'full_name' => $studentName,
                    'group_id' => $group->id,
                    'author_id' => auth()->id()
                ]);
            }
        // }

        return redirect()->route('departments.groups-and-students.show', ['departmentId' => $departmentId])->with('success', 'Group with students created successfully');
    }

    public function showGroupsAndStudents(int $departmentId)
    {
        $department = Department::with('groups.students')->orderBy('created_at', 'desc')->findOrFail($departmentId);

        return view('departments.index', compact('department'));
    }

    public function deleteGroup(int $groupId): JsonResponse
    {
        $group = Group::findOrFail($groupId);
        $group->delete();

        return response()->json(['success' => true]);
        // return redirect()->route('dashboard')->with('success', 'Department deleted successfully');
    }

    public function deleteStudent(int $studentId): JsonResponse
    {
        $student = Student::findOrFail($studentId);
        $student->delete();

        return response()->json(['success' => true]);
        // return redirect()->route('dashboard')->with('success', 'Department deleted successfully');
    }
}
