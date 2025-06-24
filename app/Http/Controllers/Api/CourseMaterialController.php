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
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,document,pdf,link,image,presentation',
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $material = CourseMaterial::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'url' => $request->url,
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
            'course_id' => 'exists:courses,id',
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,document,pdf,link,image,presentation',
            'url' => 'url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $material->update($request->only([
            'course_id',
            'title',
            'description',
            'type',
            'url',
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

    public function getBySectionId($section_id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante'])) {
            return $response;
        }

        $materials = CourseMaterial::with(['publishedBy', 'courseSection.course'])
            ->where('section_id', $section_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $materials
        ]);
    }
}
