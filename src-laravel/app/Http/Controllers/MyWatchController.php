<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MyWatch;
use App\Models\Brand;
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
            $avaragePrice = MarketPrice::where('ref_number', $watch->reference_number)->avg('price');

            $watch->market_average = (int)$avaragePrice;

            if ($watch->market_average > 0) {
                $watch->profit_loss = $watch->market_average - $watch->purchase_price;
            } else {
                $watch->profit_loss = null;
            }
        }

        $marketTrends = MarketPrice::inRandomOrder()->take(16)->get();

        return view('my-watches.index', compact('myWatches','marketTrends'));
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
        $validated = $request->validate([
            'brand_id'         => 'required',
            'model_name'       => 'required|string',
            'reference_number' => 'required|string',
            'serial_number'    => 'nullable|string',
            'purchase_price'   => 'required|numeric',
            'purchase_date'    => 'required|date',
            'note'             => 'nullable|string',
            'image'            => 'nullable|image|max:5120'
        ]);

        $validated['id'] = (string) \Illuminate\Support\Str::ulid();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('watches', 'public');
            $validated['image_path'] = $path;
        }

        MyWatch::create($validated);

        return redirect()->back()->with('success', '登録完了！');
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
        $watch = MyWatch::findOrFail($id);

        $brands = Brand::all();

        return view('my-watches.edit', compact('watch', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $watch = MyWatch::findOrFail($id);

        $validated = $request->validate([
            'brand_id'         => 'required',
            'model_name'       => 'required|string',
            'reference_number' => 'required|string',
            'serial_number'    => 'nullable|string',
            'purchase_price'   => 'required|numeric',
            'purchase_date'    => 'required|date',
            'note'             => 'nullable|string',
            'image'            => 'nullable|image|max:5120'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('watches', 'public');

            $validated['image_path'] = $path;
        }

        $watch->update($validated);

        return redirect()->route('my-watches.index')->with('success', '更新が完了しました！');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $watch = MyWatch::findOrFail($id);

        if ($watch->image_path) {
            Storage::disk('public')->delete($watch->image_path);
        }

        $watch->delete();

        return redirect()->route('my-watches.index')->with('success', 'コレクションから削除しました');
    }
}
