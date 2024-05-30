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
            ->get();

        // Procura do produto mais vendido
        $maxProduct = $productQuantities->max('total_quantity');
        $productName = Item::where('product_id', $maxProduct->product_id)->first()->product->name;

        return response()->json(['product_name' => $productName, 'total_quantity' => $maxProduct->total_quantity]);
    }
}