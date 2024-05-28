<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'discount'];

    // Validações
    public static function boot()
    {
        parent::boot();

        static::creating(function ($paymentMethod) {
            self::validate($paymentMethod);
        });

        static::updating(function ($paymentMethod) {
            self::validate($paymentMethod);
        });
    }

    public static function validate($paymentMethod)
    {
        $validator = Validator::make($paymentMethod->attributesToArray(), [
            'name' => 'required|string|unique:payment_methods,name|max:255',
            'discount' => 'required|numeric|between:0,100',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
