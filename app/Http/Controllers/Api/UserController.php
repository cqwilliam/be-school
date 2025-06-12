<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante'])) {
            return $response;
        }

        //$users = User::all();
        $users = User::with('role')->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

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

        $validated['password'] = Hash::make($request->password);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'dni' => $request->dni,
            'birth_date' => $request->birth_date,
            'photo_url' => $request->photo_url,
            'phone' => $request->phone,
            'address' => $request->address,
            'role_id' => $request->role_id,
        ]);

        return response()->json([
            'success' => true,
            'data' => $user
        ], 201);
    }

    public function show(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

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
        $user = User::findOrFail($id);

        $request->validate([
            'current_password' => 'required_with:password',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La contraseña actual no es correcta.'
                ], 422);
            }

            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente.',
            'user' => $user
        ]);
    }

    public function destroy(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
