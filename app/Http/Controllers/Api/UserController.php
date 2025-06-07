<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        //$users = User::with('role')->paginate(10);
        $users = User::all();
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'user_name' => 'nullable|string|max:50',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8',
            'dni' => 'required|string|max:20|unique:users',
            'birth_date' => 'nullable|date',
            'photo_url' => 'nullable|url|max:2048',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($request->hasFile('photo_url')) {
            $validated['photo_url'] = $request->file('photo_url')->store('profile_photos', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'data' => $user
        ], 201);
    }

    public function show($id)
    {
        $users = User::find($id);

        if (!$users) {
            return response()->json([
                'success' => false,
                'message' => 'users not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'string|max:100',
            'last_name' => 'string|max:100',
            'user_name' => 'nullable|string|max:50',
            'email' => 'string|email|max:100|unique:users',
            'password' => 'string|min:8',
            'dni' => 'string|max:20|unique:users',
            'birth_date' => 'nullable|date',
            'photo_url' => 'nullable|url|max:2048',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role_id' => 'exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($request->only('first_name', 'last_name', 'user_name', 'email', 'password', 'dni', 'birth_date', 'photo_url', 'phone', 'address', 'role_id'));

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }
}
