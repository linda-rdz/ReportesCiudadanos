@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Solicitudes</h4>
                    <a href="{{ route('solicitudes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Solicitud
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filtros -->
                    <form class="row g-3 mb-4" method="GET">
                        <div class="col-md-3">
                            <select name="estado" class="form-select" onchange="this.form.submit()">
                                <option value="">Todos los estados</option>
                                @foreach(['Pendiente','En proceso','Resuelto','Rechazado'] as $estado)
                                    <option value="{{ $estado }}" @selected(request('estado') === $estado)>
                                        {{ $estado }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    <!-- Lista de solicitudes -->
                    @forelse($solicitudes as $solicitud)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="card-title">{{ $solicitud->titulo }}</h5>
                                        <p class="card-text">{{ Str::limit($solicitud->descripcion, 150) }}</p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <strong>Categoría:</strong> {{ $solicitud->categoria->nombre ?? 'N/A' }}
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <strong>Colonia:</strong> {{ $solicitud->colonia->nombre ?? 'N/A' }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <strong>Fecha:</strong> {{ $solicitud->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <span class="badge 
                                                    @if($solicitud->estado === 'Pendiente') bg-warning
                                                    @elseif($solicitud->estado === 'En proceso') bg-info
                                                    @elseif($solicitud->estado === 'Resuelto') bg-success
                                                    @else bg-danger
                                                    @endif">
                                                    {{ $solicitud->estado }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        @if($solicitud->evidencias->count() > 0)
                                            <div class="row">
                                                @foreach($solicitud->evidencias->take(2) as $evidencia)
                                                    <div class="col-6 mb-2">
                                                        <img src="{{ asset('storage/' . $evidencia->ruta_archivo) }}" 
                                                             class="img-fluid rounded" 
                                                             style="max-height: 80px; object-fit: cover;">
                                                    </div>
                                                @endforeach
                                            </div>
                                            @if($solicitud->evidencias->count() > 2)
                                                <small class="text-muted">+{{ $solicitud->evidencias->count() - 2 }} más</small>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('solicitudes.show', $solicitud) }}" class="btn btn-outline-primary btn-sm">
                                        Ver Detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info text-center">
                            <h5>No hay solicitudes</h5>
                            <p>No se encontraron solicitudes con los filtros aplicados.</p>
                            <a href="{{ route('solicitudes.create') }}" class="btn btn-primary">
                                Crear Primera Solicitud
                            </a>
                        </div>
                    @endforelse

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center">
                        {{ $solicitudes->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
