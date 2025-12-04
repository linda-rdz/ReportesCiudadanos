<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $table = 'mensajes';

    protected $fillable = [
        'solicitud_id',
        'user_id',
        'contenido',
        'tipo',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

