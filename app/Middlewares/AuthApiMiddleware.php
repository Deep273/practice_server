<?php

namespace Middlewares;


use Src\Auth\Auth;
use Src\Request;
use Src\Response;

class AuthApiMiddleware
{
    public function handle(Request $request): ?Request
    {
        $authHeader = $request->headers['Authorization'] ?? '';

        if (!preg_match('/Bearer\s+(\S+)/', $authHeader, $matches)) {
            (new Response([
                'status' => 'error',
                'message' => 'Отсутствует токен авторизации'
            ]))->json(401);
            exit;
        }

        $token = $matches[1];
        $user = Auth::attemptToken($token);

        if (!$user) {
            (new Response([
                'status' => 'error',
                'message' => 'Недействительный токен'
            ]))->json(401);
            exit;
        }

        // Авторизация успешна
        Auth::login($user);
        return $request;
    }
}