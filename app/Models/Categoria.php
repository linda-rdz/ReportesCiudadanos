<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    
    public $timestamps = false;
    
    protected $fillable = ['nombre', 'descripcion', 'icono'];

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }
}
