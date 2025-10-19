<?php

namespace Src;

class Response
{
    private array $data;
    private int $status;

    public function __construct(array $data = [], int $status = 200)
    {
        $this->data = $data;
        $this->status = $status;
    }

    public function json(int $status = null): string
    {
        header('Content-Type: application/json');
        http_response_code($status ?? $this->status);
        return json_encode($this->data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
