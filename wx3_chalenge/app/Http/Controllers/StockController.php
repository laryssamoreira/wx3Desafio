<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        return Stock::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'size' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'product_id' => 'required|exists:products,id',
        ]);

        return Stock::create($request->all());
    }

    public function show(Stock $stock)
    {
        return $stock;
    }

    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'size' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'product_id' => 'required|exists:products,id',
        ]);

        $stock->update($request->all());

        return $stock;
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();

        return response()->json(null, 204);
    }
}
