<?php

namespace App\Enums;

enum EstadoSolicitud: string
{
    case PENDIENTE = 'Pendiente';
    case EN_PROCESO = 'En proceso';
    case RESUELTO = 'Resuelto';
    case RECHAZADO = 'Rechazado';
}
