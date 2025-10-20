<?php

namespace Controller;

use Src\View;
use Src\Request;
use Model\Book;

class BookController
{
    public function createBook(): string
    {
        return new View('site/create-book');
    }

    public function storeBook(Request $request): string
    {
        $data = $request->all();
        $errors = [];

        foreach (['title', 'author', 'published_year', 'price'] as $field) {
            if (empty($data[$field])) {
                $errors[] = "Поле {$field} обязательно для заполнения.";
            }
        }

        if (isset($data['price']) && (float)$data['price'] < 0) {
            $errors[] = "Цена не может быть отрицательной.";
        }

        if (!empty($data['published_year'])) {
            $inputDate = strtotime($data['published_year']);
            if ($inputDate > time()) {
                $errors[] = "Год издания не может быть в будущем.";
            }
        }

        $imageValidator = new \Src\Validator\ImageValidator($_FILES['cover'] ?? null);
        if ($imageValidator->fails()) {
            $errors = array_merge($errors, $imageValidator->errors());
        }

        if (!empty($errors)) {
            return new View('site/create-book', [
                'errors' => $errors,
                'old' => $data
            ]);
        }

        $coverPath = null;
        if (!empty($_FILES['cover']['name'])) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/books/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $fileName = time() . '_' . basename($_FILES['cover']['name']);
            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['cover']['tmp_name'], $targetFile)) {
                $coverPath = '/uploads/books/' . $fileName;
            }
        }

        Book::create([
            'title' => $data['title'],
            'author' => $data['author'],
            'published_year' => (int)$data['published_year'],
            'price' => (float)$data['price'],
            'is_new_edition' => isset($data['is_new_edition']) ? 1 : 0,
            'description' => $data['description'] ?? null,
            'cover_url' => $coverPath,
        ]);

        return new View('site/create-book', ['message' => 'Книга успешно добавлена!']);
    }

    public function listBooks(Request $request): string
    {
        $query = Book::query();

        if (!empty($request->get('q'))) {
            $search = trim($request->get('q'));
            $query->where('title', 'like', "%$search%")
                ->orWhere('author', 'like', "%$search%");
        }

        return new View('site.list_books', [
            'books' => $query->get(),
            'search' => $request->get('q') ?? ''
        ]);
    }

    public function deleteBooks(Request $request): string
    {
        $data = $request->all();
        $ids = $data['book_ids'] ?? [];

        if (empty($ids)) {
            return new View('site/list_books', [
                'books' => Book::all(),
                'message' => 'Вы не выбрали ни одной книги для удаления.'
            ]);
        }

        Book::destroy($ids);

        return new View('site/list_books', [
            'books' => Book::all(),
            'message' => 'Выбранные книги успешно удалены!'
        ]);
    }
}
