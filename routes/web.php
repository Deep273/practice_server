<?php

use Src\Route;

Route::add('GET', '/hello', [Controller\Site::class, 'hello'])
    ->middleware('auth');
Route::add(['GET', 'POST'], '/signup', [Controller\Site::class, 'signup']);
Route::add(['GET', 'POST'], '/login', [Controller\Site::class, 'login']);
Route::add('GET', '/logout', [Controller\Site::class, 'logout']);

// Создание книги
Route::add('GET', '/create-book', [Controller\Site::class, 'createBook'])
    ->middleware('role:librarian,admin');

Route::add('POST', '/create-book', [Controller\Site::class, 'storeBook'])
    ->middleware('role:librarian,admin');

// Создание читателя
Route::add('GET', '/create-reader', [Controller\Site::class, 'createReader'])
    ->middleware('role:librarian,admin');

Route::add('POST', '/create-reader', [Controller\Site::class, 'storeReader'])
    ->middleware('role:librarian,admin');

// Выдача книги
Route::add('GET', '/issue-book', [Controller\Site::class, 'issueBookForm'])
    ->middleware('role:librarian,admin');

Route::add('POST', '/issue-book', [Controller\Site::class, 'issueBook'])
    ->middleware('role:librarian,admin');

// Просмотр выданных книг и возврат
Route::add('GET', '/return-book', [Controller\Site::class, 'returnBookList'])
    ->middleware('role:librarian,admin');

Route::add('POST', '/return-book', [Controller\Site::class, 'returnBook'])
    ->middleware('role:librarian,admin');

// Список книг и читателей — тоже только для библиотекаря и админа
Route::add('GET', '/books', [Controller\Site::class, 'listBooks'])
    ->middleware('role:librarian,admin');

Route::add('GET', '/readers', [Controller\Site::class, 'listReaders'])
    ->middleware('role:librarian,admin');

// Просмотр кто взял какую книгу и книги у читателей
Route::add(['GET', 'POST'], '/borrowed-books', [Controller\Site::class, 'borrowedBooks'])
    ->middleware('role:librarian,admin');

Route::add(['GET', 'POST'], '/borrowers-by-book', [Controller\Site::class, 'borrowersByBook'])
    ->middleware('role:librarian,admin');

// Самые популярные книги
Route::add('GET', '/most-popular-books', [Controller\Site::class, 'mostPopularBooks'])
    ->middleware('role:librarian,admin');


// Только для администратора
Route::add(['GET', 'POST'], '/create-librarian', [Controller\Site::class, 'createLibrarian'])
    ->middleware('role:admin');
// Список библиотекарей (только админ)
Route::add('GET', '/librarians', [Controller\Site::class, 'listLibrarians'])
    ->middleware('role:admin');
