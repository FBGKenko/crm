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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('claveCamarena')->nullable();
            $table->string('nombreCorto');
            $table->string('codigo')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('identificadorUrl')->nullable();
            $table->string('nombreWeb')->nullable();
            $table->string('presentacion')->nullable();
            $table->text('videoUsoUrl')->nullable();
            $table->text('fichaTecnicaUrl')->nullable();
            $table->text('descripcionWeb')->nullable();
            $table->foreignId('categoria_id')->nullable()->constrained();
            $table->dateTime('fechaBorrado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
