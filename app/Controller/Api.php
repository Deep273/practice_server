<?php

namespace Controller;

use Model\Book;
use Src\Auth\Auth;
use Src\Request;
use Src\Response;
use Src\View;

class Api
{
    public function index(): void
    {
        $books = Book::all()->toArray();
        (new View())->toJSON($books);
    }

    public function echo(Request $request): void
    {
        (new View())->toJSON($request->all());
    }

    public function login(Request $request): void
    {
        $data = $request->all();

        // Проверка наличия логина и пароля
        if (empty($data['login']) || empty($data['password'])) {
            (new View())->toJSON([
                'status' => 'error',
                'message' => 'Необходимо указать логин и пароль'
            ], 400); // 400 Bad Request
            return;
        }

        // Аутентификация
        if (!Auth::attempt($data)) {
            (new View())->toJSON([
                'status' => 'error',
                'message' => 'Неверный логин или пароль'
            ], 400); // 400 Bad Request
            return;
        }

        $user = Auth::user();
        Auth::generateToken($user);
        $user->refresh();

        // Возвращаем успешный ответ
        (new View())->toJSON([
            'status' => 'success',
            'token' => $user->api_token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ],
        ], 200); // 200 OK
    }
}