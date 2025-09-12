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
}
