<?php

namespace Controller;

use Model\Post;
use Src\View;
use Src\Request;
use Model\User;
use Src\Auth\Auth;
use Model\Book;
use Model\Reader;
use Carbon\Carbon;
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

    public function listBooks(): string
    {
        // Получаем все книги из базы
        $books = Book::all();

        // Передаем книги в вид
        return new View('site.list_books', ['books' => $books]);
    }

    public function listReaders(): string
    {
        $readers = Reader::all(); // Получаем всех читателей из БД

        return new View('site.readers', ['readers' => $readers]);
    }

// Форма выдачи книги читателю
    public function issueBookForm(): string
    {
        $books = Book::all();
        $readers = Reader::all();
        return new View('site.issue_book', ['books' => $books, 'readers' => $readers]);
    }

// Обработка формы выдачи книги
    public function issueBook(Request $request): string
    {
        $data = $request->all();

        if (empty($data['book_id']) || empty($data['reader_id'])) {
            return new View('site.issue_book', [
                'message' => 'Выберите книгу и читателя.',
                'books' => Book::all(),
                'readers' => Reader::all()
            ]);
        }

        // Получаем читателя
        $reader = Reader::find($data['reader_id']);

        // Проверим, не выдана ли уже эта книга этому читателю без возврата
        $alreadyIssued = $reader->books()
            ->wherePivot('book_id', $data['book_id'])
            ->wherePivot('returned_at', null)
            ->exists();

        if ($alreadyIssued) {
            return new View('site.issue_book', [
                'message' => 'Эта книга уже выдана этому читателю и не возвращена.',
                'books' => Book::all(),
                'readers' => Reader::all()
            ]);
        }

        // Добавляем запись в book_reader
        $reader->books()->attach($data['book_id'], [
            'issued_at' => date('Y-m-d H:i:s'),
            'returned_at' => null,
        ]);

        return new View('site.issue_book', [
            'message' => 'Книга успешно выдана!',
            'books' => Book::all(),
            'readers' => Reader::all()
        ]);
    }

    // Отобразить все выданные книги, которые ещё не возвращены
    public function returnBookList(): string
    {
        // Получаем все записи из book_reader, где returned_at IS NULL
        $readers = Reader::with(['books' => function ($query) {
            $query->wherePivot('returned_at', null);
        }])->get();

        return new View('site.return_book', ['readers' => $readers]);
    }

// Обработка возврата книги
    public function returnBook(Request $request): string
    {
        $data = $request->all();
        $bookId = $data['book_id'] ?? null;
        $readerId = $data['reader_id'] ?? null;

        if (!$bookId || !$readerId) {
            return new View('site.return_book', [
                'message' => 'Не указаны книга или читатель.',
                'readers' => Reader::with(['books' => function ($query) {
                    $query->wherePivot('returned_at', null);
                }])->get()
            ]);
        }

        // Находим читателя и обновляем запись о возврате книги
        $reader = Reader::find($readerId);
        if ($reader) {
            $reader->books()->updateExistingPivot($bookId, [
                'returned_at' => date('Y-m-d H:i:s')
            ]);
        }

        return new View('site.return_book', [
            'message' => 'Книга успешно возвращена.',
            'readers' => Reader::with(['books' => function ($query) {
                $query->wherePivot('returned_at', null);
            }])->get()
        ]);
    }

    public function borrowedBooks(Request $request): string
    {
        $readerId = $request->all()['reader_id'] ?? null;

        if ($readerId) {
            // Получаем конкретного читателя с книгами, которые он ещё не вернул
            $reader = Reader::with(['books' => function ($query) {
                $query->wherePivot('returned_at', null);
            }])->find($readerId);

            return new View('site.borrowed_books', [
                'readers' => Reader::all(),
                'selectedReader' => $reader,
                'books' => $reader ? $reader->books : [],
            ]);
        } else {
            // Получаем все книги, которые сейчас не возвращены (у всех читателей)
            // Для этого получим всех читателей с книгами с условием returned_at = null
            $readers = Reader::with(['books' => function ($query) {
                $query->wherePivot('returned_at', null);
            }])->get();

            // Соберём все книги у всех читателей в один список
            $books = collect();
            foreach ($readers as $reader) {
                $books = $books->merge($reader->books->map(function($book) use ($reader) {
                    $book->reader_name = $reader->full_name;
                    return $book;
                }));
            }

            return new View('site.borrowed_books', [
                'readers' => $readers,
                'books' => $books,
                'selectedReader' => null,
            ]);
        }
    }

    public function borrowersByBook(Request $request): string
    {
        $bookId = $request->all()['book_id'] ?? null;

        $books = Book::all();

        if ($bookId) {
            // Загружаем книгу с читателями, которые брали ее (включая даты)
            $book = Book::with(['readers' => function($query) {
                $query->orderBy('book_reader.issued_at', 'desc');
            }])->find($bookId);

            $borrowers = $book ? $book->readers : collect();

            return new View('site.borrowers_by_book', [
                'books' => $books,
                'selectedBook' => $book,
                'borrowers' => $borrowers,
            ]);
        }

        return new View('site.borrowers_by_book', [
            'books' => $books,
            'selectedBook' => null,
            'borrowers' => collect(),
        ]);
    }

    public function mostPopularBooks(): string
    {
        // Получаем книги с подсчётом количества выдач (borrowings)
        $books = Book::withCount(['readers as borrowings_count' => function($query) {
            // Если нужно, можно добавить дополнительные условия (например, только активные выдачи)
        }])
            ->orderBy('borrowings_count', 'desc')
            ->get();

        return new View('site.most_popular_books', [
            'books' => $books,
        ]);
    }

    // Показ формы добавления библиотекаря
    public function createLibrarian(Request $request): string
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            return new View('site.403', ['message' => 'Доступ запрещен']);
        }

        if ($request->method === 'POST') {
            $data = $request->all();

            // Валидация
            if (empty($data['name']) || empty($data['login']) || empty($data['password'])) {
                return new View('site.create_librarian', ['message' => 'Все поля обязательны']);
            }

            // Создание пользователя с ролью "librarian"
            \Model\User::create([
                'name' => $data['name'],
                'login' => $data['login'],
                'password' => $data['password'], // захешируется в booted()
                'role' => 'librarian' // или \Model\User::ROLE_LIBRARIAN, если есть константа
            ]);

            return new View('site.create_librarian', ['message' => 'Библиотекарь успешно добавлен!']);
        }

        // Если GET-запрос — просто показываем форму
        return new View('site.create_librarian');
    }



}