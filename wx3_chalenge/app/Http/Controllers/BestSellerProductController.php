<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class BestSellerProductController extends Controller
{
    public function bestSellerProduct()
    {
        // Cálculo da quantidade total de cada produto
        $productQuantities = Item::groupBy('product_id')
            ->selectRaw('product_id, SUM(quantity) as total_quantity')
            ->orderBy('total_quantity', 'desc')
            ->first();

        // Verificação
        if (!$productQuantities) {
            return response()->json(['message' => 'Produtos não encontrados'], 404);
        }

        // Nome do produto mais vendido
        $product = Item::where('product_id', $productQuantities->product_id)
            ->first()
            ->product;

        return response()->json([
            'product_name' => $product->name,
            'total_quantity' => $productQuantities->total_quantity
        ]);
    }
}
