<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    public function index()
    {
        $objects = Category::all();

        if ($objects->isEmpty()) {
            return response()->json(['message' => 'Não há objetos encontrados'], 200);
        }

        return response()->json($objects, 200);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'identification_code' => 'required|string|max:255|unique:categories',
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'required|string',
            ]);
            
            return Category::create($request->all());
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Erro ao armazenar categoria: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
        
            return response()->json($category, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Objeto não encontrado.'], 404);
        }
    }

    public function update(Request $request, Category $category)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
                'identification_code' => 'required|string|max:255|unique:categories',
                'description' => 'required|string',
            ]);

            $category->update($request->all());
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Erro ao atualizar categoria: ' . $e->getMessage()], 500);
        }

        return $category;
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(null, 204);
    }
}
