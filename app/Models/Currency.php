<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    public $fillable = [
        'name',
        'char_code',
        'country_code',
        'nominal',
        'value'
    ];
}
