<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'identification_code',
        'street',
        'city',
        'neighborhood',
        'number',
        'zipcode',
        'complement'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($address) {
            self::validate($address);
        });

        static::updating(function ($address) {
            self::validate($address);
        });
    }

    public static function validate($address)
    {
        $validator = Validator::make($address->attributesToArray(), [
            'identification_code' => 'required|string|unique:addresses,identification_code',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'zipcode' => 'required|string|size:8|regex:/^\d{8}$/',
            'complement' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
