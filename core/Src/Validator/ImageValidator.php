<?php

namespace Src\Validator;

class ImageValidator
{
    protected array $file;
    protected array $errors = [];

    protected array $allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    protected int $maxSize = 2 * 1024 * 1024; // 2 MB

    public function __construct(?array $file)
    {
        $this->file = $file ?? [];
    }

    public function fails(): bool
    {
        // Если файл не загружен — не считаем это ошибкой, т.к. обложка может быть необязательной
        if (empty($this->file['name'])) {
            return false;
        }

        // Проверка ошибок загрузки
        if ($this->file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = 'Ошибка загрузки файла.';
        }

        // Проверка типа файла
        if (!in_array($this->file['type'], $this->allowedTypes)) {
            $this->errors[] = 'Разрешены только изображения форматов JPEG, PNG или WEBP.';
        }

        // Проверка размера
        if ($this->file['size'] > $this->maxSize) {
            $this->errors[] = 'Размер файла не должен превышать 2 МБ.';
        }

        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
