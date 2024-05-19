<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function createStudent(Request $request, int $groupId): JsonResponse
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'student_name' => 'array',
            'student_name.*' => 'required|string|max:255'
        ]);
    
        foreach ($request->student_name as $studentName) {
            if (empty($studentName)) {
                continue;
            }
            Student::create([
                'full_name' => $studentName,
                'group_id' => $groupId,
                'author_id' => auth()->id()
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function deleteStudent(int $studentId): JsonResponse
    {
        $student = Student::findOrFail($studentId);
        $student->delete();

        return response()->json(['success' => true]);
    }
}
