<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Currency;
use App\Models\CurrenciesHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

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
        $date = Carbon::now()->format("d/m/Y");
        $client = new Client([
            'base_uri' => "https://www.cbr.ru/scripts/XML_daily.asp?date_req=".$date
        ]);
        $response = $client->request("GET");
        $body = $response->getBody();
        $xmlData = simplexml_load_string((string) $body);
        $data = json_decode(json_encode($xmlData), true)["Valute"];

        $currencies = [];
        foreach ($data as $valute) {
            $currencies[] = [
                'name' => $valute['Name'],
                'nominal' => (int) $valute['Nominal'],
                'char_code' => $valute['CharCode'],
                'value' => (float) str_replace(',', '.', $valute['Value']),
                'date' => Carbon::createFromFormat('d/m/Y', $date)
            ];
        }
        $countryMap = config('currencies');

        DB::transaction(function () use ($currencies, $countryMap) {
            Currency::truncate();
            foreach ($currencies as $newCurrency) {
                Currency::create([...$newCurrency, 'country_code' => $countryMap[$newCurrency['char_code']]]);

                CurrenciesHistory::create($newCurrency);
            }

            Currency::create([
                'name' => 'Рубль',
                'char_code' => 'RUB',
                'country_code' => $countryMap['RUB'],
                'nominal' => 1,
                'value' => 1
            ]);
        });
    }
}
