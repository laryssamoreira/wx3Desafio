<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $objects = PaymentMethod::all();

        if ($objects->isEmpty()) {
            return response()->json(['message' => 'Não há objetos encontrados'], 200);
        }

        return response()->json($objects, 200);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'discount' => 'required|numeric|min:0',
            ]);

            return PaymentMethod::create($request->all());
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Erro ao armazenar método de pagamento: ' . $e->getMessage()], 500);
        }
    }

    public function show(PaymentMethod $paymentMethod)
    {
        try {
            $paymentMethod = PaymentMethod::findOrFail($id);
        
            return response()->json($paymentMethod, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Objeto não encontrado.'], 404);
        }
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'discount' => 'required|numeric|min:0',
            ]);

            $paymentMethod->update($request->all());
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Erro ao atualizar método de pagamento: ' . $e->getMessage()], 500);
        }

        return $paymentMethod;
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return response()->json(null, 204);
    }
}
