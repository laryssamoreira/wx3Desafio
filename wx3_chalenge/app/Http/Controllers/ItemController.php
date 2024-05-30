<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return Item::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer',
            'sale_price' => 'required|numeric',
            'size' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'sale_id' => 'required|exists:sales,id',
        ]);

        return Item::create($request->all());
    }

    public function show(Item $item)
    {
        return $item;
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'quantity' => 'required|integer',
            'sale_price' => 'required|numeric',
            'size' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'sale_id' => 'required|exists:sales,id',
        ]);

        $item->update($request->all());

        return $item;
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json(null, 204);
    }
}
