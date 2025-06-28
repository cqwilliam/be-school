<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\EvaluationGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EvaluationGradeController extends Controller
{
    use RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $grades = EvaluationGrade::all();
        return response()->json([
            'success' => true,
            'data' => $grades
        ]);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'evaluation_id' => 'required|exists:evaluations,id',
            'student_user_id' => 'required|exists:users,id',
            'grade' => 'required|numeric|min:0|max:20',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $grades = EvaluationGrade::create([
            'evaluation_id' => $request->evaluation_id,
            'student_user_id' => $request->student_user_id,
            'grade' => $request->grade,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'data' => $grades
        ], 201);
    }

    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $grades = EvaluationGrade::find($id);

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

    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente'])) {
            return $response;
        }

        $grade = EvaluationGrade::find($id);

        if (!$grade) {
            return response()->json([
                'success' => false,
                'message' => 'grades not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'evaluation_id' => 'exists:evaluations,id',
            'student_user_id' => 'exists:users,id',
            'grade' => 'numeric|min:0|max:20',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $grade->update($request->only([
            'evaluation_id',
            'student_user_id',
            'grade',
            'comment',
        ]));

        return response()->json([
            'success' => true,
            'data' => $grade
        ]);
    }

    /**
     * Remove the specified grade.
     */
    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $grade = EvaluationGrade::find($id);

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
    public function getStudentGrades($student_id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $grades = EvaluationGrade::with([
            'evaluation.courseSection.course',
            'evaluation.evaluationType',
            'gradedBy'
        ])
            ->where('student_user_id', $student_id)
            ->whereHas('evaluation.academicPeriod', function ($query) {
                $query->where('active', true);
            })
            ->orderBy('graded_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $grades
        ]);
    }

    public function getStudentGradesBySection($student_id, $section_id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $grades = EvaluationGrade::with([
            'evaluation.evaluationType',
            'gradedBy'
        ])
            ->whereHas('evaluation', function ($query) use ($section_id) {
                $query->where('section_id', $section_id);
            })
            ->where('student_user_id', $student_id)
            ->orderBy('graded_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $grades
        ]);
    }
}
