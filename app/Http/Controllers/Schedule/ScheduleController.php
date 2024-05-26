<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupSubject;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function monday(Request $request)
    {
        $groups = $this->getGroups();
        return view('schedule.monday', compact('groups'));
    }

    public function tuesday(Request $request)
    {
        $groups = $this->getGroups();
        return view('schedule.tuesday');
    }

    public function wednesday(Request $request)
    {
        $groups = $this->getGroups();
        return view('schedule.wednesday', compact('groups'));
    }

    public function thursday(Request $request)
    {
        $groups = $this->getGroups();
        return view('schedule.thursday', compact('groups'));
    }

    public function friday(Request $request)
    {
        $groups = $this->getGroups();
        return view('schedule.friday', compact('groups'));
    }

    public function saturday(Request $request)
    {
        $groups = $this->getGroups();
        return view('schedule.saturday', compact('groups'));
    }
    
    private function getGroups()
    {
        return Group::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'subject_id' => 'required|exists:subjects,id',
            'day_of_week' => 'required|string',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
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
        // return redirect()->route('schedules.index')->with('success', 'Schedule created successfully');
    }

    public function index()
    {
        $schedules = Schedule::with(['group', 'subject'])
            ->where('user_id', auth()->id())
            ->get();
        return view('schedules.index', compact('schedules'));
    }
}
