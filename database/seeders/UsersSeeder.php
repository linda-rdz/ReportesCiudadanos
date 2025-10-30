<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Crear usuario ciudadano de prueba
        User::create([
            'name' => 'Ciudadano Test',
            'email' => 'ciudadano@test.com',
            'password' => Hash::make('password'),
            'role' => 'ciudadano',
        ]);
    }
}

