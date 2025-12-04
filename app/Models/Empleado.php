<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empleado extends Authenticatable
{
    use HasFactory;

    protected $table = 'empleados';

    protected $fillable = [
        'numero_empleado',
        'nombre',
        'contrasena_hash',
        'rol',
        'estado',
    ];

    protected $hidden = [
        'contrasena_hash',
    ];

    public function getAuthPassword()
    {
        return $this->contrasena_hash;
    }

    public function isAdmin()
    {
        return $this->rol === 'admin';
    }

    public function isActivo()
    {
        return $this->estado === 'activo';
    }
}

