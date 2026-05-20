<?php

namespace App\Http\Controllers;

use App\Models\MarketPrice;
use Illuminate\Http\Request;

class MarketTrendController extends Controller
{
    public function show(string $id)
    {
        $trend = MarketPrice::findOrFail($id);

        return view('market-trends.show', compact('trend'));
    }
}
