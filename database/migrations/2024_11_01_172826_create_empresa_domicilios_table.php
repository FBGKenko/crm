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
        Schema::create('empresa_domicilios', function (Blueprint $table) {
            $table->id();
            $table->string('tipo')->default('UBICACION');
            $table->foreignId('empresa_id')->nullable()->constrained();
            $table->foreignId('domicilio_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa_domicilios');
    }
};
