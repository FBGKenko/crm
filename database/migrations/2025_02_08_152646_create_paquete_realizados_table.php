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
        // Schema::create('paquete_realizados', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('tipoPaquete');
        //     $table->string('peso')->nullable();
        //     $table->binary('documentoGuia')->nullable();
        //     $table->string('estatusDocumentoGuia')->nullable();
        //     $table->foreignId('venta_id')->nullable()->constrained();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('paquete_realizados');
    }
};
