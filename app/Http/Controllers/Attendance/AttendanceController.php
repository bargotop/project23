<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Schedule;
use Carbon\Carbon;
use Auth;

class AttendanceController extends Controller
{
    public function index(Request $request, $scheduleId)
    {
        $schedule = Schedule::with('group.students')->findOrFail($scheduleId);
        $students = $schedule->group->students;
        $dates = collect();

        // Генерация дат за последние 30 дней
        for ($i = 0; $i < 30; $i++) {
            $dates->push(Carbon::today()->subDays($i)->format('d-m'));
        }

        $attendances = Attendance::where('schedule_id', $scheduleId)
            ->whereIn('date', $dates->map(function ($date) {
                return Carbon::createFromFormat('d-m', $date)->format('Y-m-d');
            }))
            ->get()
            ->groupBy(function ($attendance) {
                return Carbon::parse($attendance->date)->format('d-m');
            });

        return view('teacher.attendance', compact('schedule', 'students', 'dates', 'attendances'));
    }

    public function store(Request $request, $scheduleId)
    {
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.date' => 'required|date',
            'attendances.*.is_present' => 'required|boolean',
        ]);

        foreach ($request->attendances as $attendanceData) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $attendanceData['student_id'],
                    'schedule_id' => $scheduleId,
                    'date' => $attendanceData['date'],
                ],
                [
                    'is_present' => $attendanceData['is_present'],
                ]
            );
        }

        return response()->json(['success' => true]);
    }
}
