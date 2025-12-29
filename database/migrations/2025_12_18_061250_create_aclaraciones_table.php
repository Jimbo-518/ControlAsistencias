<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aclaraciones', function (Blueprint $table) {
            $table->id('id_aclaracion');

            $table->unsignedBigInteger('id_registro');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_tipo_incidencia');

            $table->text('descripcion');

            $table->timestamp('fecha_aclaracion')->useCurrent();

            $table->string('evidencia')->nullable();

            $table->boolean('archivado')->default(false);

            $table->timestamps();

            $table->foreign('id_registro')
                ->references('id_registro')
                ->on('asistencias_procesadas');

            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios');

            $table->foreign('id_tipo_incidencia')
                ->references('id_tipo')
                ->on('tipo_incidencia');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aclaraciones');
    }
};
