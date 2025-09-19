<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;
use App\Http\Requests\ConvertRequest;

class ConvertController extends Controller
{
    private function getExchangeRate(string $fromCharCode, string $toCharCode)
    {
        $fromCurr = Currency::where('char_code', $fromCharCode)->first();
        $toCurr = Currency::where('char_code', $toCharCode)->first();
        
        return $fromCurr['value'] / $fromCurr['nominal'] / $toCurr['value'] / $toCurr['nominal'];
    }

    public function show(Request $request)
    {
        return view('main', ['currencies' => Currency::all()]);
    }

    public function convert(ConvertRequest $request)
    {   
        $validated = $request->validated();
        
        try {
            $rate = $this->getExchangeRate($validated['fromCharCode'], $validated['toCharCode']);
            $result = $validated['amount'] * $rate;
            \Log::info([$rate, $result]);
            
            return response()->json([
                'success' => true,
                'result' => number_format($result, 4),
                'rate' => number_format($rate, 6)
            ]);
        } catch (\Exception $e) {
            
            return response()->json([
                'success' => false,
                'message' => 'Conversion failed'
            ], 500);
        }
    }
}
