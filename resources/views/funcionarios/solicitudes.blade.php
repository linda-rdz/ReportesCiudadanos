@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-shield-alt"></i> Panel de Funcionarios</h2>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-home"></i> Inicio
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select" onchange="this.form.submit()">
                                <option value="">Todos los estados</option>
                                @foreach(['Pendiente','En proceso','Resuelto','Rechazado'] as $estado)
                                    <option value="{{ $estado }}" @selected(request('estado') === $estado)>{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Buscar por nombre de ciudadano</label>
                            <input type="text" name="ciudadano" value="{{ request('ciudadano') }}" class="form-control" placeholder="Ej. Juan" />
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary me-2" type="submit"><i class="fas fa-search"></i> Buscar</button>
                            <a class="btn btn-secondary" href="{{ route('funcionarios.solicitudes.index') }}">Limpiar</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ciudadano</th>
                                    <th>Categoría</th>
                                    <th>Descripción</th>
                                    <th>Colonia</th>
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
                                            @php $dp = $solicitud->datos_personales ?? []; @endphp
                                            {{ $dp['nombre'] ?? 'N/A' }} {{ $dp['apellido_paterno'] ?? '' }}
                                            <div class="text-muted small">{{ $dp['celular'] ?? '' }} {{ $dp['email'] ?? '' }}</div>
                                        </td>
                                        <td>{{ $solicitud->categoria->nombre ?? 'N/A' }}</td>
                                        <td>{{ Str::limit($solicitud->descripcion, 60) }}</td>
                                        <td>{{ $solicitud->colonia->nombre ?? 'N/A' }}</td>
                                        <td>{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <form action="{{ route('funcionarios.solicitudes.updateEstado', $solicitud) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    @foreach(['Pendiente','En proceso','Resuelto','Rechazado'] as $estado)
                                                        <option value="{{ $estado }}" @selected($solicitud->estado === $estado)>{{ $estado }}</option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <a href="{{ route('solicitudes.show', $solicitud) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">No hay solicitudes</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $solicitudes->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


