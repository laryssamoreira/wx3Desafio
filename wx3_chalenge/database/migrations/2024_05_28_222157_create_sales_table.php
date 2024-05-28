<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_price', 10, 2);
            $table->decimal('discounts', 10, 2);
            $table->foreignId('address_id')->constrained('addresses');
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('shipping_id')->constrained('shippings');
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};

