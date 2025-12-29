<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('depto_edificio', function (Blueprint $table) {
            $table->id('id_de');

            $table->unsignedBigInteger('id_edificio');
            $table->unsignedBigInteger('id_departamento');

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->foreign('id_edificio')
                ->references('id_edificio')
                ->on('edificios');

            $table->foreign('id_departamento')
                ->references('id_departamento')
                ->on('departamentos');

            $table->unique(['id_edificio', 'id_departamento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('depto_edificios');
    }
};