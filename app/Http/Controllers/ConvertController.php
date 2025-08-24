<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;

class ConvertController extends Controller
{
    private function getExchangeRate(string $fromCharCode, string $toCharCode)
    {
        $fromCurr = Currency::where('char_code', $fromCharCode)->first();
        $toCurr = Currency::where('char_code', $toCharCode)->first();
        // dd($fromCurr);
        
        return $fromCurr['value'] / $fromCurr['nominal'] / $toCurr['value'] / $toCurr['nominal'];
    }

    public function convert(Request $request)
    {
        $request->validate([
            'fromCharCode' => 'required|string|size:3',
            'toCharCode' => 'required|string|size:3',
            'amount' => 'required|numeric|min:0'
        ]);
        
        // Обновляем статистику использования
        // $this->updateCurrencyUsage($request->from, $request->to);
        
        // Получаем реальный курс из вашего сервиса
        $rate = $this->getExchangeRate($request->fromCharCode, $request->toCharCode);
        $result = $request->amount * $rate;
        
        return response()->json([
            'success' => true,
            'result' => number_format($result, 4),
            'rate' => number_format($rate, 6)
        ]);
    }
}
