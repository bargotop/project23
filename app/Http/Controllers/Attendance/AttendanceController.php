<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Schedule;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    private $daysBefore = -10;
    private $daysAfter = 5;
    public function index(Request $request, int $scheduleId)
    {
        $schedule = Schedule::with('group.students')->findOrFail($scheduleId);
        $students = $schedule->group->students;
        $dates = collect();

        // Получение дней недели, на которые запланированы занятия по предмету
        $scheduledDays = Schedule::where('id', $scheduleId)->pluck('day_of_week')->unique();

        // Генерация дат на основе расписания
        for ($i = $this->daysBefore; $i <= $this->daysAfter; $i++) {
            // если надо на все дни
            $dates->push(Carbon::today()->addDays($i)->format('d.m.y'));
            // $date = Carbon::today()->addDays($i);
            // if ($scheduledDays->contains(strtolower($date->format('l')))) {
            //     $dates->push($date->format('d.m.y'));
            // }
        }

        $attendances = Attendance::where('schedule_id', $scheduleId)
            ->whereIn('date', $dates->map(function ($date) {
                return Carbon::createFromFormat('d.m.y', $date)->format('Y-m-d');
            }))
            ->get()
            ->groupBy(function ($attendance) {
                return Carbon::parse($attendance->date)->format('d.m.y');
            });

        $today = $this->getTodayDate('d.m.y');

        return view('teacher.attendance', compact('schedule', 'students', 'dates', 'attendances', 'today'));
    }

    public function store(Request $request, $scheduleId)
    {
        $today = $this->getTodayDate('Y-m-d');

        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.*.student_id' => 'required|exists:students,id',
            'attendances.*.*.date' => 'required|date',
            'attendances.*.*.is_present' => 'required|boolean',
        ]);
    
        foreach ($request->attendances as $studentAttendances) {
            foreach ($studentAttendances as $attendanceData) {
                if ($attendanceData['date'] !== $today) {
                    continue; // Пропускаем данные не за текущую дату
                }
    
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
        }

        return response()->json(['success' => true]);
    }

    private function getTodayDate(string $format)
    {
        return Carbon::today()->format($format);
    }
}
