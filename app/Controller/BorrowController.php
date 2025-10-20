<?php

namespace Controller;

use Src\View;
use Src\Request;
use Model\Book;
use Model\Reader;

class BorrowController
{
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
}
