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

    public $timestamps = false;

    public $fillable = [
        'name',
        'char_code',
        'nominal',
        'value',
        'date'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function prunable(): Builder
    {
        return static::where('date', '<=', now()->subYear());
    }

    protected function createdAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->date->format('d/m/Y')
        );
    }
}
