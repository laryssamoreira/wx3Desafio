<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_price',
        'discounts',
        'address_id',
        'client_id',
        'shipping_id',
        'payment_method_id',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            self::validate($sale);
        });

        static::updating(function ($sale) {
            self::validate($sale);
        });
    }

    public static function validate($sale)
    {
        $validator = Validator::make($sale->attributesToArray(), [
            'total_price' => 'nullable|numeric|min:0',
            'discounts' => 'nullable|numeric|min:0',
            'address_id' => 'required|exists:addresses,id',
            'client_id' => 'required|exists:clients,id',
            'shipping_id' => 'required|exists:shippings,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    // Definir relacionamentos
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}

