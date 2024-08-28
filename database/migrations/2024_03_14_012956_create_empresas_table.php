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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombreEmpresa')->nullable();
            $table->string('telefono1')->nullable();
            $table->string('telefono2')->nullable();
            $table->string('telefono3')->nullable();
            $table->string('correo1')->nullable();
            $table->string('correo2')->nullable();
            $table->string('correo3')->nullable();
            $table->string('paginaWeb')->nullable();
            $table->foreignId('persona_id')->nullable()->constrained();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};

$table->string('nombreEmpresa')->nullable();
