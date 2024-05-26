<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupSubject;
use App\Models\Schedule;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        return view('teacher.schedule.schedule');
    }

    public function monday(Request $request)
    {
        $groups = $this->getGroups();
        $schedule = $this->getScheduleForDay('monday');
        return view('teacher.schedule.scheduleDays.monday', compact('groups', 'schedule'));
    }

    public function tuesday(Request $request)
    {
        $groups = $this->getGroups();
        $schedule = $this->getScheduleForDay('tuesday');
        return view('teacher.schedule.scheduleDays.tuesday', compact('groups', 'schedule'));
    }

    public function wednesday(Request $request)
    {
        $groups = $this->getGroups();
        $schedule = $this->getScheduleForDay('wednesday');
        return view('teacher.schedule.scheduleDays.wednesday', compact('groups', 'schedule'));
    }

    public function thursday(Request $request)
    {
        $groups = $this->getGroups();
        $schedule = $this->getScheduleForDay('thursday');
        return view('teacher.schedule.scheduleDays.thursday', compact('groups', 'schedule'));
    }

    public function friday(Request $request)
    {
        $groups = $this->getGroups();
        $schedule = $this->getScheduleForDay('friday');
        return view('teacher.schedule.scheduleDays.friday', compact('groups', 'schedule'));
    }

    public function saturday(Request $request)
    {
        $groups = $this->getGroups();
        $schedule = $this->getScheduleForDay('saturday');
        return view('teacher.schedule.scheduleDays.saturday', compact('groups', 'schedule'));
    }

    public function getSubjectsByDate(Request $request)
    {
        $date = Carbon::parse($request->date);
        $dayOfWeek = strtolower($date->format('l')); // Получаем день недели (monday, tuesday и т.д.)

        // Получение расписания на указанный день недели для текущего пользователя
        $schedules = Schedule::with(['group', 'subject'])
            ->where('user_id', auth()->id())
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time', 'asc')
            ->get();

        return response()->json(['data' => $schedules]);
    }
    
    private function getGroups()
    {
        return Group::orderBy('name', 'asc')->get();
    }

    private function getScheduleForDay(string $dayOfWeek)
    {
        $schedules = Schedule::with(['group', 'subject'])
            ->where('user_id', auth()->id())
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time', 'asc')
            ->get();
        return $schedules;
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'subject_id' => 'required|exists:subjects,id',
            'day_of_week' => [
                'required',
                'string',
                Rule::in(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']),
            ],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        Schedule::create([
            'user_id' => auth()->id(),
            'group_id' => $request->group_id,
            'subject_id' => $request->subject_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteSchedule(int $id): JsonResponse
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return response()->json(['success' => true]);
    }
}
