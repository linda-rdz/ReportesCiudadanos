<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class Solicitud extends Model
{
    protected $table = 'solicitudes';
    
    protected $fillable = [
        'folio',
        'titulo',
        'descripcion',
        'categoria_id',
        'colonia_id',
        'direccion',
        'lat',
        'lng',
        'estado',
        'datos_personales',
        'ciudadano_id',
        'funcionario_id'
    ];

    protected $casts = [
        'datos_personales' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    public function mensajes()
    {
        return $this->hasMany(Mensaje::class);
    }

    public function ciudadano()
    {
        return $this->belongsTo(User::class, 'ciudadano_id');
    }

    public function funcionario()
    {
        return $this->belongsTo(User::class, 'funcionario_id');
    }

    /**
     * Generar un folio alfanumérico único
     */
    public static function generarFolio()
    {
        do {
            // Generar un folio de 8 caracteres: 2 letras + 6 números
            $letras = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2));
            $numeros = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $folio = $letras . $numeros;
        } while (self::where('folio', $folio)->exists());

        return $folio;
    }

    /**
     * Obtener la fecha formateada en la zona horaria local de México
     */
    public function getFechaFormateadaAttribute()
    {
        return $this->created_at->setTimezone('America/Mexico_City')->format('d/m/Y H:i');
    }

    /**
     * Obtener la fecha de actualización formateada en la zona horaria local de México
     */
    public function getFechaActualizacionFormateadaAttribute()
    {
        return $this->updated_at->setTimezone('America/Mexico_City')->format('d/m/Y H:i');
    }
}
