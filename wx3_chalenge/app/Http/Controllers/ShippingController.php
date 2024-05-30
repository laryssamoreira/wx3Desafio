<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        return Shipping::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric',
            'distance' => 'required|numeric',
            'weight' => 'required|numeric',
        ]);

        return Shipping::create($request->all());
    }

    public function show(Shipping $shipping)
    {
        return $shipping;
    }

    public function update(Request $request, Shipping $shipping)
    {
        $request->validate([
            'value' => 'required|numeric',
            'distance' => 'required|numeric',
            'weight' => 'required|numeric',
        ]);

        $shipping->update($request->all());

        return $shipping;
    }

    public function destroy(Shipping $shipping)
    {
        $shipping->delete();

        return response()->json(null, 204);
    }
}
