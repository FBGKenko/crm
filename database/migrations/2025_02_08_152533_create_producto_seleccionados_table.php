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
        // Schema::create('producto_seleccionados', function (Blueprint $table) {
        //     $table->id();
        //     $table->float('precioEscogido')->nullable();
        //     $table->integer('cantidadEscogido')->nullable();
        //     $table->integer('unidad')->nullable();
        //     $table->boolean('anexado')->default(false);
        //     $table->foreignId('variante_id')->nullable()->constrained();
        //     $table->foreignId('venta_id')->nullable()->constrained();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('producto_seleccionados');
    }
};
