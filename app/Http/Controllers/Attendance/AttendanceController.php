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
        $schedule = Schedule::with(['group.students' => function ($query) {
            $query->orderBy('full_name', 'asc');
        }])->where('user_id', auth()->id()) ->findOrFail($scheduleId);
        $students = $schedule->group->students;
        $dates = collect();

        // Получение всех записей расписания для группы и предмета
        $scheduledEntries = Schedule::where('group_id', $schedule->group_id)
                                    ->where('subject_id', $schedule->subject_id)
                                    ->where('user_id', auth()->id())
                                    ->get();

        // Генерация дат на основе расписания
        for ($i = $this->daysBefore; $i <= $this->daysAfter; $i++) {
            $date = Carbon::today()->addDays($i);
            foreach ($scheduledEntries as $entry) {
                if (strtolower($date->format('l')) == $entry->day_of_week) {
                    $dates->push([
                        'date' => $date->format('d.m.y'),
                        'start_time' => $entry->start_time,
                        'end_time' => $entry->end_time,
                        'schedule_id' => $entry->id
                    ]);
                }
            }
        }

        $attendances = Attendance::whereIn('schedule_id', $scheduledEntries->pluck('id'))
            ->whereIn('date', $dates->map(function ($item) {
                return Carbon::createFromFormat('d.m.y', $item['date'])->format('Y-m-d');
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
        return Carbon::today(6)->format($format);
        // return Carbon::tomorrow(6)->format($format); // for local test
    }
}
