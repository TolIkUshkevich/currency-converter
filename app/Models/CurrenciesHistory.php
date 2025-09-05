<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        return static::where('created_at', '<=', now()->subYear());
    }

    protected function createdAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at->format('d.m.Y')
        );
    }
}
