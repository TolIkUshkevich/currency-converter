<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Builder;

class CurrenciesHistory extends Model
{
    use MassPrunable;

    public $table = 'currencies_history';

    public $fillable = [
        'name',
        'char_code',
        'nominal',
        'value'
    ];

    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subMonth());
    }
}
