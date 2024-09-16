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
        Schema::create('perfil_modelo_relacionados', function (Blueprint $table) {
            $table->id();
            $table->string('modelo')->nullable();
            $table->unsignedBigInteger('idAsociado')->nullable();
            $table->foreignId('perfil_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfil_modelo_relacionados');
    }
};
