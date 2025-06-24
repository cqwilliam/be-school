<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\Guardian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GuardianController extends Controller
{
    use RoleCheck;
    /**
     * Display a listing of guardians.
     */
    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $guardians = Guardian::all();

        return response()->json([
            'success' => true,
            'data' => $guardians
        ]);
    }

    /**
     * Store a newly created guardian.
     */
    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|unique:guardians,user_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($request->user_id);
        if ($user->role_id !== 4) { 
            return response()->json([
                'success' => false,
                'message' => 'User is not a Apoderado'
            ], 422);
        }

        $guardian = Guardian::create($validator->validated());


        return response()->json([
            'success' => true,
            'message' => 'Guardian created successfully',
            'data' => $guardian
        ], 201);
    }

    /**
     * Display the specified guardian.
     */
    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $guardian = Guardian::find($id);

        if (!$guardian) {
            return response()->json([
                'success' => false,
                'message' => 'Guardian not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $guardian
        ]);
    }

    /**
     * Update the specified guardian.
     */
    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $guardian = Guardian::find($id);

        if (!$guardian) {
            return response()->json([
                'success' => false,
                'message' => 'Guardian not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|unique:guardians,user_id,' . $guardian->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $guardian->update([
            'user_id' => $request->user_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Guardian updated successfully',
            'data' => $guardian
        ]);
    }

    /**
     * Remove the specified guardian.
     */
    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $guardian = Guardian::find($id);

        if (!$guardian) {
            return response()->json([
                'success' => false,
                'message' => 'Guardian not found'
            ], 404);
        }

        $guardian->delete();

        return response()->json([
            'success' => true,
            'message' => 'Guardian deleted successfully'
        ]);
    }
    // public function getByUserId($user_id, Request $request)
    // {
    //     if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
    //         return $response;
    //     }

    //     $guardian = Guardian::with('user')->where('user_id', $user_id)->first();

    //     if (!$guardian) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Apoderado no encontrado'
    //         ], 404);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'data' => $guardian
    //     ]);
    // }
}
