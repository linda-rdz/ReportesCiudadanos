<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colonia extends Model
{
    protected $table = 'colonias';
    
    public $timestamps = false;
    
    protected $fillable = ['nombre'];

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }
}

