<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');

            $table->unsignedBigInteger('id_empleado')->unique();
            $table->string('usuario', 50)->unique();
            $table->string('password');
            $table->unsignedBigInteger('id_rol');

            $table->boolean('activo')->default(true);
            $table->timestamp('ultimo_acceso')->nullable();

            $table->timestamps();

            $table->foreign('id_empleado')
                ->references('id_empleado')
                ->on('empleados')
                ->onDelete('cascade');

            $table->foreign('id_rol')
                ->references('id_rol')
                ->on('roles')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
