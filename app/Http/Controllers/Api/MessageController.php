<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::all();
        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sender_id' => 'required|exists:users,id',
            'recipient_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'sent_at' => 'required|date',
            'is_read' => 'boolean',
            'read_at' => 'nullable|date',
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
            'sent_at' => $request->sent_at,
            'is_read' => $request->is_read ?? false,
            'read_at' => $request->read_at,
        ]);

        return response()->json([
            'success' => true,
            'data' => $message
        ], 201);
    }

    public function show($id)
    {
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
            'sent_at' => 'date',
            'is_read' => 'boolean',
            'read_at' => 'date',
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
            'sent_at',
            'is_read',
            'read_at',
        ]));

        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    public function destroy($id)
    {
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
