<?php

namespace App\Services\v1\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenService
{
    protected $secret;
    protected $algo = 'HS256';
    protected $ttl; // em segundos

    public function __construct()
    {
        $this->secret = getenv('JWT_SECRET') ?: env('JWT_SECRET', 'change_me');
        $this->ttl = (int) (getenv('JWT_TTL') ?: 3600);
    }

    public function createToken(array $claims = []): string
    {
        $now = time();
        $payload = array_merge([
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + $this->ttl,
        ], $claims);

        return JWT::encode($payload, $this->secret, $this->algo);
    }

    public function decodeToken(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key($this->secret, $this->algo));
        } catch (\Throwable $e) {
            return null;
        }
    }
}