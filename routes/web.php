<?php

use Src\Route;

/* Авторизация */
Route::add(['GET', 'POST'], '/signup', [Controller\AuthController::class, 'signup']);
Route::add(['GET', 'POST'], '/login', [Controller\AuthController::class, 'login']);
Route::add('GET', '/logout', [Controller\AuthController::class, 'logout']);

/* Главная страница- */
Route::add('GET', '/hello', [Controller\SiteController::class, 'hello'])
    ->middleware('auth');

/* Книги */
Route::add('GET', '/books', [Controller\BookController::class, 'listBooks'])
    ->middleware('role:librarian,admin');

Route::add('GET', '/create-book', [Controller\BookController::class, 'createBook'])
    ->middleware('role:librarian,admin');

Route::add('POST', '/create-book', [Controller\BookController::class, 'storeBook'])
    ->middleware('role:librarian,admin');

Route::add('POST', '/delete-books', [Controller\BookController::class, 'deleteBooks'])
    ->middleware('role:librarian,admin');

Route::add('GET', '/most-popular-books', [Controller\BookController::class, 'mostPopularBooks'])
    ->middleware('role:librarian,admin');

/* Читатели */
Route::add('GET', '/readers', [Controller\ReaderController::class, 'listReaders'])
    ->middleware('role:librarian,admin');

Route::add('GET', '/create-reader', [Controller\ReaderController::class, 'createReader'])
    ->middleware('role:librarian,admin');

Route::add('POST', '/create-reader', [Controller\ReaderController::class, 'storeReader'])
    ->middleware('role:librarian,admin');

Route::add('POST', '/delete-readers', [Controller\ReaderController::class, 'deleteReader'])
    ->middleware('role:librarian,admin');

/* Выдача и возврат */
Route::add('GET', '/issue-book', [Controller\BorrowController::class, 'issueBookForm'])
    ->middleware('role:librarian,admin');

Route::add('POST', '/issue-book', [Controller\BorrowController::class, 'issueBook'])
    ->middleware('role:librarian,admin');

Route::add('GET', '/return-book', [Controller\BorrowController::class, 'returnBookList'])
    ->middleware('role:librarian,admin');

Route::add('POST', '/return-book', [Controller\BorrowController::class, 'returnBook'])
    ->middleware('role:librarian,admin');

/* Статистика книн */
Route::add(['GET', 'POST'], '/borrowed-books', [Controller\BorrowController::class, 'borrowedBooks'])
    ->middleware('role:librarian,admin');

Route::add(['GET', 'POST'], '/borrowers-by-book', [Controller\BorrowController::class, 'borrowersByBook'])
    ->middleware('role:librarian,admin');

/* Создание библиотекаря */
Route::add(['GET', 'POST'], '/create-librarian', [Controller\LibrarianController::class, 'createLibrarian'])
    ->middleware('role:admin');

Route::add('GET', '/librarians', [Controller\LibrarianController::class, 'listLibrarians'])
    ->middleware('role:admin');

Route::add('POST', '/delete-librarians', [Controller\LibrarianController::class, 'deleteLibrarians'])
    ->middleware('role:admin');

