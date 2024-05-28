<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'identification_code',
        'name',
        'color',
        'image',
        'price',
        'description',
        'register_date',
        'weight',
        'category_id'
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

