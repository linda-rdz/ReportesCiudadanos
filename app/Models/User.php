<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword as ContractsCanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword;

class User extends Authenticatable implements ContractsCanResetPassword
{
    use HasFactory, Notifiable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relaciones
     */
    public function solicitudesComoCiudadano()
    {
        return $this->hasMany(Solicitud::class, 'ciudadano_id');
    }

    public function solicitudesComoFuncionario()
    {
        return $this->hasMany(Solicitud::class, 'funcionario_id');
    }

    /**
     * Verificar si el usuario es admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Verificar si el usuario es ciudadano
     */
    public function isCiudadano()
    {
        return $this->role === 'ciudadano';
    }
}

