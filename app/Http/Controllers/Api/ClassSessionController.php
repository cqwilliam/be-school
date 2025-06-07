<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassSessionController extends Controller
{
    /**
     * Display a listing of the class sessions.
     */
    public function index()
    {
        $classSessions = ClassSession::all();
        
        return response()->json([
            'success' => true,
            'data' => $classSessions
        ]);
    }

    /**
     * Store a newly created class session.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_id' => 'required|exists:course_sections,id',
            'topic' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'created_by' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $classSession = ClassSession::create([
            'section_id' => $request->section_id,
            'topic' => $request->topic,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'created_by' => $request->created_by,
        ]);

        return response()->json([
            'success' => true,
            'data' => $classSession
        ], 201);
    }

    /**
     * Display the specified class session.
     */
    public function show($id)
    {
        $classSession = ClassSession::find($id);

        if (!$classSession) {
            return response()->json([
                'success' => false,
                'message' => 'Class session not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $classSession
        ]);
    }

    /**
     * Update the specified class session.
     */
    public function update(Request $request, $id)
    {
        $classSession = ClassSession::find($id);

        if (!$classSession) {
            return response()->json([
                'success' => false,
                'message' => 'Class session not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'section_id' => 'exists:course_sections,id',
            'topic' => 'nullable|string',
            'date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date|after:start_date',
            'created_by' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $classSession->update($request->only([
            'section_id',
            'topic',
            'date',
            'start_time',
            'end_time',
            'created_by',
        ]));

        return response()->json([
            'success' => true,
            'data' => $classSession
        ]);
    }

    /**
     * Remove the specified class session.
     */
    public function destroy($id)
    {
        $classSession = ClassSession::find($id);
        
        if (!$classSession) {
            return response()->json([
                'success' => false,
                'message' => 'classSession not found'
            ], 404);
        }

        $classSession->delete();

        return response()->json([
            'success' => true,
            'message' => 'classSession deleted successfully'
        ]);
    }
}
