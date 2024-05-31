<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Rules\ValidCpf;

class ClientController extends Controller
{
    public function index()
    {
        $objects = Client::all();

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
                'cpf' => ['required', 'string', 'size:11', 'unique:clients,cpf', new ValidCpf],
                'birthdate' => 'required|date',
            ]);
    
            return Client::create($request->all());
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Erro ao armazenar cliente: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $client = Client::findOrFail($id);
        
            return response()->json($client, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Objeto não encontrado.'], 404);
        }
    }

    public function update(Request $request, Client $client)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'birthdate' => 'required|date',
            ]);

            $data = $request->except(['cpf']);
            $client->update($request->all());
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Erro ao atualizar cliente: ' . $e->getMessage()], 500);
        }
        

        return $client;
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json(null, 204);
    }
}
