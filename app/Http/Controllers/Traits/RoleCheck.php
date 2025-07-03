<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait RoleCheck

{
    private function checkRole(Request $request, array $allowedRoles)
    {
        $user = $request->auth_user;

        // $user->load('role');

        if (!in_array($user->role->name ?? '', $allowedRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return null;
    }
}
