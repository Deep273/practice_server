<?php

namespace Controller;

use Model\Post;
use Src\View;
use Src\Request;
use Model\User;
use Src\Auth\Auth;
use Model\Book;
use Model\Reader;
use PhoneValidator\PhoneValidator;



class Site
{
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
        if ($request->method === 'POST') {

            $data = $request->all();

            // Валидация полей и уникальности логина
            $validator = new \Src\Validator\Validator(
                $data,
                [
                    'name'     => ['required'],
                    'login'    => ['required', 'unique:users,login'],
                    'password' => ['required'],
                ],
                [
                    'required' => 'Поле :field пусто',
                    'unique'   => 'Поле :field должно быть уникальным',
                ]
            );

            // Проверка сложности пароля
            $passwordValidator = new \Src\Validator\PasswordValidator($data['password'] ?? '');

            // Если есть ошибки — объединяем обе группы ошибок
            if ($validator->fails() || $passwordValidator->fails()) {
                $errors = $validator->errors();

                if ($passwordValidator->fails()) {
                    $errors['password'] = array_merge(
                        $errors['password'] ?? [],
                        $passwordValidator->errors()
                    );
                }

                return new \Src\View('site.signup', [
                    'errors' => $errors,
                    'old'    => $data,
                    'message' => 'Проверьте правильность заполнения полей.'
                ]);
            }

            // Хэшируем пароль перед сохранением
            $data['password'] = md5($data['password']);

            // Создание пользователя
            if (\Model\User::create($data)) {
                app()->route->redirect('/login');
            }

            // Если создание не удалось
            return new \Src\View('site.signup', [
                'message' => 'Ошибка при создании пользователя. Попробуйте позже.',
                'old'     => $data
            ]);
        }

