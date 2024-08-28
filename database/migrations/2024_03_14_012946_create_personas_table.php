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
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->date('fecha_registro')->nullable();
            $table->string('folio')->nullable();
            $table->foreignId('persona_id')->nullable()->constrained();
            $table->string('origen')->nullable();
            $table->string('referenciaOrigen')->nullable();
            $table->string('referenciaCampania')->nullable();
            $table->string('etiquetasOrigen')->nullable();
            $table->string('estatus')->nullable()->default('PENDIENTE');
            $table->string('apodo')->nullable();
            $table->string('nombres')->nullable();
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->string('genero')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('edadPromedio')->nullable();
            $table->string('telefonoCelular1')->nullable();
            $table->string('telefonoCelular2')->nullable();
            $table->string('telefonoCelular3')->nullable();
            $table->string('telefono_fijo')->nullable();
            $table->string('correo')->nullable();
            $table->string('correoAlternativo')->nullable();
            $table->string('nombre_en_facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('observaciones')->nullable();
            $table->string('etiquetas')->nullable();
            $table->boolean('supervisado')->default(0);
            $table->string('tipo')->default('SIN DEFINIR');
            $table->string('cliente')->nullable();
            $table->string('promotor')->nullable();
            $table->string('colaborador')->nullable();
            $table->string('campoPersonalizado')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
