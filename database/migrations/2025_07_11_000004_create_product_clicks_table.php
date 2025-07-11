<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // Nombre del producto clickeado
            $table->decimal('product_price', 10, 2)->nullable(); // Precio del producto
            $table->string('product_category')->nullable(); // Categoría del producto
            $table->string('product_sku')->nullable(); // SKU o código del producto
            $table->string('button_text')->default('Comprar'); // Texto del botón clickeado
            $table->string('session_id')->nullable(); // ID de sesión del usuario
            $table->string('ip_address', 45)->nullable(); // IP del visitante
            $table->text('user_agent')->nullable(); // User agent del navegador
            $table->string('referrer')->nullable(); // Página de origen
            $table->json('product_data')->nullable(); // Datos completos del producto en JSON
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index('landing_id');
            $table->index('product_name');
            $table->index('product_category');
            $table->index(['landing_id', 'product_name']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_clicks');
    }
}; 