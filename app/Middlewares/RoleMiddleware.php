<?php

namespace Middlewares;

use Src\Auth\Auth;
use Src\Request;

class RoleMiddleware
{
    public function handle(Request $request, string $roles): void
    {
        $allowedRoles = explode(',', $roles);
        $user = app()->auth::user();

        if (!in_array($user->role, $allowedRoles)) {
            http_response_code(403);
            echo 'Доступ запрещён';
            exit();
        }
    }
}
