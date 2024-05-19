<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function index()
    {
        $faculties = Faculty::with('departments')->orderBy('created_at', 'desc')->get();

        return view('dashboard', compact('faculties'));
    }

    public function getFaculties()
    {
        $faculties = Faculty::with('departments')->get();

        return response()->json(['faculties' => $faculties], 200);
    }

    public function createFaculty(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $faculty = Faculty::create([
            'name' => $request->name,
            'author_id' => auth()->id(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Faculty created successfully');
    }

    public function deleteFaculty(int $facultyId): JsonResponse
    {
        $faculty = Faculty::findOrFail($facultyId);
        $faculty->delete();

        return response()->json(['success' => true]);
        // return redirect()->route('dashboard')->with('success', 'Faculty deleted successfully');
    }

    public function createFacultyWithDepartment(Request $request): RedirectResponse
    {
        $request->validate([
            'faculty_name' => 'required|string|max:255',
            'department_name.*' => 'required|string|max:255',
        ]);

        // foreach (range(1, 30) as $number) {
            $faculty = Faculty::create([
                'name' => $request->faculty_name,
                'author_id' => auth()->id(),
            ]);
    
            foreach ($request->department_name as $departmentName) {
                Department::create([
                    'name' => $departmentName,
                    'faculty_id' => $faculty->id,
                    'author_id' => auth()->id(),
                ]);
            }
        // }

        return redirect()->route('dashboard')->with('success', 'Faculty with departments created successfully');
    }

    public function getDepartments(int $facultyId)
    {
        $faculty = Faculty::findOrFail($facultyId);
        $departments = $faculty->departments;

        return response()->json(['departments' => $departments], 200);
    }

    public function createDepartment(Request $request, int $facultyId): RedirectResponse
    {
        $faculty = Faculty::findOrFail($facultyId);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $department = $faculty->departments()->create([
            'name' => $request->name,
            'author_id' => auth()->id(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Department created successfully');
    }

    public function deleteDepartment(int $departmentId): JsonResponse
    {
        $department = Department::findOrFail($departmentId);
        $department->delete();

        return response()->json(['success' => true]);
        // return redirect()->route('dashboard')->with('success', 'Department deleted successfully');
    }
}