<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    public function index()
    {
        $objects = Product::all();

        if ($objects->isEmpty()) {
            return response()->json(['message' => 'Não há objetos encontrados'], 200);
        }

        return response()->json($objects, 200);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'identification_code' => 'required|unique:products|string|max:255',
                'name' => 'required|string|max:255',
                'color' => 'required|string|max:255',
                'image' => 'required|string|max:255',
                'price' => 'required|numeric',
                'description' => 'required|string',
                'register_date' => 'required|date',
                'weight' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
            ]);

            return Product::create($request->all());
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Erro ao armazenar produto: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
        
            return response()->json($product, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Objeto não encontrado.'], 404);
        }
    }

    public function update(Request $request, Product $product)
    {
        try{
            $request->validate([
                'identification_code' => 'required|string|max:255|unique:products,identification_code,' . $product->id,
                'name' => 'required|string|max:255',
                'color' => 'required|string|max:255',
                'image' => 'required|string|max:255',
                'price' => 'required|numeric',
                'description' => 'required|string',
                'register_date' => 'required|date',
                'weight' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
            ]);

            $product->update($request->all());
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Erro ao atualizar produto: ' . $e->getMessage()], 500);
        }

        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}
