@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-search me-2"></i>Buscar Solicitud por Folio
                    </h4>
                </div>

                <div class="card-body">
                    <!-- Formulario de búsqueda -->
                    <form method="GET" action="{{ route('solicitudes.buscar') }}" class="mb-4">
                        <div class="input-group input-group-lg">
                            <input type="text" 
                                   class="form-control" 
                                   name="folio" 
                                   id="folio"
                                   placeholder="Ingresa tu folio (ej: AB123456)"  
                                   value="{{ old('folio', $folio ?? '') }}"
                                   required
                                   autofocus
                                   style="text-transform: uppercase;">
                            <input type="tel"
                                   class="form-control"
                                   name="telefono"
                                   id="telefono"
                                   placeholder="Teléfono (10 dígitos)"
                                   value="{{ old('telefono', $telefono ?? '') }}"
                                   required
                                   maxlength="10">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search me-1"></i>Buscar
                            </button>
                        </div>
                        <small class="form-text text-muted mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Ingresa folio y teléfono para consultar tu solicitud.
                        </small>
                    </form>

                    @if(isset($folio))
                        @if($solicitud)
                            <!-- Resultado encontrado -->
                            <div class="alert alert-success">
                                <h5 class="mb-3">
                                    <i class="fas fa-check-circle me-2"></i>Solicitud encontrada
                                </h5>
                                <p class="mb-0">
                                    <strong>Folio:</strong> {{ $solicitud->folio }}
                                </p>
                            </div>

                            <div class="card border">
                                <div class="card-header d-flex justify-content-end align-items-center">
                                    <span class="badge 
                                        @if($solicitud->estado === 'Pendiente') bg-warning
                                        @elseif($solicitud->estado === 'En proceso') bg-info
                                        @elseif($solicitud->estado === 'Resuelto') bg-success
                                        @else bg-danger
                                        @endif fs-6">
                                        {{ $solicitud->estado }}
                                    </span>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h6 class="text-primary">
                                                <i class="fas fa-file-alt me-2"></i>Descripción del problema
                                            </h6>
                                            <p class="fs-6">{{ $solicitud->descripcion }}</p>

                                            <div class="row mt-3">
                                        <div class="col-md-6">
                                                    <h6 class="text-primary">Información general</h6>
                                                    <ul class="list-unstyled">
                                                        <li><strong>Categoría:</strong> {{ $solicitud->categoria->nombre ?? 'N/A' }}</li>
                                                        <li><strong>Colonia:</strong> {{ $solicitud->colonia->nombre ?? 'N/A' }}</li>
                                                        <li><strong>Dirección:</strong> {{ $solicitud->direccion ?: 'No especificada' }}</li>
                                                        <li><strong>Fecha de reporte:</strong> {{ $solicitud->created_at->setTimezone('America/Mexico_City')->format('d/m/Y H:i') }}</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-primary">Estado de la solicitud</h6>
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            <strong>Estado actual:</strong> 
                                                            <span class="badge 
                                                                @if($solicitud->estado === 'Pendiente') bg-warning
                                                                @elseif($solicitud->estado === 'En proceso') bg-info
                                                                @elseif($solicitud->estado === 'Resuelto') bg-success
                                                                @else bg-danger
                                                                @endif">
                                                                {{ $solicitud->estado }}
                                                            </span>
                                                        </li>
                                                        @if($solicitud->updated_at != $solicitud->created_at)
                                                            <li><strong>Última actualización:</strong> {{ $solicitud->updated_at->setTimezone('America/Mexico_City')->format('d/m/Y H:i') }}</li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>

                                            @if($solicitud->datos_personales)
                                                <div class="mt-3">
                                                    <h6 class="text-primary">Datos del reporte</h6>
                                                    <ul class="list-unstyled">
                                                        <li><strong>Nombre:</strong> {{ $solicitud->datos_personales['nombre'] ?? 'N/A' }} {{ $solicitud->datos_personales['apellido_paterno'] ?? '' }} {{ $solicitud->datos_personales['apellido_materno'] ?? '' }}</li>
                                                        <li><strong>Celular:</strong> {{ $solicitud->datos_personales['celular'] ?? 'N/A' }}</li>
                                                        @if(isset($solicitud->datos_personales['email']) && $solicitud->datos_personales['email'])
                                                            <li><strong>Email:</strong> {{ $solicitud->datos_personales['email'] }}</li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-3">
                                            @if($solicitud->evidencias->count() > 0)
                                                <h6 class="text-primary">
                                                    <i class="fas fa-camera me-2"></i>Evidencias
                                                </h6>
                                                <div class="row">
                                                    @foreach($solicitud->evidencias as $evidencia)
                                                        <div class="col-12 mb-2">
                                                            <img src="{{ asset('storage/' . str_replace('evidencias/', 'evidencias/thumbs/', $evidencia->ruta_archivo)) }}" 
                                                                 class="img-fluid rounded shadow-sm" 
                                                                 style="max-height: 200px; object-fit: cover; cursor: pointer; width: 100%;"
                                                                 data-bs-toggle="modal" 
                                                                 data-bs-target="#imageModal"
                                                                 onerror="this.onerror=null; this.src='{{ asset('storage/' . $evidencia->ruta_archivo) }}'" 
                                                                 onclick="showImage('{{ asset('storage/' . $evidencia->ruta_archivo) }}')">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="alert alert-info">
                                                    <small><i class="fas fa-info-circle me-1"></i>No se proporcionaron evidencias para esta solicitud.</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <h6 class="text-primary">
                                            <i class="fas fa-envelope me-2"></i>Mensajes del administrador
                                        </h6>
                                        @if($solicitud->mensajes && $solicitud->mensajes->count())
                                            <ul class="list-group">
                                                @foreach($solicitud->mensajes as $mensaje)
                                                    <li class="list-group-item">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <span class="badge bg-secondary me-2">{{ $mensaje->tipo }}</span>
                                                                {{ $mensaje->contenido }}
                                                            </div>
                                                            <small class="text-muted">{{ $mensaje->created_at->setTimezone('America/Mexico_City')->format('d/m/Y H:i') }}</small>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted"><small>No hay mensajes aún.</small></p>
                                        @endif
                                    </div>

                                    <div class="mt-4 pt-3 border-top">
                                        <a href="{{ route('solicitudes.buscar') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-search me-1"></i>Buscar otra solicitud
                                        </a>
                                        <a href="{{ route('home') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-home me-1"></i>Ir al inicio
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- No se encontró la solicitud -->
                            <div class="alert alert-warning">
                                <h5 class="mb-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Solicitud no encontrada
                                </h5>
                                <p class="mb-0">
                                    No se encontró ninguna solicitud con el folio y teléfono proporcionados.
                                    Verifica que ambos datos sean correctos.
                                </p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar imágenes en grande -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Evidencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Evidencia">
            </div>
        </div>
    </div>
</div>

<script>
function showImage(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
}

document.getElementById('folio')?.addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});
document.getElementById('telefono')?.addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 10);
});
</script>
@endsection

