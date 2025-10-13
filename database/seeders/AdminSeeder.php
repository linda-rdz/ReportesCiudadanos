<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'Administrador Principal',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);

        Admin::create([
            'name' => 'Supervisor Municipal',
            'email' => 'supervisor@test.com',
            'password' => Hash::make('password'),
        ]);
    }
}
