<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MyWatch;
use App\Models\MarketPrice;

class MyWatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $myWatches = MyWatch::with('brand')->get();

        foreach ($myWatches as $watch) {
            $avaragePrice = MarketPrice::where('ref_number',$watch->reference_number)->avg('price');

            $watch->market_average = (int)$avaragePrice;

            if ($watch->market_average > 0) {
                $watch->profit_loss = $watch->market_average - $watch->purchase_price;
            } else {
                $watch->profit_loss = null;
            }
        }

        return view('my-watches.index', compact('myWatches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
