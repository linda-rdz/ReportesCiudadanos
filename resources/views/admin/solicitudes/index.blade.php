@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Panel de Administración - Solicitudes</h4>
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

                    <!-- Tabla de solicitudes -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Ciudadano</th>
                                    <th>Categoría</th>
                                    <th>Colonia</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($solicitudes as $solicitud)
                                    <tr>
                                        <td>{{ $solicitud->id }}</td>
                                        <td>
                                            <strong>{{ Str::limit($solicitud->titulo, 30) }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($solicitud->descripcion, 50) }}</small>
                                        </td>
                                        <td>Público</td>
                                        <td>{{ $solicitud->categoria->nombre ?? 'N/A' }}</td>
                                        <td>{{ $solicitud->colonia->nombre ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($solicitud->estado === 'Pendiente') bg-warning
                                                @elseif($solicitud->estado === 'En proceso') bg-info
                                                @elseif($solicitud->estado === 'Resuelto') bg-success
                                                @else bg-danger
                                                @endif">
                                                {{ $solicitud->estado }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $solicitud->created_at->format('d/m/Y') }}</small>
                                            <br>
                                            <small class="text-muted">{{ $solicitud->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('solicitudes.show', $solicitud) }}" 
                                                   class="btn btn-outline-primary btn-sm" 
                                                   title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <!-- Formulario para cambiar estado -->
                                                <form action="{{ route('admin.solicitudes.updateEstado', $solicitud) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="estado" 
                                                            class="form-select form-select-sm" 
                                                            style="width: auto; display: inline-block;"
                                                            onchange="this.form.submit()">
                                                        @foreach(['Pendiente','En proceso','Resuelto','Rechazado'] as $estado)
                                                            <option value="{{ $estado }}" 
                                                                    @selected($solicitud->estado === $estado)>
                                                                {{ $estado }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="alert alert-info">
                                                <h5>No hay solicitudes</h5>
                                                <p>No se encontraron solicitudes con los filtros aplicados.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $solicitudes->withQueryString()->links() }}
                    </div>

                    <!-- Estadísticas rápidas -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $solicitudes->where('estado', 'Pendiente')->count() }}</h5>
                                    <small>Pendientes</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $solicitudes->where('estado', 'En proceso')->count() }}</h5>
                                    <small>En Proceso</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $solicitudes->where('estado', 'Resuelto')->count() }}</h5>
                                    <small>Resueltas</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $solicitudes->where('estado', 'Rechazado')->count() }}</h5>
                                    <small>Rechazadas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
