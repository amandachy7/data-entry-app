<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique(); // PRD-001
            $table->string('name');
            $table->string('category')->nullable();
            $table->decimal('price', 12, 0)->nullable(); // Recommended_Price_IDR
            $table->foreignId('tier_id')->constrained('tiers'); // dari Target_Tier
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};