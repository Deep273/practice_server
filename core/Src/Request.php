<?php

namespace Src;

use Error;

class Request
{
    protected array $body;
    public string $method;
    public array $headers;

    public function __construct()
    {
        $this->body = $_REQUEST ?? [];
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->headers = function_exists('getallheaders') ? (getallheaders() ?: []) : [];
    }

    // Получить все параметры + файлы
    public function all(): array
    {
        return $this->body + $this->files();
    }

    // Установить значение
    public function set($field, $value): void
    {
        $this->body[$field] = $value;
    }

    // Безопасно получить значение (если нет — вернёт null или дефолтное)
    public function get($field, $default = null)
    {
        return $this->body[$field] ?? $default;
    }

    // Вернуть все файлы
    public function files(): array
    {
        return $_FILES ?? [];
    }

    // Доступ к данным как к свойствам (без ошибок)
    public function __get($key)
    {
        return $this->body[$key] ?? null;
    }
}
