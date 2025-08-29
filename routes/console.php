<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Model\CurrenciesHistory;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:update-currency-rate')->daily();

Schedule::command('model:prune', [
    '--model' => [\App\Models\CurrenciesHistory::class]
])->daily();