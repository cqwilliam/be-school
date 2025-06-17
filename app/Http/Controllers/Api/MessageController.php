<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    use RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $messages = Message::all();
        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'sender_id' => 'required|exists:users,id',
            'recipient_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'is_read' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $message = Message::create([
            'sender_id' => $request->sender_id,
            'recipient_id' => $request->recipient_id,
            'content' => $request->content,
            'is_read' => $request->is_read ?? false,
        ]);

        return response()->json([
            'success' => true,
            'data' => $message
        ], 201);
    }

    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $message = Message::find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'message not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante'])) {
            return $response;
        }

        $message = Message::find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'message not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'sender_id' => 'exists:users,id',
            'recipient_id' => 'exists:users,id',
            'content' => 'string',
            'is_read' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $message->update($request->only([
            'sender_id',
            'recipient_id',
            'content',
            'is_read',
        ]));

        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante'])) {
            return $response;
        }

        $message = Message::find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'message not found'
            ], 404);
        }

        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'message deleted successfully'
        ]);
    }
}
