<?php

namespace App\Http\Controllers\Subject;

use App\Http\Controllers\Controller;
use App\Models\GroupSubject;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function createSubject(Request $request, ?int $groupId): JsonResponse
    {
        $request->validate([
            'group_id' => 'nullable|exists:groups,id',
            'subject_name' => 'required|string|max:255',
        ]);

        $subject = Subject::create([
            'name' => $request->subject_name,
            'author_id' => auth()->id(),
        ]);

        if (!empty($request->group_id)) {
            GroupSubject::create([
                'group_id' => $request->group_id,
                'subject_id' => $subject->id,
                'author_id' => auth()->id(),
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function deleteSubject(int $subjectId): JsonResponse
    {
        $subject = Subject::findOrFail($subjectId);
        $subject->delete();

        return response()->json(['success' => true]);
    }
}
