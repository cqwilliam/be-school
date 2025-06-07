<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::all();
        return response()->json([
            'success' => true,
            'data' => $assignments
        ]);
    }

    // Crear una nueva tarea
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_id' => 'required|exists:course_sections,id',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'published_at' => 'nullable|date',
            'due_date' => 'required|date|after_or_equal:published_at',
            'published_by' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $assignments = Assignment::create([
            'section_id' => $request->section_id,
            'title' => $request->title,
            'description' => $request->description,
            'published_at' => $request->published_at,
            'due_date' => $request->due_date,
            'published_by' => $request->published_by,
        ]);

        return response()->json([
            'success' => true,
            'data' => $assignments
        ], 201);
    }

    // Mostrar una tarea específica
    public function show($id)
    {
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

    // Actualizar una tarea
    public function update(Request $request, $id)
    {
        $assignment = Assignment::find($id);
        
        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:100',
            'description' => 'nullable|string',
            'published_at' => 'nullable|date',
            'due_date' => 'date|after_or_equal:published_at',
            'published_by' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $assignment->update($request->only([
            'title',
            'description',
            'published_at',
            'due_date',
            'published_by'
        ]));

        return response()->json([
            'success' => true,
            'data' => $assignment
        ]);
    }

    // Eliminar una tarea
    public function destroy($id)
    {
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
}
