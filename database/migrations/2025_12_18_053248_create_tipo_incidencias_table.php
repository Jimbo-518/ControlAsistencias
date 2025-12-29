<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tipo_incidencia', function (Blueprint $table) {
            $table->id('id_tipo');

            $table->string('nombre');
            $table->text('descripcion')->nullable();

            $table->enum('alcance', ['dia_completo', 'parcial'])
                ->default('dia_completo');

            $table->boolean('genera_falta')->default(false);
            $table->boolean('elimina_falta')->default(false);

            $table->boolean('permite_asistencia')->default(true);

            $table->boolean('modifica_horario')->default(false);
            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_incidencia');
    }
};