        // Если это GET — просто отображаем форму
        return new \Src\View('site.signup');
    }


    public function login(Request $request): string
    {
        // Если просто обращение к странице, то отобразить форму
        if ($request->method === 'GET') {
            return new View('site.login');
        }

        // валидация
        $validator = new \Src\Validator\Validator(
            $request->all(),
            [
                'login' => ['required'],
                'password' => ['required'],
            ],
            [
                'required' => 'Поле :field пусто',
            ]
        );

        if ($validator->fails()) {
            return new View('site.login', [
                'errors' => $validator->errors()
            ]);
        }

        // аутентификация
        if (\Src\Auth\Auth::attempt($request->all())) {
            app()->route->redirect('/hello');
        }

        // Если аутентификация не удалась
        return new View('site.login', ['message' => 'Неправильные логин или пароль']);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/hello');
    }

    // Метод для отображения формы добавления книги
    public function createBook(): string
    {
        return new View('site/create-book');
    }

    // Обработка формы
    public function storeBook(Request $request): string
    {
        $data = $request->all();
        $errors = [];

        // Проверка обязательных полей
        foreach (['title', 'author', 'published_year', 'price'] as $field) {
            if (empty($data[$field])) {
                $errors[] = "Поле {$field} обязательно для заполнения.";
            }
        }

        // Проверка на отрицательную цену
        if (isset($data['price']) && (float)$data['price'] < 0) {
            $errors[] = "Цена не может быть отрицательной.";
        }

        // Проверка, чтобы дата не была из будущего
        if (!empty($data['published_year'])) {
            $inputDate = strtotime($data['published_year']);
            $currentDate = strtotime(date('Y-m-d'));
            if ($inputDate > $currentDate) {
                $errors[] = "Год издания не может быть в будущем.";
            }
        }

        // Валидация обложки
        $imageValidator = new \Src\Validator\ImageValidator($_FILES['cover'] ?? null);
        if ($imageValidator->fails()) {
            $errors = array_merge($errors, $imageValidator->errors());
        }

        // Если есть ошибки — вернуть форму с ними
        if (!empty($errors)) {
            return new View('site/create-book', [
                'errors' => $errors,
                'old'    => $data
            ]);
        }

        // Загрузка обложки
        $coverPath = null;
        if (!empty($_FILES['cover']['name'])) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/books/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . '_' . basename($_FILES['cover']['name']);
            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['cover']['tmp_name'], $targetFile)) {
                $coverPath = '/uploads/books/' . $fileName;
            }
        }

        // Сохранение
        \Model\Book::create([
            'title'          => $data['title'],
            'author'         => $data['author'],
            'published_year' => (int)$data['published_year'],
            'price'          => (float)$data['price'],
            'is_new_edition' => isset($data['is_new_edition']) ? 1 : 0,
            'description'    => $data['description'] ?? null,
            'cover_url'      => $coverPath,
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
        $errors = [];

        // Проверяем обязательные поля
        foreach (['card_number', 'full_name', 'address', 'phone_number'] as $field) {
            if (empty($data[$field])) {
                $errors[] = "Поле {$field} обязательно для заполнения.";
            }
        }

        // Проверка телефона через PhoneValidator
        $phoneValidator = new PhoneValidator($data['phone_number'] ?? '');
        if ($phoneValidator->fails()) {
            $errors = array_merge($errors, $phoneValidator->errors());
        }
        // Если есть ошибки — возвращаем форму с сообщением
        if (!empty($errors)) {
            return new View('site/add_reader', [
                'message' => implode ($errors),
                'old' => $data
            ]);
        }

        // Сохраняем нового читателя
        \Model\Reader::create([
            'card_number' => $data['card_number'],
            'full_name' => $data['full_name'],
            'address' => $data['address'],
            'phone_number' => $data['phone_number']
        ]);

        // Успешное добавление
        return new View('site/add_reader', [
            'message' => 'Читатель успешно добавлен!'
        ]);
    }


    public function deleteReader(Request $request): string
    {
        // Получаем все данные из POST
        $data = $request->all();
        $ids = $data['reader_ids'] ?? [];

        // Если ничего не выбрано
        if (empty($ids)) {
            return new View('site/readers', [
                'readers' => \Model\Reader::all(),
                'message' => 'Вы не выбрали ни одного читателя для удаления.'
            ]);
        }

        // Удаляем выбранных читателей
        \Model\Reader::destroy($ids);

        // После удаления показываем обновлённый список
        return new View('site/readers', [
            'readers' => \Model\Reader::all(),
            'message' => 'Выбранные читатели успешно удалены!'
        ]);
    }


    public function listBooks(Request $request): string
    {
        $query = Book::query();

        // Если в запросе есть поисковая строка
        if (!empty($request->get('q'))) {
            $search = trim($request->get('q'));
            $query->where('title', 'like', "%$search%")
                ->orWhere('author', 'like', "%$search%");
        }

        $books = $query->get();

        return new View('site.list_books', [
            'books' => $books,
            'search' => $request->get('q') ?? ''
        ]);
    }

    public function listReaders(): string
    {
        $readers = Reader::all(); // Получаем всех читателей из бд

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

        // Проверка, не выдана ли уже эта книга этому читателю без возврата
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
            $readers = Reader::with(['books' => function ($query) {
                $query->wherePivot('returned_at', null);
            }])->get();

            // Соберём все книги у всех читателей в один список
            $books = collect();
            foreach ($readers as $reader) {
                $books = $books->merge($reader->books->map(function ($book) use ($reader) {
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
            // Загружаем книгу с читателями, которые брали ее
            $book = Book::with(['readers' => function ($query) {
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
        // Получаем книги с подсчётом количества выдач
        $books = Book::withCount(['readers as borrowings_count' => function ($query) {

        }])
            ->orderBy('borrowings_count', 'desc')
            ->get();

        return new View('site.most_popular_books', [
            'books' => $books,
        ]);
    }
    public function createLibrarian(Request $request): string
    {
        // Проверка прав — только админ
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            app()->route->redirect('/login');
        }

        // Если это POST-запрос - обрабатываем отправку формы
        if ($request->method === 'POST') {
            $data = $request->all();

            // Проверка обязательных полей
            if (empty($data['name']) || empty($data['login']) || empty($data['password'])) {
                return new View('site/create_librarian', [
                    'message' => 'Все поля обязательны!'
                ]);
            }

            // Проверка уникальности логина
            if (User::where('login', $data['login'])->exists()) {
                return new View('site/create_librarian', [
                    'message' => 'Такой логин уже существует!'
                ]);
            }

            // Хешируем пароль перед сохранением
            $data['password'] = md5($data['password']);

            // Создание библиотекаря
            User::create([
                'name'     => $data['name'],
                'login'    => $data['login'],
                'password' => $data['password'],
                'role'     => 'librarian',
            ]);

            return new View('site/create_librarian', [
                'message' => 'Библиотекарь успешно добавлен!'
            ]);
        }

        // Если GET-запрос - просто отображаем форму
        return new View('site/create_librarian');
    }


    public function listLibrarians(): string
    {
        // Только админ может просматривать список
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            app()->route->redirect('/login');
        }

        // Получаем всех пользователей с ролью librarian
        $librarians = User::where('role', 'librarian')->get();

        return new View('site/list_librarian', [
            'librarians' => $librarians
        ]);
    }

    public function deleteBooks(Request $request): string
    {
        $data = $request->all();
        $ids = $data['book_ids'] ?? [];

        if (empty($ids)) {
            return new View('site/list_books', [
                'books' => \Model\Book::all(),
                'message' => 'Вы не выбрали ни одной книги для удаления.'
            ]);
        }

        // Удаляем выбранные книги
        \Model\Book::destroy($ids);

        return new View('site/list_books', [
            'books' => \Model\Book::all(),
            'message' => 'Выбранные книги успешно удалены!'
        ]);
    }

}

