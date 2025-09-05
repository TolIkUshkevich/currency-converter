<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\CurrenciesHistory;
use Illuminate\Http\RedirectResponse;

class GraphicsController extends Controller
{
    public function select(Request $request)
    {
        return view('currency-select-page', ['currencies' => Currency::all()]);
    }


    public function show(Request $request)
    {
        $numinator = session('numinator') ?? CurrenciesHistory::where('char_code', 'USD')->get();;
        $denuminator = session('denuminator') ?? CurrenciesHistory::where('char_code', 'EUR')->get();;
        $minHistoryLen = count($numinator);
        $currencyPairValues = [];
        for ($i = 0; $i < $minHistoryLen; $i++) {
            $currencyPairValues[$numinator[$i]->created_at_formatted] = round($numinator[$i]->value/$denuminator[$i]->value, 5);
        }
        return view('graphics-page', ['currencyPairValues' => $currencyPairValues]);
    }

    public function makeGraphic(Request $request): RedirectResponse
    {
        $numirator = CurrenciesHistory::where('char_code', $request->numirator)->get();
        $denumirator = CurrenciesHistory::where('char_code', $request->denumirator)->get();
        return redirect()->route('show.graphic')
            ->with('numinator', $numirator)
            ->with('denuminator', $denumirator);
    }
}
