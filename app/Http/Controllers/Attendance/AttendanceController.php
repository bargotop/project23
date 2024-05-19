<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function recordAttendance(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'present' => 'required|boolean',
        ]);

        Attendance::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'subject_id' => $request->subject_id,
                'date' => $request->date,
            ],
            [
                'present' => $request->present,
                'author_id' => auth()->id(),
            ]
        );

        return response()->json(['success' => true]);
    }
}
