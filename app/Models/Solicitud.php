<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\EstadoSolicitud;

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
        'ciudadano_id',
        'funcionario_id'
    ];

    protected $casts = [
        'estado' => EstadoSolicitud::class,
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

    public function ciudadano()
    {
        return $this->belongsTo(User::class, 'ciudadano_id');
    }

    public function funcionario()
    {
        return $this->belongsTo(User::class, 'funcionario_id');
    }
}
