<?php

namespace Controller;

use Src\View;
use Src\Request;
use Model\Reader;
use PhoneValidator\PhoneValidator;

class ReaderController
{
    public function createReader(): string
    {
        return new View('site/add_reader');
    }

    public function storeReader(Request $request): string
    {
        $data = $request->all();
        $errors = [];

        foreach (['card_number', 'full_name', 'address', 'phone_number'] as $field) {
            if (empty($data[$field])) {
                $errors[] = "Поле {$field} обязательно для заполнения.";
            }
        }

        $phoneValidator = new PhoneValidator($data['phone_number'] ?? '');
        if ($phoneValidator->fails()) {
            $errors = array_merge($errors, $phoneValidator->errors());
        }

        if (!empty($errors)) {
            return new View('site/add_reader', [
                'message' => implode($errors),
                'old' => $data
            ]);
        }

        Reader::create($data);
        return new View('site/add_reader', ['message' => 'Читатель успешно добавлен!']);
    }

    public function listReaders(): string
    {
        return new View('site.readers', ['readers' => Reader::all()]);
    }

    public function deleteReader(Request $request): string
    {
        $data = $request->all();
        $ids = $data['reader_ids'] ?? [];

        if (empty($ids)) {
            return new View('site/readers', [
                'readers' => Reader::all(),
                'message' => 'Вы не выбрали ни одного читателя для удаления.'
            ]);
        }

        Reader::destroy($ids);

        return new View('site/readers', [
            'readers' => Reader::all(),
            'message' => 'Выбранные читатели успешно удалены!'
        ]);
    }
}
