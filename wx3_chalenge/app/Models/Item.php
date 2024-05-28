<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'selling_price',
        'size',
        'product_id',
        'sale_id',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            self::validate($item);
        });

        static::updating(function ($item) {
            self::validate($item);
        });
    }

    public static function validate($item)
    {
        $validator = Validator::make($item->attributesToArray(), [
            'quantity' => 'required|integer|min:1',
            'selling_price' => 'required|numeric|min:0',
            'size' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'sale_id' => 'required|exists:sales,id',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}

