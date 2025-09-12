<?php

use Src\Route;

Route::add('GET', '/hello', [Controller\Site::class, 'hello'])
    ->middleware('auth');
Route::add(['GET', 'POST'], '/signup', [Controller\Site::class, 'signup']);
Route::add(['GET', 'POST'], '/login', [Controller\Site::class, 'login']);
Route::add('GET', '/logout', [Controller\Site::class, 'logout']);
// Маршруты для книги
Route::add('GET', '/create-book', [Controller\Site::class, 'createBook']);    // Форма добавления книги
Route::add('POST', '/create-book', [Controller\Site::class, 'storeBook']);     // Обработка добавления книги
Route::add('GET', '/create-reader', [Controller\Site::class, 'createReader']);  // Форма
Route::add('POST', '/create-reader', [Controller\Site::class, 'storeReader']);  // Обработка

Route::add('GET', '/books', [Controller\Site::class, 'listBooks']);
Route::add('GET', '/readers', [Controller\Site::class, 'listReaders']);

Route::add('GET', '/issue-book', [Controller\Site::class, 'issueBookForm']);
Route::add('POST', '/issue-book', [Controller\Site::class, 'issueBook']);

// Просмотр выданных книг
Route::add('GET', '/return-book', [Controller\Site::class, 'returnBookList']);

// Обработка возврата
Route::add('POST', '/return-book', [Controller\Site::class, 'returnBook']);

Route::add(['GET', 'POST'], '/borrowed-books', [Controller\Site::class, 'borrowedBooks']);

Route::add(['GET', 'POST'], '/borrowers-by-book', [Controller\Site::class, 'borrowersByBook']);

Route::add('GET', '/most-popular-books', [Controller\Site::class, 'mostPopularBooks']);

