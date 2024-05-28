<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function store(Request $request)
    {
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
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(Request $request, Product $product)
    {
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

        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}
