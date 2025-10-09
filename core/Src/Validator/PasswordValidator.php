<?php

namespace Src\Validator;

class PasswordValidator
{
    protected string $password;
    protected array $errors = [];

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function fails(): bool
    {
        $this->errors = []; // сбрасываем на случай повторных проверок

        // 1. Проверка длины не менее 6 символов
        if (strlen($this->password) < 6) {
            $this->errors[] = 'Пароль должен содержать не менее 6 символов';
        }

        // 2. Проверка наличия хотя бы одной буквы (латиница или кириллица)
        if (!preg_match('/[a-zA-Zа-яА-Я]/u', $this->password)) {
            $this->errors[] = 'Пароль должен содержать хотя бы одну букву';
        }

        // 3. Проверка наличия хотя бы одной цифры
        if (!preg_match('/\d/', $this->password)) {
            $this->errors[] = 'Пароль должен содержать хотя бы одну цифру';
        }

        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
