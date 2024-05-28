<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->identification_code = uniqid(); // Gera um ID único
        });
    }
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

