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
Route::add('GET', '/books', [Controller\Site::class, 'listBooks']);            // Список всех книг
Route::add('GET', '/create-reader', [Controller\Site::class, 'createReader']);  // Форма
Route::add('POST', '/create-reader', [Controller\Site::class, 'storeReader']);  // Обработка