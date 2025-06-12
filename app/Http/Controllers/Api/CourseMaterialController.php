<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\CourseMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseMaterialController extends Controller
{
    use RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante'])) {
            return $response;
        }

        $materials = CourseMaterial::all();
        return response()->json([
            'success' => true,
            'data' => $materials
        ]);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'section_id' => 'required|exists:course_sections,id',
            'published_by' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,document,link',
            'url' => 'required|url',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $material = CourseMaterial::create([
            'section_id' => $request->section_id,
            'published_by' => $request->published_by,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'url' => $request->url,
            'published_at' => $request->published_at,
        ]);

        return response()->json([
            'success' => true,
            'data' => $material
        ], 201);
    }

    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $material = CourseMaterial::find($id);

        if (!$material) {
            return response()->json([
                'success' => false,
                'message' => 'material not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $material
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $material = CourseMaterial::find($id);

        if (!$material) {
            return response()->json([
                'success' => false,
                'message' => 'material not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'section_id' => 'exists:course_sections,id',
            'published_by' => 'exists:users,id',
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'type' => 'in:video,document,link',
            'url' => 'url',
            'published_at' => 'date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $material->update($request->only([
            'section_id',
            'published_by',
            'title',
            'description',
            'type',
            'url',
            'published_at',
        ]));

        return response()->json([
            'success' => true,
            'data' => $material
        ]);
    }

    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $material = CourseMaterial::find($id);

        if (!$material) {
            return response()->json([
                'success' => false,
                'message' => 'material not found'
            ], 404);
        }

        $material->delete();

        return response()->json([
            'success' => true,
            'message' => 'material deleted successfully'
        ]);
    }
}
