<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssignmentSubmissionController extends Controller
{
    public function index()
    {
        $submissions = AssignmentSubmission::all();
        return response()->json([
            'success' => true,
            'data' => $submissions
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'assignment_id' => 'required|exists:assignments,id',
            'student_id' => 'required|exists:students,id',
            'file_url' => 'nullable|string',
            'comment' => 'nullable|string',
            'submitted_at' => 'nullable|date',
            'grade' => 'nullable|numeric|min:0|max:20',
            'feedback' => 'nullable|string',
            'graded_by' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $submissions = AssignmentSubmission::create([
            'assignment_id' => $request->assignment_id,
            'student_id' => $request->student_id,
            'file_url' => $request->file_url,
            'comment' => $request->comment,
            'submitted_at' => $request->submitted_at,
            'grade' => $request->grade,
            'feedback' => $request->feedback,
            'graded_by' => $request->graded_by,
        ]);

        return response()->json([
            'success' => true,
            'data' => $submissions
        ], 201);
    }

    public function show($id)
    {
        $submissions = AssignmentSubmission::find($id);

        if (!$submissions) {
            return response()->json([
                'success' => false,
                'message' => 'submissions not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $submissions
        ]);
    }

    public function update(Request $request, $id)
    {
        $submission = AssignmentSubmission::find($id);

        if (!$submission) {
            return response()->json([
                'success' => false,
                'message' => 'submission not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'assignment_id' => 'exists:assignments,id',
            'student_id' => 'exists:students,id',
            'file_url' => 'nullable|string',
            'comment' => 'nullable|string',
            'submitted_at' => 'nullable|date',
            'grade' => 'nullable|numeric|min:0|max:20',
            'feedback' => 'nullable|string',
            'graded_by' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $submission->update($request->only([
            'assignment_id',
            'student_id',
            'file_url',
            'comment',
            'submitted_at',
            'grade',
            'feedback',
            'graded_by',
        ]));

        return response()->json([
            'success' => true,
            'data' => $submission
        ]);
    }

    public function destroy($id)
    {
        $submission = AssignmentSubmission::find($id);

        if (!$submission) {
            return response()->json([
                'success' => false,
                'message' => 'submission not found'
            ], 404);
        }

        $submission->delete();

        return response()->json([
            'success' => true,
            'message' => 'submission deleted successfully'
        ]);
    }
}
