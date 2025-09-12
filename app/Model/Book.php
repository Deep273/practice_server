<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'author',
        'published_year',
        'price',
        'is_new_edition',
        'description',
    ];
    public function readers()
    {
        return $this->belongsToMany(Reader::class, 'book_reader', 'book_id', 'reader_id')
            ->withPivot('issued_at', 'returned_at');
    }

}
