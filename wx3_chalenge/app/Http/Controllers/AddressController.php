<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        return Address::all();
    }

    public function store(Request $request)
    {
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
    }

    public function show(Address $address)
    {
        return $address;
    }

    public function update(Request $request, Address $address)
    {
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

        return $address;
    }

    public function destroy(Address $address)
    {
        $address->delete();

        return response()->json(null, 204);
    }
}
