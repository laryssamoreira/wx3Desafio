<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        return PaymentMethod::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'discount' => 'required|numeric|min:0',
        ]);

        return PaymentMethod::create($request->all());
    }

    public function show(PaymentMethod $paymentMethod)
    {
        return $paymentMethod;
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'discount' => 'required|numeric|min:0',
        ]);

        $paymentMethod->update($request->all());

        return $paymentMethod;
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return response()->json(null, 204);
    }
}
