<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AddressController extends Controller
{
    public function index()
    {
        $objects = Address::all();

        if ($objects->isEmpty()) {
            return response()->json(['message' => 'Não há objetos encontrados'], 200);
        }

        return response()->json($objects, 200);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'identification_code' => 'required|string|max:255|unique:addresses',
                'street' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'neighborhood' => 'required|string|max:255',
                'number' => 'required|integer',
                'zipcode' => 'required|string|max:20',
                'complement' => 'nullable|string|max:255',
            ]);
            
            return Address::create($request->all());
        } catch (\Exception $e) {
            $sale->delete();
            return response()->json(['message' => 'Erro ao armazenar endereço: ' . $e->getMessage()], 500);
        }
        }

    public function show($id)
    {
        try {
            $address = Address::findOrFail($id);
        
            return response()->json($address, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Objeto não encontrado.'], 404);
        }
    }

    public function update(Request $request, Address $address)
    {
        try{
            $request->validate([
                'identification_code' => 'required|string|max:255|unique:addresses,identification_code,' . $address->id,
                'street' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'neighborhood' => 'required|string|max:255',
                'number' => 'required|integer',
                'zipcode' => 'required|string|max:20',
                'complement' => 'nullable|string|max:255',
            ]);

            $address->update($request->all());
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Erro ao atualizar endereço: ' . $e->getMessage()], 500);
        }
        return $address;
    }

    public function destroy(Address $address)
    {
        $address->delete();

        return response()->json(null, 204);
    }
}
