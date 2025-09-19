<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\CurrenciesHistory;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\SelectCurrencyRequest;

class GraphicsController extends Controller
{
    public function select(Request $request)
    {
        return view('currency-select-page', ['currencies' => Currency::all()]);
    }


    public function show(Request $request)
    {
        $numeratorCharCode = session('numerator') ?? 'USD';
        $denumeratorCharCode = session('denumerator') ?? 'EUR';
        $numerator = CurrenciesHistory::where('char_code', $numeratorCharCode)->orderBy('date', 'ASC')->get();
        $denumerator = CurrenciesHistory::where('char_code', $denumeratorCharCode)->orderBy('date', 'ASC')->get();
        $minVal = count($numerator) < count($denumerator) ? count($numerator) : count($denumerator);
        $currencyPairValues = [];
        for ($i = 0; $i < $minVal; $i++) {
            $currencyPairValues[] = [
                $numerator[$i]->created_at_formatted,
                round($numerator[$i]->value/$denumerator[$i]->value, 5)
            ];
        }

        return view('graphics-page', [
            'currencyPairValues' => $currencyPairValues,
            'numerator' => $numeratorCharCode,
            'denumerator' => $denumeratorCharCode
        ]);
    }

    public function makeGraphic(SelectCurrencyRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $numerator = $validated['numerator'];
        $denumerator = $validated['denumerator'];
        return redirect(route('show.graphic'))
            ->with(['numerator' => $validated['numerator'], 'denumerator' => $validated['denumerator']]);
    }
}
