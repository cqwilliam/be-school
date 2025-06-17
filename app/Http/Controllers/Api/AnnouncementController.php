<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    use RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }
        $announcements = Announcement::all();
        return response()->json([
            'success' => true,
            'data' => $announcements
        ]);
    }


    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target' => 'required|string',
            'section_id' => 'nullable|exists:course_sections,id',
            'published_by' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'target' => $request->target,
            'section_id' => $request->section_id,
            'published_by' => $request->published_by,
        ]);

        return response()->json([
            'success' => true,
            'data' => $announcement
        ], 201);
    }

    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $announcement = Announcement::find($id);

        if (!$announcement) {
            return response()->json([
                'success' => false,
                'message' => 'announcement not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $announcement
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente'])) {
            return $response;
        }

        $announcement = Announcement::find($id);

        if (!$announcement) {
            return response()->json([
                'success' => false,
                'message' => 'announcement not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'content' => 'string',
            'target' => 'string',
            'section_id' => 'exists:course_sections,id',
            'published_by' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $announcement->update($request->only([
            'title',
            'content',
            'target',
            'section_id',
            'published_by',
        ]));

        return response()->json([
            'success' => true,
            'data' => $announcement
        ]);
    }

    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente'])) {
            return $response;
        }

        $announcement = Announcement::find($id);

        if (!$announcement) {
            return response()->json([
                'success' => false,
                'message' => 'announcement not found'
            ], 404);
        }

        $announcement->delete();

        return response()->json([
            'success' => true,
            'message' => 'announcement deleted successfully'
        ]);
    }
}
