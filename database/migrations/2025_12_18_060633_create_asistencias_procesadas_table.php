<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asistencias_procesadas', function (Blueprint $table) {
            $table->id('id_registro');

            $table->unsignedBigInteger('id_empleado');
            $table->date('fecha');

            $table->string('estado');

            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();

            $table->integer('minutos_faltantes')->default(0);
            $table->integer('minutos_extra')->default(0);

            $table->unsignedBigInteger('id_tipo_incidencia')->nullable();
            $table->unsignedBigInteger('id_periodo')->nullable();

            $table->boolean('cerrado')->default(false);

            $table->boolean('inconsistencia')->default(false);
            $table->text('motivo_inconsistencia')->nullable();

            $table->timestamps();

            $table->foreign('id_empleado')
                ->references('id_empleado')
                ->on('empleados');

            $table->foreign('id_tipo_incidencia')
                ->references('id_tipo')
                ->on('tipo_incidencia');

            $table->foreign('id_periodo')
                ->references('id_periodo')
                ->on('periodos_control');

            $table->unique(['id_empleado', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias_procesadas');
    }
};
