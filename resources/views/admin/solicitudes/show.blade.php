@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <a href="{{ route('admin.solicitudes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Panel
                </a>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-file-alt"></i> Detalles de la Solicitud #{{ $solicitud->id }}</h4>
                </div>
                <div class="card-body">
                    <!-- Estado y acciones -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Estado Actual:</h5>
                            <span class="badge 
                                @if($solicitud->estado === 'Pendiente') bg-warning
                                @elseif($solicitud->estado === 'En proceso') bg-info
                                @elseif($solicitud->estado === 'Resuelto') bg-success
                                @else bg-danger
                                @endif fs-5">
                                {{ $solicitud->estado }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <h5>Cambiar Estado:</h5>
                            <form action="{{ route('admin.solicitudes.updateEstado', $solicitud) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <div class="input-group">
                                    <select name="estado" class="form-select">
                                        @foreach(['Pendiente','En proceso','Resuelto','Rechazado'] as $estado)
                                            <option value="{{ $estado }}" @selected($solicitud->estado === $estado)>
                                                {{ $estado }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <hr>

                    <!-- Información de la solicitud -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5><i class="fas fa-tag"></i> Categoría</h5>
                            <p class="fs-6">{{ $solicitud->categoria->nombre ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-calendar"></i> Fecha de Creación</h5>
                            <p class="fs-6">{{ $solicitud->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h5><i class="fas fa-align-left"></i> Descripción</h5>
                        <p class="fs-6">{{ $solicitud->descripcion }}</p>
                    </div>

                    <!-- Datos del ciudadano -->
                    @if($solicitud->datos_personales)
                    <hr>
                    <h5><i class="fas fa-user"></i> Datos del Ciudadano</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre:</strong> 
                                {{ $solicitud->datos_personales['nombre'] ?? '' }}
                                {{ $solicitud->datos_personales['apellido_paterno'] ?? '' }}
                                {{ $solicitud->datos_personales['apellido_materno'] ?? '' }}
                            </p>
                            <p><strong>Fecha de Nacimiento:</strong> {{ $solicitud->datos_personales['fecha_nacimiento'] ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Celular:</strong> {{ $solicitud->datos_personales['celular'] ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $solicitud->datos_personales['email'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Ubicación -->
                    <hr>
                    <h5><i class="fas fa-map-marker-alt"></i> Ubicación</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Colonia:</strong> {{ $solicitud->colonia->nombre ?? 'N/A' }}</p>
                            <p><strong>Dirección:</strong> {{ $solicitud->direccion }}</p>
                            @if($solicitud->datos_personales)
                                <p><strong>Número Exterior:</strong> {{ $solicitud->datos_personales['numero_exterior'] ?? 'N/A' }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($solicitud->datos_personales)
                                <p><strong>Entre Calle:</strong> {{ $solicitud->datos_personales['entre_calle'] ?? 'N/A' }}</p>
                                <p><strong>Y Calle:</strong> {{ $solicitud->datos_personales['y_calle'] ?? 'N/A' }}</p>
                                <p><strong>Referencias:</strong> {{ $solicitud->datos_personales['referencias'] ?? 'N/A' }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Evidencias -->
                    @if($solicitud->evidencias->count() > 0)
                    <hr>
                    <h5><i class="fas fa-images"></i> Evidencias</h5>
                    <div class="row">
                        @foreach($solicitud->evidencias as $evidencia)
                            <div class="col-md-4 mb-3">
                                <img src="{{ asset('storage/' . $evidencia->ruta_archivo) }}" 
                                     class="img-fluid rounded shadow" 
                                     data-bs-toggle="modal" 
                                     data-bs-target="#imagenModal{{ $evidencia->id }}"
                                     style="cursor: pointer;">
                            </div>

                            <!-- Modal para ver imagen completa -->
                            <div class="modal fade" id="imagenModal{{ $evidencia->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <img src="{{ asset('storage/' . $evidencia->ruta_archivo) }}" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

