<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Stock;
use App\Models\PaymentMethod;
use App\Models\Shipping;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::all();

        if ($sales->isEmpty()) {
            return response()->json(['message' => 'Não há objetos encontrados'], 200);
        }

        return response()->json($sales, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'client_id' => 'required|exists:clients,id',
            'shipping_id' => 'required|exists:shippings,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.selling_price' => 'required|numeric|min:0',
            'items.*.size' => 'required|string|max:255',
            'items.*.product_id' => 'required|exists:products,id',
        ]);

        // Verificação de estoque
        try{
            foreach ($request->items as $item) {
                $stock = Stock::where('product_id', $item['product_id'])-> where('size', $item['size']) ->first();
                
                if (!$stock || $stock->quantity < $item['quantity']) {
                    return response()->json(['message' => 'Quantidade insuficiente em estoque para o produto com ID ' . $item['product_id']], 400);
                }
            }
        } catch (\Exception $e) {
            $sale->delete();
            return response()->json(['message' => 'Erro ao gerar venda: ' . $e->getMessage()], 500);
        }

        // Obtenção do valor de desconto relacionado a forma de pagamento
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);
        $discount = $paymentMethod->discount;

        //Cálculo do valor total da compra
        $totalPrice = 0;
        foreach ($request->items as $item) {
            $totalPrice += $item['quantity'] * $item['selling_price'];
        }
        $shipping = Shipping::findOrFail($request->shipping_id);
        $totalPrice += $shipping->value;
        $totalPrice -= $totalPrice * $discount;

        try {
            $sale = Sale::create([
                'total_price' => $totalPrice,
                'discounts' => $discount,
                'address_id' => $request->address_id,
                'client_id' => $request->client_id,
                'shipping_id' => $request->shipping_id,
                'payment_method_id' => $request->payment_method_id,
            ]);
        } catch (\Exception $e) {
            $sale->delete();
            return response()->json(['message' => 'Erro ao gerar venda: ' . $e->getMessage()], 500);
        }

        try {
            foreach ($request->items as $itemData) {
                $sale->items()->create([
                    'quantity' => $itemData['quantity'],
                    'selling_price' => $itemData['selling_price'],
                    'size' => $itemData['size'],
                    'product_id' => $itemData['product_id'],
                    'sale_id' => $sale->id,
                ]);
            }
        } catch (\Exception $e) {
            $sale->delete();
            return response()->json(['message' => 'Erro ao criar itens: ' . $e->getMessage()], 500);
        }

        // Atualização de estoque
        foreach ($request->items as $item) {
            $stock = Stock::where('product_id', $item['product_id'])-> where('size', $item['size']) ->first();
            
            if (!$stock || $stock->quantity >= $item['quantity']) {
                $stock->decrement('quantity', $item['quantity']);
            }
        }
    
    
        return $sale;
    }

    public function show($id)
    {
        try {
            $sale = Sale::findOrFail($id);
            
            $items = Item::where('sale_id', $sale->id)->get();

            $sale->items = $items;

            return response()->json($sale, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Venda não encontrada.'], 404);
        }
    }

    // public function update(Request $request, Sale $sale)
    // {
    //     $request->validate([
    //         'total_price' => 'required|numeric',
    //         'discount' => 'required|numeric',
    //         'address_id' => 'required|exists:addresses,id',
    //         'client_id' => 'required|exists:clients,id',
    //         'shipping_id' => 'required|exists:shippings,id',
    //         'payment_method_id' => 'required|exists:payment_methods,id',
    //     ]);

    //     $sale->update($request->all());

    //     return $sale;
    // }

    public function destroy(Sale $sale)
    {
        $sale->delete();

        return response()->json(null, 204);
    }
}
