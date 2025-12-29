<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('face_ids', function (Blueprint $table) {
            $table->id('id_face');

            $table->unsignedBigInteger('id_empleado');

            $table->json('embedding');
            $table->string('motor', 50);
            $table->string('version_modelo', 20)->nullable();

            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_registro')->useCurrent();

            $table->timestamps();

            $table->foreign('id_empleado')
                ->references('id_empleado')
                ->on('empleados')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('face_ids');
    }
};
