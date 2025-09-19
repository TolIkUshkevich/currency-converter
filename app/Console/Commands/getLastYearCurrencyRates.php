<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\CurrenciesHistory;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class getLastYearCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-last-year-currency-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    private function getRatesForDate($date)
    {
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
        return $currencies;
    }
 
    
    public function handle()
    {
        $startDate = Carbon::parse('-1 year');
        $endDate = Carbon::yesterday();
        $currentDate = $startDate->copy();
    
        while ($currentDate->lte($endDate)) {
            try {
                $rates = $this->getRatesForDate($currentDate->format('d/m/Y'));
    
                if (!empty($rates)) {
                    DB::transaction(function() use ($rates, $currentDate) {
                        foreach ($rates as $currency) {
                            CurrenciesHistory::create($currency);
                        }
    
                        CurrenciesHistory::create([
                            'name' => 'Рубль',
                            'char_code' => 'RUB',
                            'nominal' => 1,
                            'value' => 1,
                            'date' => $currentDate->format('Y-m-d')
                        ]);
                    });
                }
            } catch (\SoapFault $e) {
                dd($e);
            }
    
            $currentDate->addDay();
        }
    }

        
}
