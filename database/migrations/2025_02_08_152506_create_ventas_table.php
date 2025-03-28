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
        // Schema::create('ventas', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('folio')->nullable();
        //     $table->dateTime('fechaCotizacionCreada')->nullable();
        //     $table->dateTime('fechaVentaCreada')->nullable();
        //     $table->dateTime('fechaEmpaquetado')->nullable();
        //     $table->dateTime('fechaEnviado')->nullable();
        //     $table->dateTime('fechaEntregado')->nullable();
        //     $table->float('montoEnvio')->nullable();
        //     $table->float('total')->nullable();
        //     $table->binary('comprobantePago')->nullable();
        //     $table->string('nombreComprobantePago')->nullable();
        //     $table->string('estatus')->default('COTIZACIÓN');
        //     $table->foreignId('vendedor_inicial_id')->nullable()->constrained();
        //     $table->foreignId('cliente_id')->nullable()->constrained();
        //     $table->foreignId('domicilio_id')->nullable()->constrained();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('ventas');
    }
};
