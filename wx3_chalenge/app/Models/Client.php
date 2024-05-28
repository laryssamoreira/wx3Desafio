<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'cpf', 'birthdate'];

    // Validações
    public static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            $validator = Validator::make($client->attributesToArray(), [
                'name' => 'required|string|max:255',
                'cpf' => 'required|string|size:11|unique:clients,cpf|regex:/^\d{11}$/',
                'birthdate' => 'required|date|before:today',
            ], [
                'cpf.regex' => 'The CPF must contain only digits.',
                'birthdate.before' => 'The birthdate must be a date before today.',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        });

        static::updating(function ($client) {
            $validator = Validator::make($client->attributesToArray(), [
                'name' => 'required|string|max:255',
                'cpf' => 'required|string|size:11|unique:clients,cpf,' . $client->id,
                'birthdate' => 'required|date|before:today',
            ], [
                'cpf.regex' => 'The CPF must contain only digits.',
                'birthdate.before' => 'The birthdate must be a date before today.',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        });
    }
}


