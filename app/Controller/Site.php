<?php

namespace Controller;

use Model\Post;
use Src\View;
use Src\Request;
use Model\User;
use Src\Auth\Auth;
use Model\Book;
use Model\Reader;
class Site
{
    /**
     * @throws \Exception
     */
    public function index(Request $request): string
    {
        $posts = Post::where('id', $request->id)->get();
        return (new View())->render('site.post', ['posts' => $posts]);
    }

    public function hello(): string
    {
        return new View('site.hello', ['message' => 'hello working']);
    }

    public function signup(Request $request): string
    {
        if ($request->method === 'POST' && User::create($request->all())) {
            app()->route->redirect('/login');
        }
        return new View('site.signup');
    }
    public function login(Request $request): string
    {
        //Если просто обращение к странице, то отобразить форму
        if ($request->method === 'GET') {
            return new View('site.login');
        }
        //Если удалось аутентифицировать пользователя, то редирект
        if (Auth::attempt($request->all())) {
            app()->route->redirect('/hello');
        }
        //Если аутентификация не удалась, то сообщение об ошибке
        return new View('site.login', ['message' => 'Неправильные логин или пароль']);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/hello');
    }

    // Метод для отображения формы добавления книги
    // Метод для отображения формы добавления книги
    // Отображение формы
    public function createBook(): string
    {
        return new View('site/create-book'); // путь до views/site/add_book.php
    }

// Обработка формы
    public function storeBook(Request $request): string
    {
        $data = $request->all();

        if (
            empty($data['title']) ||
            empty($data['author']) ||
            empty($data['published_year']) ||
            empty($data['price'])
        ) {
            return new View('site/create-book', ['message' => 'Обязательные поля не заполнены!']);
        }

        \Model\Book::create([
            'title' => $data['title'],
            'author' => $data['author'],
            'published_year' => (int)$data['published_year'],
            'price' => (float)$data['price'],
            'is_new_edition' => isset($data['is_new_edition']) ? 1 : 0,
            'description' => $data['description'] ?? null,
        ]);

        return new View('site/create-book', ['message' => 'Книга успешно добавлена!']);
    }


    // Метод для отображения списка книг
    public function listBooks(Request $request)
    {
        // Получаем все книги
        $books = Book::all();

        // Рендерим представление с помощью существующего метода (например, view)
        return new View('list_books', ['books' => $books]);
    }
    public function createReader(): string
    {
        return new View('site/add_reader');
    }

// Обработка формы добавления читателя
    public function storeReader(Request $request): string
    {
        $data = $request->all();

        if (
            empty($data['card_number']) ||
            empty($data['full_name']) ||
            empty($data['address']) ||
            empty($data['phone_number'])
        ) {
            return new View('site/add_reader', ['message' => 'Все поля обязательны!']);
        }

        Reader::create([
            'card_number' => $data['card_number'],
            'full_name' => $data['full_name'],
            'address' => $data['address'],
            'phone_number' => $data['phone_number']
        ]);

        return new View('site/add_reader', ['message' => 'Читатель успешно добавлен!']);
    }




}