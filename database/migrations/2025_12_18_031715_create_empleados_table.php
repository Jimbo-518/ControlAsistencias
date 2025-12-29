<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id('id_empleado');

            $table->string('nombre', 150);
            $table->string('apellido_paterno', 100);
            $table->string('apellido_materno', 100);
            $table->string('correo', 150);
            $table->string('telefono', 20);

            $table->date('fecha_ingreso');

            $table->unsignedBigInteger('id_depto_edificio');

            $table->enum('estatus', ['activo', 'inactivo', 'archivado'])
                ->default('activo');

            $table->integer('vacaciones_tomadas')->default(0);

            $table->timestamps();

            $table->foreign('id_depto_edificio')
                ->references('id_de')
                ->on('depto_edificio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
