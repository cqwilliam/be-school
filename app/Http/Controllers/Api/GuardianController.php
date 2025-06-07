<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GuardianController extends Controller
{
    /**
     * Display a listing of guardians.
     */
    public function index()
    {
        //$guardians = Guardian::with('students')->get();
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
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|unique:guardians,user_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $guardian = Guardian::create([
            'user_id' => $request->user_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Guardian created successfully',
            'data' => $guardian
        ], 201);
    }

    /**
     * Display the specified guardian.
     */
    public function show($id)
    {
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
    public function destroy($id)
    {
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
}
