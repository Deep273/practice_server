<?php

namespace Controller;

use Model\Reader;
use Src\Request;
use Src\Response;
use PhoneValidator\PhoneValidator;
use Src\View;

class AddReaderApi
{
    public function addReader(Request $request): void
    {
    $data = $request->all();

    // Проверка обязательных полей
    foreach (['card_number', 'full_name', 'address', 'phone_number'] as $field) {
        if (empty($data[$field])) {
            (new View())->toJSON([
                'status' => 'error',
                'message' => "Поле '{$field}' обязательно для заполнения."
            ], 400);
            return;
        }
    }

    // Проверка телефона через твой PhoneValidator
    $phoneValidator = new PhoneValidator($data['phone_number'] ?? '');
    if ($phoneValidator->fails()) {
        (new View())->toJSON([
            'status' => 'error',
            'message' => 'Ошибка валидации номера телефона',
            'errors' => $phoneValidator->errors()
        ], 400);
        return;
    }

    // Проверка на уникальный номер карты
    if (Reader::where('card_number', $data['card_number'])->exists()) {
        (new View())->toJSON([
            'status' => 'error',
            'message' => 'Читатель с таким номером карты уже существует.'
        ], 400);
        return;
    }

    // Создание нового читателя
    $reader = Reader::create([
        'card_number'  => $data['card_number'],
        'full_name'    => $data['full_name'],
        'address'      => $data['address'],
        'phone_number' => $data['phone_number'],
    ]);

    // Успешный ответ
    (new View())->toJSON([
        'status' => 'success',
        'message' => 'Читатель успешно добавлен.',
        'reader' => [
            'id' => $reader->id,
            'card_number' => $reader->card_number,
            'full_name' => $reader->full_name,
            'address' => $reader->address,
            'phone_number' => $reader->phone_number,
        ]
    ], 200);
    }
}
