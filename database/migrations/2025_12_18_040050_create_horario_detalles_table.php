<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('horario_detalle', function (Blueprint $table) {
            $table->id('id_detalle');

            $table->unsignedBigInteger('id_horario');

            $table->tinyInteger('dia'); // 1 = Lunes ... 7 = Domingo

            $table->time('entrada');
            $table->integer('tolerancia')->default(0);

            $table->time('comida_inicio')->nullable();
            $table->time('comida_fin')->nullable();

            $table->time('salida');

            $table->timestamps();

            $table->foreign('id_horario')
                ->references('id_horario')
                ->on('horarios')
                ->onDelete('cascade');

            $table->unique(['id_horario', 'dia']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horario_detalles');
    }
};
