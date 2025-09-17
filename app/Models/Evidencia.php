<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evidencia extends Model
{
    protected $table = 'evidencias';
    
    protected $fillable = [
        'solicitud_id',
        'ruta_archivo'
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }
}
