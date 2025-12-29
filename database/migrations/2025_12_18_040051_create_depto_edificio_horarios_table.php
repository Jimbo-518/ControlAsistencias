<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('depto_edificio_horario', function (Blueprint $table) {
            $table->id('id_deh');

            $table->unsignedBigInteger('id_horario');
            $table->unsignedBigInteger('id_de');

            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->foreign('id_horario')
                ->references('id_horario')
                ->on('horarios');

            $table->foreign('id_de')
                ->references('id_de')
                ->on('depto_edificio');

            $table->unique(['id_horario', 'id_de', 'fecha_inicio']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('depto_edificio_horarios');
    }
};
