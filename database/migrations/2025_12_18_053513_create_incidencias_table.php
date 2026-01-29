<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id('id_incidencia');

            $table->unsignedBigInteger('id_empleado');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');

            $table->text('descripcion')->nullable();

            $table->unsignedBigInteger('id_tipo_incidencia');

            $table->unsignedBigInteger('auditor')->nullable();

            $table->string('evidencia_url')->nullable();

            $table->enum('estatus', ['pendiente', 'aplicada', 'rechazada'])->default('pendiente');

            $table->timestamp('fecha_registro')->useCurrent();

            $table->boolean('archivada')->default(false);

            $table->timestamps();

            $table->foreign('id_empleado')
                ->references('id_empleado')
                ->on('empleados')
                ->onDelete('cascade');

            $table->foreign('id_tipo_incidencia')
                ->references('id_tipo')
                ->on('tipo_incidencia');

            $table->foreign('auditor')
                ->references('id_usuario')
                ->on('usuarios')
                ->nullOnDelete();

            $table->index(['id_empleado', 'fecha']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};
