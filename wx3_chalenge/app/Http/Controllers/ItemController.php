<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ItemController extends Controller
{
    public function index()
    {
        $objects = Item::all();

        if ($objects->isEmpty()) {
            return response()->json(['message' => 'Não há objetos encontrados'], 200);
        }

        return response()->json($objects, 200);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer',
                'sale_price' => 'required|numeric',
                'size' => 'required|string|max:255',
                'product_id' => 'required|exists:products,id',
                'sale_id' => 'required|exists:sales,id',
            ]);

            return Item::create($request->all());
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Erro ao armazenar item: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $item = Item::findOrFail($id);
        
            return response()->json($item, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Objeto não encontrado.'], 404);
        }
    }

    // public function update(Request $request, Item $item)
    // {
    //     $request->validate([
    //         'quantity' => 'required|integer',
    //         'sale_price' => 'required|numeric',
    //         'size' => 'required|string|max:255',
    //         'product_id' => 'required|exists:products,id',
    //         'sale_id' => 'required|exists:sales,id',
    //     ]);

    //     $item->update($request->all());

    //     return $item;
    // }

    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json(null, 204);
    }
}
