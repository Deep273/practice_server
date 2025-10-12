<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Reader extends Model
{
    protected $table = 'readers';
    public $timestamps = false;

    protected $fillable = [
        'card_number',
        'full_name',
        'address',
        'phone_number'
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_reader', 'reader_id', 'book_id')
            ->withPivot('issued_at', 'returned_at');
    }

    /**
     * Удаление читателя и очистка связей с книгами
     */
    public function remove()
    {
        // Удаляем связи в pivot-таблице
        $this->books()->detach();

        // Удаляем самого читателя
        return $this->delete();
    }
}
