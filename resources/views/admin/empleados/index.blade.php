@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-cog"></i> Gestión de Empleados</h2>
        <a href="{{ route('admin.empleados.create') }}" class="btn btn-primary"><i class="fas fa-user-plus"></i> Nuevo</a>
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
                <div class="col-md-6">
                    <label class="form-label">Buscar</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Nombre, número o rol" value="{{ $search }}">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Buscar</button>
                        @if($search)
                            <a href="{{ route('admin.empleados.index') }}" class="btn btn-secondary">Limpiar</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Número</th>
                            <th>Nombre</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($empleados as $empleado)
                            <tr>
                                <td>{{ $empleado->id }}</td>
                                <td>{{ $empleado->numero_empleado }}</td>
                                <td>{{ $empleado->nombre }}</td>
                                <td><span class="badge bg-info text-dark">{{ $empleado->rol }}</span></td>
                                <td>
                                    @if($empleado->estado === 'activo')
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('admin.empleados.edit', $empleado) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Editar</a>
                                    <form action="{{ route('admin.empleados.destroy', $empleado) }}" method="POST" onsubmit="return confirm('¿Eliminar empleado?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Sin empleados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $empleados->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

