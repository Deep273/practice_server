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
}
