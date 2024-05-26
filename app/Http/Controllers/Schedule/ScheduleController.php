<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupSubject;
use App\Models\Schedule;
use App\Models\Student;
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
        return view('schedule.monday', compact('groups', 'schedule'));
    }

    public function tuesday(Request $request)
    {
        $groups = $this->getGroups();
        $schedule = $this->getScheduleForDay('tuesday');
        return view('schedule.tuesday', compact('groups', 'schedule'));
    }

    public function wednesday(Request $request)
    {
        $groups = $this->getGroups();
        $schedule = $this->getScheduleForDay('wednesday');
        return view('schedule.wednesday', compact('groups', 'schedule'));
    }

    public function thursday(Request $request)
    {
        $groups = $this->getGroups();
        $schedule = $this->getScheduleForDay('thursday');
        return view('schedule.thursday', compact('groups', 'schedule'));
    }

    public function friday(Request $request)
    {
        $groups = $this->getGroups();
        $schedule = $this->getScheduleForDay('friday');
        return view('schedule.friday', compact('groups', 'schedule'));
    }

    public function saturday(Request $request)
    {
        $groups = $this->getGroups();
        $schedule = $this->getScheduleForDay('saturday');
        return view('schedule.saturday', compact('groups', 'schedule'));
    }
    
    private function getGroups()
    {
        return Group::all();
    }

    private function getScheduleForDay(string $dayOfWeek)
    {
        $schedules = Schedule::with(['group', 'subject'])
            ->where('user_id', auth()->id())
            ->where('day_of_week', $dayOfWeek)
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
                // Проверка уникальности
                // Rule::unique('schedules')->where(function ($query) use ($request) {
                //     return $query->where('user_id', auth()->id())
                //                  ->where('group_id', $request->group_id)
                //                  ->where('subject_id', $request->subject_id)
                //                  ->where('day_of_week', $request->day_of_week);
                // })
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

    // public function index()
    // {
    //     $schedules = Schedule::with(['group', 'subject'])
    //         ->where('user_id', auth()->id())
    //         ->get();
    //     return view('schedules.index', compact('schedules'));
    // }
}
