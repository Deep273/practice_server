<?php

namespace Src\Auth;

use Src\Session;
use Model\User;

class Auth
{
    private static IdentityInterface $user;

    public static function init(IdentityInterface $user): void
    {
        self::$user = $user;
        if (self::user()) {
            self::login(self::user());
        }
    }

    public static function login(IdentityInterface $user): void
    {
        self::$user = $user;
        Session::set('id', self::$user->getId());
    }

    public static function attempt(array $credentials): bool
    {
        if ($user = self::$user->attemptIdentity($credentials)) {
            self::login($user);
            return true;
        }
        return false;
    }

    public static function user()
    {
        $id = Session::get('id') ?? 0;
        return self::$user->findIdentity($id);
    }

    public static function check(): bool
    {
        return (bool) self::user();
    }

    public static function logout(): bool
    {
        Session::clear('id');
        return true;
    }

    public static function attemptToken(string $token): ?User
    {
        return User::where('api_token', $token)->first();
    }

    // 🔹 Генерация токена при успешном входе
    public static function generateToken(User $user): string
    {
        $token = bin2hex(random_bytes(30));
        $user->api_token = $token;
        $user->save(); // сохраняем в БД
        return $token;
    }


    //Генерация нового токена для CSRF
    public static function generateCSRF(): string
    {
        $token = md5(time());
        Session::set('csrf_token', $token);
        return $token;
    }
}