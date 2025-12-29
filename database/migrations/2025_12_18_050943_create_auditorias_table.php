<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('auditoria', function (Blueprint $table) {
            $table->id('id_auditoria');

            $table->unsignedBigInteger('id_usuario')->nullable();

            $table->string('accion', 50);
            $table->string('tabla', 50);
            $table->unsignedBigInteger('id_registro')->nullable();

            $table->text('descripcion')->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->string('ip', 45)->nullable();

            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditorias');
    }
};
