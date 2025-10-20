<?php

namespace Controller;

use Src\View;
use Src\Request;
use Model\User;
use Src\Auth\Auth;

class LibrarianController
{
    public function createLibrarian(Request $request): string
    {
        // Проверка прав — только админ
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            app()->route->redirect('/login');
        }

        // Если это POST-запрос - обрабатываем отправку формы
        if ($request->method === 'POST') {
            $data = $request->all();

            // Проверка обязательных полей
            if (empty($data['name']) || empty($data['login']) || empty($data['password'])) {
                return new View('site/create_librarian', [
                    'message' => 'Все поля обязательны!'
                ]);
            }

            // Проверка уникальности логина
            if (User::where('login', $data['login'])->exists()) {
                return new View('site/create_librarian', [
                    'message' => 'Такой логин уже существует!'
                ]);
            }

            // Хешируем пароль перед сохранением
            $data['password'] = md5($data['password']);

            // Создание библиотекаря
            User::create([
                'name'     => $data['name'],
                'login'    => $data['login'],
                'password' => $data['password'],
                'role'     => 'librarian',
            ]);

            return new View('site/create_librarian', [
                'message' => 'Библиотекарь успешно добавлен!'
            ]);
        }

        // Если GET-запрос - просто отображаем форму
        return new View('site/create_librarian');
    }

    public function listLibrarians(): string
    {
        // Только админ может просматривать список
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            app()->route->redirect('/login');
        }

        // Получаем всех пользователей с ролью librarian
        $librarians = User::where('role', 'librarian')->get();

        return new View('site/list_librarian', [
            'librarians' => $librarians
        ]);
    }
}
