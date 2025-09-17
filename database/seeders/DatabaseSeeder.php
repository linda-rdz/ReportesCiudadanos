<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Colonia;
use App\Models\Solicitud;
use App\Models\Evidencia;
use App\Enums\EstadoSolicitud;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Crear usuarios de prueba
        $ciudadano = User::create([
            'name' => 'Juan Pérez',
            'email' => 'ciudadano@test.com',
            'password' => Hash::make('password'),
            'role' => 'ciudadano',
        ]);

        $funcionario = User::create([
            'name' => 'María García',
            'email' => 'funcionario@test.com',
            'password' => Hash::make('password'),
            'role' => 'funcionario',
        ]);

        // Crear categorías
        $categorias = [
            ['nombre' => 'Baches'],
            ['nombre' => 'Alumbrado Público'],
            ['nombre' => 'Fugas de Agua'],
            ['nombre' => 'Limpieza'],
            ['nombre' => 'Semáforos'],
            ['nombre' => 'Señalización'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }

        // Crear colonias
        $colonias = [
            ['nombre' => 'Centro'],
            ['nombre' => 'Zona Norte'],
            ['nombre' => 'Zona Sur'],
            ['nombre' => 'Zona Este'],
            ['nombre' => 'Zona Oeste'],
            ['nombre' => 'Residencial'],
        ];

        foreach ($colonias as $colonia) {
            Colonia::create($colonia);
        }

        // Crear algunas solicitudes de ejemplo
        $solicitudes = [
            [
                'titulo' => 'Bache en calle principal',
                'descripcion' => 'Hay un bache grande en la calle principal que está causando problemas a los vehículos.',
                'categoria_id' => 1,
                'colonia_id' => 1,
                'direccion' => 'Calle Principal #123',
                'estado' => EstadoSolicitud::PENDIENTE,
                'ciudadano_id' => $ciudadano->id,
            ],
            [
                'titulo' => 'Lámpara fundida',
                'descripcion' => 'La lámpara de la esquina está fundida desde hace varios días.',
                'categoria_id' => 2,
                'colonia_id' => 2,
                'direccion' => 'Esquina de las calles A y B',
                'estado' => EstadoSolicitud::EN_PROCESO,
                'ciudadano_id' => $ciudadano->id,
                'funcionario_id' => $funcionario->id,
            ],
            [
                'titulo' => 'Fuga de agua en la calle',
                'descripcion' => 'Hay una fuga de agua en la calle que está causando charcos.',
                'categoria_id' => 3,
                'colonia_id' => 3,
                'direccion' => 'Calle Secundaria #456',
                'estado' => EstadoSolicitud::RESUELTO,
                'ciudadano_id' => $ciudadano->id,
                'funcionario_id' => $funcionario->id,
            ],
        ];

        foreach ($solicitudes as $solicitud) {
            Solicitud::create($solicitud);
        }

        $this->command->info('Datos de prueba creados exitosamente!');
        $this->command->info('Usuario ciudadano: ciudadano@test.com / password');
        $this->command->info('Usuario funcionario: funcionario@test.com / password');
    }
}