<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GradeController extends Controller
{
    /**
     * Display a listing of the grades.
     */
    public function index()
    {
        $grades = Grade::all();
        return response()->json([
            'success' => true,
            'data' => $grades
        ]);
    }


    /**
     * Store a newly created grade.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'evaluation_id' => 'required|exists:evaluations,id',
            'student_id' => 'required|exists:students,id',
            'graded_by' => 'nullable|exists:users,id',
            'score' => 'required|numeric|min:0|max:20',
            'comment' => 'nullable|string',
            'graded_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $grades = Grade::create([
            'evaluation_id' => $request->evaluation_id,
            'student_id' => $request->student_id,
            'graded_by' => $request->graded_by,
            'score' => $request->score,
            'comment' => $request->comment,
            'graded_at' => $request->graded_at,
        ]);

        return response()->json([
            'success' => true,
            'data' => $grades
        ], 201);
    }

    /**
     * Display the specified grade.
     */
    public function show($id)
    {
        $grades = Grade::find($id);

        if (!$grades) {
            return response()->json([
                'success' => false,
                'message' => 'grades not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $grades
        ]);
    }

    /**
     * Update the specified grade.
     */
    public function update(Request $request, $id)
    {
        $grade = Grade::find($id);

        if (!$grade) {
            return response()->json([
                'success' => false,
                'message' => 'grades not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'evaluation_id' => 'exists:evaluations,id',
            'student_id' => 'exists:students,id',
            'graded_by' => 'nullable|exists:users,id',
            'score' => 'numeric|min:0|max:20',
            'comment' => 'nullable|string',
            'graded_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $grade->update($request->only([
            'evaluation_id',
            'student_id',
            'graded_by',
            'score',
            'comment',
            'graded_at',
        ]));

        return response()->json([
            'success' => true,
            'data' => $grade
        ]);
    }

    /**
     * Remove the specified grade.
     */
    public function destroy($id)
    {
        $grade = Grade::find($id);

        if (!$grade) {
            return response()->json([
                'success' => false,
                'message' => 'grade not found'
            ], 404);
        }

        $grade->delete();

        return response()->json([
            'success' => true,
            'message' => 'grade deleted successfully'
        ]);
    }
}
