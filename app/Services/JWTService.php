<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{
    public function createJWT(User $user): string
    {
        $payload = [
            'exp' => time() + (60 * 60 * 24),
            'sub' => $user->id,
        ];

        return JWT::encode($payload, config('app.key'), 'HS256');
    }

    public function verifyJWT(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key(config('app.key'), 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }
}