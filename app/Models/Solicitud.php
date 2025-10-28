<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $table = 'solicitudes';
    
    protected $fillable = [
        'titulo',
        'descripcion',
        'categoria_id',
        'colonia_id',
        'direccion',
        'lat',
        'lng',
        'estado',
        'datos_personales'
    ];

    protected $casts = [
        'datos_personales' => 'array',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function colonia()
    {
        return $this->belongsTo(Colonia::class);
    }

    public function evidencias()
    {
        return $this->hasMany(Evidencia::class);
    }

    // Relaciones de usuario eliminadas - sistema sin autenticación
}
