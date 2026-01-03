<?php

namespace App\Services\v1\Auth;

class PasswordService
{
    /**
     * Verifica senha
     */
    public function verify(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    /**
     * Gera hash (quando criar usuário)
     */
    public function hash(string $plain): string
    {
        return password_hash($plain, PASSWORD_DEFAULT);
    }
}