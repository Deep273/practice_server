<?php

namespace Controller;

use Model\Book;
use Src\Request;
use Src\View;

class Api
{
    public function index(): void
    {
        $books = Book::all()->toArray();
        (new View())->toJSON($books);
    }

    public function echo(Request $request): void
    {
        (new View())->toJSON($request->all());
    }
}