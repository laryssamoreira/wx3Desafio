<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShippingController extends Controller
{
    public function index()
    {
        $objects = Shipping::all();

        if ($objects->isEmpty()) {
            return response()->json(['message' => 'Não há objetos encontrados'], 200);
        }

        return response()->json($objects, 200);
    }

    public function store(Request $request)
    {
        try{
            $request->validate([
                'value' => 'required|numeric',
                'distance' => 'required|numeric',
                'weight' => 'required|numeric',
            ]);

            return Shipping::create($request->all());
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Erro ao armazenar frete: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $shipping = Shipping::findOrFail($id);
        
            return response()->json($shipping, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Objeto não encontrado.'], 404);
        }
    }

    public function update(Request $request, Shipping $shipping)
    {
        try{
            $request->validate([
                'value' => 'required|numeric',
                'distance' => 'required|numeric',
                'weight' => 'required|numeric',
            ]);

            $shipping->update($request->all());
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Erro ao atualizar frete: ' . $e->getMessage()], 500);
        }

        return $shipping;
    }

    public function destroy(Shipping $shipping)
    {
        $shipping->delete();

        return response()->json(null, 204);
    }
}
