@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-file-alt"></i> {{ $solicitud->titulo }}</h4>
                    <span class="badge 
                        @if($solicitud->estado === 'Pendiente') bg-warning text-dark
                        @elseif($solicitud->estado === 'En proceso') bg-info
                        @elseif($solicitud->estado === 'Resuelto') bg-success
                        @else bg-danger
                        @endif fs-6">
                        {{ $solicitud->estado }}
                    </span>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5><i class="fas fa-align-left"></i> Descripción del problema</h5>
                            <p class="text-muted">{{ \Illuminate\Support\Str::limit($solicitud->descripcion, 50) }}</p>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="fas fa-info-circle"></i> Información General</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <strong><i class="fas fa-tag text-primary"></i> Categoría:</strong> 
                                            {{ $solicitud->categoria->nombre ?? 'N/A' }}
                                        </li>
                                        <li class="mb-2">
                                            <strong><i class="fas fa-map-marker-alt text-danger"></i> Colonia:</strong> 
                                            {{ $solicitud->colonia->nombre ?? 'N/A' }}
                                        </li>
                                        <li class="mb-2">
                                            <strong><i class="fas fa-home text-primary"></i> Dirección:</strong> 
                                            {{ $solicitud->direccion ?: 'No especificada' }}
                                        </li>
                                        <li class="mb-2">
                                            <strong><i class="fas fa-calendar text-info"></i> Fecha de reporte:</strong> 
                                            {{ $solicitud->created_at->setTimezone('America/Mexico_City')->format('d/m/Y H:i') }}
                                        </li>
                                        @if($solicitud->datos_personales)
                                        <li class="mb-2">
                                            <strong><i class="fas fa-user text-secondary"></i> Nombre del ciudadano:</strong>
                                            {{ $solicitud->datos_personales['nombre'] ?? '' }} {{ $solicitud->datos_personales['apellido_paterno'] ?? '' }} {{ $solicitud->datos_personales['apellido_materno'] ?? '' }}
                                        </li>
                                        <li class="mb-2">
                                            <strong><i class="fas fa-phone text-secondary"></i> Celular:</strong>
                                            {{ $solicitud->datos_personales['celular'] ?? '' }}
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="fas fa-tasks"></i> Estado Actual</h6>
                                    <div class="alert 
                                        @if($solicitud->estado === 'Pendiente') alert-warning
                                        @elseif($solicitud->estado === 'En proceso') alert-info
                                        @elseif($solicitud->estado === 'Resuelto') alert-success
                                        @else alert-danger
                                        @endif">
                                        <h5 class="mb-0">{{ $solicitud->estado }}</h5>
                                        @if($solicitud->updated_at != $solicitud->created_at)
                                            <hr>
                                            <small><strong>Última actualización:</strong><br>{{ $solicitud->updated_at->setTimezone('America/Mexico_City')->format('d/m/Y H:i') }}</small>
                                        @endif
                                    </div>
                                    
                                    @if($solicitud->estado === 'Pendiente')
                                        <p class="text-muted"><i class="fas fa-clock"></i> <small>Tu solicitud está en espera de ser revisada.</small></p>
                                    @elseif($solicitud->estado === 'En proceso')
                                        <p class="text-muted"><i class="fas fa-spinner"></i> <small>Estamos trabajando en tu solicitud.</small></p>
                                    @elseif($solicitud->estado === 'Resuelto')
                                        <p class="text-muted"><i class="fas fa-check-circle"></i> <small>Tu solicitud ha sido resuelta.</small></p>
                                    @else
                                        <p class="text-muted"><i class="fas fa-times-circle"></i> <small>Tu solicitud fue rechazada.</small></p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            @if($solicitud->evidencias->count() > 0)
                                <h6><i class="fas fa-images"></i> Evidencias ({{ $solicitud->evidencias->count() }})</h6>
                                <div class="row">
                                    @foreach($solicitud->evidencias as $evidencia)
                                        <div class="col-12 mb-2">
                                            <img src="{{ asset('storage/' . str_replace('evidencias/', 'evidencias/thumbs/', $evidencia->ruta_archivo)) }}" 
                                                 class="img-fluid rounded shadow-sm" 
                                                 style="max-height: 200px; width: 100%; object-fit: cover; cursor: pointer;"
                                                  data-bs-toggle="modal" 
                                                  data-bs-target="#imageModal"
                                                 onerror="this.onerror=null; this.src='{{ asset('storage/' . $evidencia->ruta_archivo) }}'" 
                                                 onclick="showImage('{{ asset('storage/' . $evidencia->ruta_archivo) }}')">
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <small>No se proporcionaron evidencias para esta solicitud.</small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="mt-4">
                        <a href="{{ route('ciudadano.solicitudes.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Volver a mis solicitudes
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
                <h5 class="modal-title" id="imageModalLabel"><i class="fas fa-image"></i> Evidencia</h5>
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

