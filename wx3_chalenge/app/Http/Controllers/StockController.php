<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StockController extends Controller
{
    public function index()
    {
        $objects = Stock::all();

        if ($objects->isEmpty()) {
            return response()->json(['message' => 'Não há objetos encontrados'], 200);
        }

        return response()->json($objects, 200);
    }

    public function store(Request $request)
    {
        try{
            $request->validate([
                'size' => 'required|string|max:255',
                'quantity' => 'required|integer|min:0',
                'product_id' => 'required|exists:products,id',
            ]);

            return Stock::create($request->all());
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Erro ao armazenar estoque: ' . $e->getMessage()], 500);
        }
    }

    public function show(Stock $stock)
    {
        try {
            $stock = Stock::findOrFail($id);
        
            return response()->json($stock, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Objeto não encontrado.'], 404);
        }
    }

    public function update(Request $request, Stock $stock)
    {
        try{
            $request->validate([
                'size' => 'required|string|max:255',
                'quantity' => 'required|integer|min:0',
                //'product_id' => 'required|exists:products,id',
            ]);

            $stock->update($request->all());
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Erro ao atualizar estoque: ' . $e->getMessage()], 500);
        }

        return $stock;
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();

        return response()->json(null, 204);
    }
}
