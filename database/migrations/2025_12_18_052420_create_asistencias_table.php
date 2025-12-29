<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id('id_registro');

            $table->unsignedBigInteger('id_empleado');
            $table->date('fecha');
            $table->time('hora');

            $table->enum('tipo_registro', ['entrada', 'salida']);
            $table->enum('origen', ['web', 'movil', 'biometrico', 'qr', 'manual']);

            $table->boolean('archivado')->default(false);

            $table->timestamps();

            $table->foreign('id_empleado')
                ->references('id_empleado')
                ->on('empleados')
                ->onDelete('cascade');

            $table->index(['id_empleado', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
