<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('categorias')
            ->where('nombre', 'Señalización')
            ->update(['icono' => 'exclamation-triangle']);
    }

    public function down()
    {
        DB::table('categorias')
            ->where('nombre', 'Señalización')
            ->update(['icono' => 'sign']);
    }
};

