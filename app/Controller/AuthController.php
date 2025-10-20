<?php

namespace Controller;

use Src\View;
use Src\Request;
use Src\Auth\Auth;
use Model\User;

class AuthController
{
    public function signup(Request $request): string
    {
        if ($request->method === 'POST') {
            $data = $request->all();

            $validator = new \Src\Validator\Validator(
                $data,
                [
                    'name'     => ['required'],
                    'login'    => ['required', 'unique:users,login'],
                    'password' => ['required'],
                ],
                [
                    'required' => 'Поле :field пусто',
                    'unique'   => 'Поле :field должно быть уникальным',
                ]
            );

            $passwordValidator = new \Src\Validator\PasswordValidator($data['password'] ?? '');

            if ($validator->fails() || $passwordValidator->fails()) {
                $errors = $validator->errors();

                if ($passwordValidator->fails()) {
                    $errors['password'] = array_merge(
                        $errors['password'] ?? [],
                        $passwordValidator->errors()
                    );
                }

                return new View('site.signup', [
                    'errors' => $errors,
                    'old'    => $data,
                    'message' => 'Проверьте правильность заполнения полей.'
                ]);
            }

            $data['password'] = md5($data['password']);

            if (User::create($data)) {
                app()->route->redirect('/login');
            }

            return new View('site.signup', [
                'message' => 'Ошибка при создании пользователя. Попробуйте позже.',
                'old'     => $data
            ]);
        }

        return new View('site.signup');
    }

    public function login(Request $request): string
    {
        if ($request->method === 'GET') {
            return new View('site.login');
        }

        $validator = new \Src\Validator\Validator(
            $request->all(),
            [
                'login' => ['required'],
                'password' => ['required'],
            ],
            ['required' => 'Поле :field пусто']
        );

        if ($validator->fails()) {
            return new View('site.login', ['errors' => $validator->errors()]);
        }

        if (Auth::attempt($request->all())) {
            app()->route->redirect('/hello');
        }

        return new View('site.login', ['message' => 'Неправильные логин или пароль']);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/hello');
    }
}
