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
        Schema::create('identificacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained();
            //DATOS IDENTIFICACION
            $table->string('curp')->nullable();
            $table->string('rfc')->nullable();
            $table->longText('ine')->nullable();
            $table->string('lugarNacimiento')->nullable();
            //DATOS DE ELECTORALES
            $table->string('clave_elector')->nullable();
            $table->foreignId('seccion_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identificacions');
    }
};

