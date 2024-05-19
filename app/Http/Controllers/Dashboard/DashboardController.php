<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Faculty;

class DashboardController extends Controller
{
    public function index()
    {
        $faculties = Faculty::with('departments')->orderBy('created_at', 'desc')->get();

        return view('dashboard', compact('faculties'));
    }
}
