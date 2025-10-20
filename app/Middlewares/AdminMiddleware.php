<?php

namespace Middlewares;

use Src\Auth\Auth;
use Src\Response;

class AdminMiddleware
{
    public function handle($request): ?object
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            (new Response([
                'status' => 'error',
                'message' => 'Доступ запрещен. Только администраторы могут выполнять это действие.'
            ]))->json(403);
            exit;
        }

        return $request;
    }
}