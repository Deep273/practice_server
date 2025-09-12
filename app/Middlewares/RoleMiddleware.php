<?php

namespace Middlewares;

use Src\Request;
use Src\Auth\Auth;

class RoleMiddleware
{
    protected array $roles;

    public function __construct(...$roles)
    {
        $this->roles = $roles;
    }

    public function handle(Request $request): bool
    {
        $user = Auth::user();

        if (!$user) {
            header('Location: /login');
            exit;
        }

        if (!in_array($user->role, $this->roles)) {
            echo 'Доступ запрещён';
            exit;
        }

        return true;
    }
}
