<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    use RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante'])) {
            return $response;
        }

        $assignments = Assignment::all();
        return response()->json([
            'success' => true,
            'data' => $assignments
        ]);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'teacher_user_id' => 'required|exists:users,id',
            'period_section_id' => 'required|exists:periods_sections,id',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after:published_at',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }


        $assignments = Assignment::create([
            'teacher_user_id' => $request->teacher_user_id,
            'period_section_id' => $request->period_section_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $assignments
        ], 201);
    }

    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $assignment = Assignment::find($id);

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $assignment
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $assignment = Assignment::find($id);

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'teacher_user_id' => 'exists:users,id',
            'period_section_id' => 'exists:periods_sections,id',
            'title' => 'string|max:100',
            'description' => 'nullable|string',
            'due_date' => 'date|after:published_at',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $assignment->update($request->only([
            'teacher_user_id',
            'period_section_id',
            'title',
            'description',
            'due_date',
        ]));

        return response()->json([
            'success' => true,
            'data' => $assignment
        ]);
    }

    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $assignment = Assignment::find($id);

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        $assignment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }


    public function getBySectionId($section_id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante'])) {
            return $response;
        }

        $validator = Validator::make(['section_id' => $section_id], [
            'section_id' => 'required|exists:course_sections,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $assignments = Assignment::where('period_section_id', $section_id)->get();

        return response()->json([
            'success' => true,
            'data' => $assignments
        ]);
    }
}
