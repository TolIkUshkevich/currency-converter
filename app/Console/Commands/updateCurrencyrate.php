<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use App\Models\Currency;
use App\Models\CurrenciesHistory;

class updateCurrencyRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-currency-rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'parses curent curency rates, update it and save previous';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new Client();
        $response = $client->get('https://www.cbr-xml-daily.ru/daily_json.js');
        $body = json_decode($response->getBody()->getContents());
        $currencies = $body->Valute;

        DB::transaction(function () use ($currencies) {
            foreach (Currency::all() as $oldCurrency) {
                CurrenciesHistory::create([
                    'name' => $oldCurrency->name,
                    'char_code' => $oldCurrency->char_code,
                    'nominal' => $oldCurrency->nominal,
                    'value' => $oldCurrency->value
                ]);
            }
            Currency::truncate();
            foreach ($currencies as $newCurrency) {
                Currency::create([
                    'name' => $newCurrency->Name,
                    'char_code' => $newCurrency->CharCode,
                    'nominal' => $newCurrency->Nominal,
                    'value' => $newCurrency->Value
                ]);
            }

            
        });
    }
}
