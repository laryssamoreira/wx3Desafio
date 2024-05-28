<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->decimal('selling_price', 10, 2);
            $table->string('size');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('sale_id')->constrained('sales');
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
