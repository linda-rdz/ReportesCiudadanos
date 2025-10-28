@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $solicitud->titulo }}</h4>
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
                        <div class="col-md-8">
                            <h5>Descripción del problema</h5>
                            <p class="text-muted">{{ $solicitud->descripcion }}</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Información general</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Categoría:</strong> {{ $solicitud->categoria->nombre ?? 'N/A' }}</li>
                                        <li><strong>Colonia:</strong> {{ $solicitud->colonia->nombre ?? 'N/A' }}</li>
                                        <li><strong>Dirección:</strong> {{ $solicitud->direccion ?: 'No especificada' }}</li>
                                        <li><strong>Fecha de reporte:</strong> {{ $solicitud->created_at->format('d/m/Y H:i') }}</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6>Estado actual</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Estado:</strong> {{ $solicitud->estado }}</li>
                                        @if($solicitud->updated_at != $solicitud->created_at)
                                            <li><strong>Última actualización:</strong> {{ $solicitud->updated_at->format('d/m/Y H:i') }}</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            @if($solicitud->evidencias->count() > 0)
                                <h6>Evidencias</h6>
                                <div class="row">
                                    @foreach($solicitud->evidencias as $evidencia)
                                        <div class="col-12 mb-2">
                                            <img src="{{ asset('storage/' . $evidencia->ruta_archivo) }}" 
                                                 class="img-fluid rounded shadow-sm" 
                                                 style="max-height: 200px; object-fit: cover; cursor: pointer;"
                                                 data-bs-toggle="modal" 
                                                 data-bs-target="#imageModal"
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
