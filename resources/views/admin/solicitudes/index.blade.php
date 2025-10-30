@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-tasks"></i> Panel de Administración de Solicitudes</h2>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </button>
                </form>
            </div>

            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">Total</h5>
                            <h2>{{ $estadisticas['total'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">Pendientes</h5>
                            <h2>{{ $estadisticas['pendientes'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">En Proceso</h5>
                            <h2>{{ $estadisticas['en_proceso'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Resueltas</h5>
                            <h2>{{ $estadisticas['resueltas'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros y búsqueda -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select" onchange="this.form.submit()">
                                <option value="">Todos los estados</option>
                                @foreach(['Pendiente','En proceso','Resuelto','Rechazado'] as $estado)
                                    <option value="{{ $estado }}" @selected(request('estado') === $estado)>
                                        {{ $estado }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Categoría</label>
                            <select name="categoria_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Todas las categorías</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" @selected(request('categoria_id') == $categoria->id)>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Buscar</label>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Buscar por título o descripción..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                @if(request()->hasAny(['estado', 'categoria_id', 'search']))
                                    <a href="{{ route('admin.solicitudes.index') }}" class="btn btn-secondary">Limpiar</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Mensajes -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Lista de solicitudes -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Categoría</th>
                                    <th>Descripción</th>
                                    <th>Colonia</th>
                                    <th>Ciudadano</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($solicitudes as $solicitud)
                                    <tr>
                                        <td>{{ $solicitud->id }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $solicitud->categoria->nombre ?? 'N/A' }}</span>
                                        </td>
                                        <td>{{ Str::limit($solicitud->descripcion, 50) }}</td>
                                        <td>{{ $solicitud->colonia->nombre ?? 'N/A' }}</td>
                                        <td>
                                            @if($solicitud->datos_personales)
                                                {{ $solicitud->datos_personales['nombre'] ?? 'N/A' }}
                                                {{ $solicitud->datos_personales['apellido_paterno'] ?? '' }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <form action="{{ route('admin.solicitudes.updateEstado', $solicitud) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="estado" class="form-select form-select-sm
                                                    @if($solicitud->estado === 'Pendiente') bg-warning
                                                    @elseif($solicitud->estado === 'En proceso') bg-info text-white
                                                    @elseif($solicitud->estado === 'Resuelto') bg-success text-white
                                                    @else bg-danger text-white
                                                    @endif" 
                                                    onchange="this.form.submit()">
                                                    @foreach(['Pendiente','En proceso','Resuelto','Rechazado'] as $estado)
                                                        <option value="{{ $estado }}" @selected($solicitud->estado === $estado)>
                                                            {{ $estado }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.solicitudes.show', $solicitud) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <p class="text-muted mb-0">No se encontraron solicitudes</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $solicitudes->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
