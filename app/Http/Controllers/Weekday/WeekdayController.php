<?php

namespace App\Http\Controllers\Weekday;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupSubject;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeekdayController extends Controller
{
    public function monday(Request $request)
    {
        $groups = $this->getGroups();
        return view('weekday.monday', compact('groups'));
    }

    public function tuesday(Request $request)
    {
        $groups = $this->getGroups();
        return view('weekday.tuesday');
    }

    public function wednesday(Request $request)
    {
        $groups = $this->getGroups();
        return view('weekday.wednesday', compact('groups'));
    }

    public function thursday(Request $request)
    {
        $groups = $this->getGroups();
        return view('weekday.thursday', compact('groups'));
    }

    public function friday(Request $request)
    {
        $groups = $this->getGroups();
        return view('weekday.friday', compact('groups'));
    }

    public function saturday(Request $request)
    {
        $groups = $this->getGroups();
        return view('weekday.saturday', compact('groups'));
    }
    
    private function getGroups()
    {
        return Group::all();
    }
}
