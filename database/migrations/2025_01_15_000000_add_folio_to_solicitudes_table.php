<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Verificar si la columna ya existe antes de agregarla
        if (!Schema::hasColumn('solicitudes', 'folio')) {
            Schema::table('solicitudes', function (Blueprint $table) {
                $table->string('folio', 20)->nullable()->after('id');
            });
            
            // Agregar el índice único después de crear la columna
            Schema::table('solicitudes', function (Blueprint $table) {
                $table->unique('folio');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropColumn('folio');
        });
    }
};

