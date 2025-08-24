<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;

class PageController extends Controller
{
    public function main()
    {
        // dd(Currency::all()->toArray());
        return view('main', ['currencies' => Currency::all()]);
    }
}
