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
        Schema::create('relacion_persona_empresas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->nullable()->constrained();
            $table->foreignId('empresa_id')->nullable()->constrained();
            $table->string('esCliente')->default("NO");
            $table->string('esPromotor')->default("NO");
            $table->string('esColaborador')->default("NO");
            $table->string('puesto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relacion_persona_empresas');
    }
};
