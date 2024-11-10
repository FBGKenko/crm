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
            $table->boolean('supervisado')->default(0);

            //DATOS DE CONTACTO
            $table->date('fecha_registro')->nullable();
            $table->string('folio')->nullable();
            $table->unsignedBigInteger('promotor_id')->nullable();
            $table->foreign('promotor_id')
                ->references('id')->on('personas')->onDelete('set null');
            $table->string('origen')->nullable();
            $table->string('referenciaOrigen')->nullable();
            $table->string('referenciaCampania')->nullable();
            $table->string('etiquetasOrigen')->nullable();
            $table->string('estatus')->nullable()->default('PENDIENTE');

            //DATOS PERSONALES
            $table->string('apodo')->nullable();
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->string('nombres')->nullable();
            $table->string('genero')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('rangoEdad')->nullable();

            //DATOS DE CONTACTO
            //MEDIANTE RELACIONES TELEFONO Y CORREO
            $table->string('nombre_en_facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();

            //DATOS DE RELACION
            $table->string('afiliado')->nullable();
            $table->string('simpatizante')->nullable();
            $table->string('programa')->nullable();
            $table->string('cliente')->nullable();
            $table->string('promotor')->nullable();
            $table->string('colaborador')->nullable();

            //OTROS DATOS
            $table->string('etiquetas')->nullable();
            $table->string('observaciones')->nullable();

            //DATOS DE ESTRUCTURA
            $table->string('rolEstructura')->nullable();
            $table->string('coordinadorDe')->nullable();
            $table->string('funcionAsignada')->nullable();

            //DATOS RELACION EMPRESA
            //TABLA RELACION EMPRESA_PERSONAS

            //DATOS DE FACTURA
            //TABLA RELACION PERSONA_DOMICILIOS

            //DATOS DE DOCUMENTOS
            //PENDIENTEEEEEEE

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
