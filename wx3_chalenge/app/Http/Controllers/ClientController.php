<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return Client::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|size:11|unique:clients,cpf',
            'birthdate' => 'required|date',
        ]);

        return Client::create($request->all());
    }

    public function show(Client $client)
    {
        return $client;
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|size:11|unique:clients,cpf,' . $client->id,
            'birthdate' => 'required|date',
        ]);

        $client->update($request->all());

        return $client;
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json(null, 204);
    }
}
