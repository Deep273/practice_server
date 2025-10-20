<?php

use Src\Route;

Route::add('GET', '/', [Controller\Api::class, 'index']);
Route::add('POST', '/login', [Controller\Api::class, 'login']);
Route::add('POST', '/readers/create', [Controller\AddReaderApi::class, 'addReader'])
    ->middleware([Middlewares\AuthApiMiddleware::class, Middlewares\AdminMiddleware::class]);
