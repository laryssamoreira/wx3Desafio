<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Stock;
use App\Models\PaymentMethod;
use App\Models\Shipping;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        return Sale::all();
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
        foreach ($request->items as $item) {
            $stock = Stock::where('product_id', $item['product_id'])-> where('size', $item['size']) ->first();
            
            if (!$stock || $stock->quantity < $item['quantity']) {
                return response()->json(['message' => 'Quantidade insuficiente em estoque para o produto com ID ' . $item['product_id']], 400);
            }
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

        $sale = Sale::create([
            'total_price' => $totalPrice,
            'discounts' => $discount,
            'address_id' => $request->address_id,
            'client_id' => $request->client_id,
            'shipping_id' => $request->shipping_id,
            'payment_method_id' => $request->payment_method_id,
        ]);

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

    public function show(Sale $sale)
    {
        return $sale;
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'total_price' => 'required|numeric',
            'discount' => 'required|numeric',
            'address_id' => 'required|exists:addresses,id',
            'client_id' => 'required|exists:clients,id',
            'shipping_id' => 'required|exists:shippings,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $sale->update($request->all());

        return $sale;
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();

        return response()->json(null, 204);
    }
}
