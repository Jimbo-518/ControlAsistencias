<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario_confirmaciones', function (Blueprint $table) {
            $table->id('id_confirmacion');
            $table->unsignedBigInteger('id_usuario');
            $table->string('token', 100)->unique();
            $table->timestamp('expira_en')->nullable();
            $table->boolean('confirmado')->default(0);
            $table->timestamps();

            $table->foreign('id_usuario')
                  ->references('id_usuario')
                  ->on('usuarios')
                  ->onDelete('cascade');
        });

        // Asegurar que usuarios pueda manejar activación
        Schema::table('usuarios', function (Blueprint $table) {
            if (!Schema::hasColumn('usuarios', 'activo')) {
                $table->boolean('activo')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario_confirmaciones');
    }
};
