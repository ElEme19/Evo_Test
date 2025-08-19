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
    Schema::create('autorizaciones', function (Blueprint $table) {
        $table->id();
        $table->string('num_chasis');
        $table->unsignedBigInteger('usuario_solicita'); // quien solicita
        $table->enum('estado', ['pendiente', 'autorizado', 'rechazado'])->default('pendiente');
        $table->string('token', 64)->unique(); // para el enlace del correo
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('autorizaciones');
}

};
