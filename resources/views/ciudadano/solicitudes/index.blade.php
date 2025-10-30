@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-list"></i> Mis Solicitudes</h2>
                <div>
                    <a href="{{ route('ciudadano.solicitudes.create') }}" class="btn btn-primary me-2">
                        <i class="fas fa-plus"></i> Nueva Solicitud
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-body">
                    <form class="row g-3" method="GET">
                        <div class="col-md-4">
                            <label class="form-label">Filtrar por estado</label>
                            <select name="estado" class="form-select" onchange="this.form.submit()">
                                <option value="">Todos los estados</option>
                                @foreach(['Pendiente','En proceso','Resuelto','Rechazado'] as $estado)
                                    <option value="{{ $estado }}" @selected(request('estado') === $estado)>
                                        {{ $estado }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if(request('estado'))
                            <div class="col-md-2 d-flex align-items-end">
                                <a href="{{ route('ciudadano.solicitudes.index') }}" class="btn btn-secondary">Limpiar</a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Mensajes -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Lista de solicitudes -->
            @forelse($solicitudes as $solicitud)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="card-title">{{ $solicitud->titulo }}</h5>
                                        <span class="badge 
                                            @if($solicitud->estado === 'Pendiente') bg-warning text-dark
                                            @elseif($solicitud->estado === 'En proceso') bg-info
                                            @elseif($solicitud->estado === 'Resuelto') bg-success
                                            @else bg-danger
                                            @endif mb-2">
                                            {{ $solicitud->estado }}
                                        </span>
                                    </div>
                                </div>
                                <p class="card-text text-muted">{{ Str::limit($solicitud->descripcion, 150) }}</p>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <i class="fas fa-tag"></i> <strong>Categoría:</strong> {{ $solicitud->categoria->nombre ?? 'N/A' }}
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt"></i> <strong>Colonia:</strong> {{ $solicitud->colonia->nombre ?? 'N/A' }}
                                        </small>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i> <strong>Fecha:</strong> {{ $solicitud->created_at->format('d/m/Y H:i') }}
                                        </small>
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
                                                     style="max-height: 100px; object-fit: cover; width: 100%;">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($solicitud->evidencias->count() > 2)
                                        <small class="text-muted"><i class="fas fa-images"></i> +{{ $solicitud->evidencias->count() - 2 }} más</small>
                                    @endif
                                @else
                                    <p class="text-muted text-center"><i class="fas fa-image"></i> Sin evidencias</p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('ciudadano.solicitudes.show', $solicitud) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No tienes solicitudes</h5>
                        <p class="text-muted">Aún no has creado ninguna solicitud. Comienza creando tu primera solicitud.</p>
                        <a href="{{ route('ciudadano.solicitudes.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Crear Primera Solicitud
                        </a>
                    </div>
                </div>
            @endforelse

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $solicitudes->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

