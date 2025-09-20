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
        Schema::create('pedido_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->nullable()->constrained('pedidos')->onDelete('set null');
            $table->foreignId('producto_id')->nullable()->constrained('productos')->onDelete('set null');
            $table->unsignedBigInteger('variante_id')->nullable();
            $table->float('precioUnitario', 10, 2)->default(0);
            $table->integer('cantidad')->default(1);
            $table->string('estatus')->default('pendiente'); // pendiente, enviado, entregado
            $table->string('estatusCliente')->default('pendiente'); // pendiente, enviado, entregado
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_productos');
    }
};
