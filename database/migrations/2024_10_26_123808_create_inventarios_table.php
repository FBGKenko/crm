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
        // Schema::create('inventarios', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('codigo')->nullable();
        //     $table->string('nombreProducto')->nullable();
        //     $table->float('costo')->nullable();
        //     $table->float('precio')->nullable();
        //     $table->bigInteger('existencia')->nullable();
        //     $table->string('unidadMedida')->nullable();
        //     $table->string('fechaBorrado')->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('inventarios');
    }
};
