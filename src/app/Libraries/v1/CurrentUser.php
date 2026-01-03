<?php

namespace App\Libraries\v1;

/**
 * Simple holder para o usuário autenticado na requisição atual.
 * Usar com parcimônia — é uma alternativa simples para injetar dados do Filter
 * nos controllers. Para designs mais testáveis, prefira injeção de dependência.
 */
class CurrentUser
{
    protected static ?array $user = null;

    public static function set(?array $user): void
    {
        self::$user = $user;
    }

    public static function get(): ?array
    {
        return self::$user;
    }

    public static function id(): ?int
    {
        return isset(self::$user['id']) ? (int) self::$user['id'] : null;
    }
}