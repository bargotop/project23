<?php

namespace App\Http\Controllers\Subject;

use App\Http\Controllers\Controller;
use App\Models\GroupSubject;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubjectController extends Controller
{
    public function createSubject(Request $request, int $groupId): JsonResponse
    {
        $request->validate([
            // 'group_id' => 'nullable|exists:groups,id',
            'subject_name' => 'required|string|max:255',
        ]);

        $subject = Subject::create([
            'name' => $request->subject_name,
            'author_id' => auth()->id(),
        ]);

        GroupSubject::create([
            'group_id' => $groupId,
            'subject_id' => $subject->id,
            'author_id' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteSubject(int $id): JsonResponse
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return response()->json(['success' => true]);
    }
}
