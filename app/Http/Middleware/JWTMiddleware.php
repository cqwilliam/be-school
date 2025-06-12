<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\JWTService;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class JWTMiddleware
{
    protected $jwtService;

    public function __construct(JWTService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $payload = $this->jwtService->verifyJWT($token);

        if (!$payload) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        // Verificar que el usuario existe
        $user = User::find($payload->sub);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 401);
        }

        // Agregar el usuario al request para uso posterior
        $request->merge(['auth_user' => $user]);

        return $next($request);
    }
}
