@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
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
                            <h5>Descripción del problema</h5>
                            <p class="fs-6">{{ $solicitud->descripcion }}</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Información general</h6>
                                    <ul class="list-unstyled">
                                        @if($solicitud->folio)
                                            <li><strong>Folio:</strong> <span class="badge bg-primary">{{ $solicitud->folio }}</span></li>
                                        @endif
                                        <li><strong>Categoría:</strong> {{ $solicitud->categoria->nombre ?? 'N/A' }}</li>
                                        <li><strong>Colonia:</strong> {{ $solicitud->colonia->nombre ?? 'N/A' }}</li>
                                        <li><strong>Dirección:</strong> {{ $solicitud->direccion ?: 'No especificada' }}</li>
                                        <li><strong>Fecha de reporte:</strong> {{ $solicitud->created_at->setTimezone('America/Mexico_City')->format('d/m/Y H:i') }}</li>
                                        @if($solicitud->datos_personales)
                                            <li><strong>Nombre del ciudadano:</strong> {{ $solicitud->datos_personales['nombre'] ?? '' }} {{ $solicitud->datos_personales['apellido_paterno'] ?? '' }} {{ $solicitud->datos_personales['apellido_materno'] ?? '' }}</li>
                                            <li><strong>Celular:</strong> {{ $solicitud->datos_personales['celular'] ?? '' }}</li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6>Estado actual</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Estado:</strong> {{ $solicitud->estado }}</li>
                                        @if($solicitud->updated_at != $solicitud->created_at)
                                            <li><strong>Última actualización:</strong> {{ $solicitud->updated_at->setTimezone('America/Mexico_City')->format('d/m/Y H:i') }}</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            @if($solicitud->evidencias->count() > 0)
                                <h6>Evidencias</h6>
                                <div class="row">
                                    @foreach($solicitud->evidencias as $evidencia)
                                        <div class="col-6 col-md-4 mb-2">
                                            <img src="{{ asset('storage/' . str_replace('evidencias/', 'evidencias/thumbs/', $evidencia->ruta_archivo)) }}" 
                                                 class="img-fluid rounded shadow-sm" 
                                                 style="height: 120px; width: 100%; object-fit: cover; cursor: pointer;"
                                                 loading="lazy"
                                                 data-bs-toggle="modal" 
                                                 data-bs-target="#imageModal"
                                                 onerror="this.onerror=null; this.src='{{ asset('storage/' . $evidencia->ruta_archivo) }}'" 
                                                 onclick="showImage('{{ asset('storage/' . $evidencia->ruta_archivo) }}')">
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <small>No se proporcionaron evidencias para esta solicitud.</small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr>
                    <h5>Mensajes del administrador</h5>
                    @if($solicitud->mensajes && $solicitud->mensajes->count())
                        <ul class="list-group mb-3">
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
                        <p class="text-muted">Aún no hay mensajes.</p>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('solicitudes.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al listado
                        </a>
                    </div>
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
</script>
@endsection
