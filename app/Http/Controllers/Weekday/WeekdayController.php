<?php

namespace App\Http\Controllers\Weekday;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeekdayController extends Controller
{
    public function monday(Request $request)
    {
        return view('weekday.monday');
    }

    public function tuesday(Request $request)
    {
        return view('weekday.tuesday');
    }

    public function wednesday(Request $request)
    {
        return view('weekday.wednesday');
    }

    public function thursday(Request $request)
    {
        return view('weekday.thursday');
    }

    public function friday(Request $request)
    {
        return view('weekday.friday');
    }

    public function saturday(Request $request)
    {
        return view('weekday.saturday');
    }
}
