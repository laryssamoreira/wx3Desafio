<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class Shipping extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'distance', 'weight'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($shipping) {
            self::validate($shipping);
        });

        static::updating(function ($shipping) {
            self::validate($shipping);
        });
    }

    public static function validate($shipping)
    {
        $validator = Validator::make($shipping->attributesToArray(), [
            'value' => 'required|numeric|min:0',
            'distance' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
