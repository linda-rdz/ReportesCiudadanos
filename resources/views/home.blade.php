@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="display-4 text-primary">Sistema de Reportes Ciudadanos</h1>
            <p class="lead">¿Cómo te podemos ayudar?</p>
            <p class="text-muted">Selecciona el tipo de problema que quieres reportar</p>
        </div>
    </div>

    <div class="row g-4">
        @foreach($categorias as $categoria)
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm category-card" onclick="selectCategory({{ $categoria->id }})">
                <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                    @if($categoria->imagen)
                        <img src="{{ asset('storage/' . $categoria->imagen) }}" class="img-fluid" style="max-height: 180px; object-fit: contain;" alt="{{ $categoria->nombre }}">
                    @else
                        <div class="text-center">
                            <i class="fas fa-{{ $categoria->icono ?? 'exclamation-triangle' }} fa-4x text-primary mb-3"></i>
                            <h5 class="text-muted">{{ $categoria->nombre }}</h5>
                        </div>
                    @endif
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title text-primary">{{ $categoria->nombre }}</h5>
                    <p class="card-text text-muted">{{ $categoria->descripcion ?? 'Reporta problemas relacionados con ' . strtolower($categoria->nombre) }}</p>
                </div>
                <div class="card-footer bg-transparent text-center">
                    <button class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-plus me-2"></i>Reportar Problema
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row mt-5">
        <div class="col-12 text-center">
            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle me-2"></i>Información</h5>
                <p class="mb-2">Todos los reportes son confidenciales y serán atendidos por las autoridades correspondientes.</p>
                <p class="mb-0">
                    <a href="{{ route('solicitudes.buscar') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-search me-1"></i>¿Ya tienes un folio? Busca tu solicitud aquí
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function selectCategory(categoriaId) {
    window.location.href = `/solicitudes/crear?categoria=${categoriaId}`;
}
</script>

<style>
.category-card {
    cursor: pointer;
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: none;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.card-img-top {
    border-radius: 0.375rem 0.375rem 0 0;
}

.fa-4x {
    font-size: 4rem;
}
</style>

<!-- Modal para mostrar el folio después de crear una solicitud -->
@if(session('folio'))
<div class="modal fade show" id="folioModal" tabindex="-1" aria-labelledby="folioModalLabel" aria-hidden="false" style="display: block;" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="folioModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Solicitud Enviada Correctamente
                </h5>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <i class="fas fa-file-alt fa-4x text-primary mb-3"></i>
                    <h4 class="mb-3">¡Tu solicitud ha sido registrada!</h4>
                    <p class="text-muted mb-4">Guarda tu folio para consultar el estado de tu solicitud en cualquier momento.</p>
                </div>
                
                <div class="alert alert-light border-primary">
                    <p class="mb-2 text-muted"><small>Tu folio de seguimiento es:</small></p>
                    <h2 class="mb-0">
                        <span class="badge bg-primary fs-3 px-4 py-3" style="letter-spacing: 2px; font-family: 'Courier New', monospace;">
                            {{ session('folio') }}
                        </span>
                    </h2>
                </div>

                <div class="mt-4">
                    <a href="{{ route('solicitudes.buscar', ['folio' => session('folio')]) }}" class="btn btn-primary btn-lg me-2">
                        <i class="fas fa-search me-2"></i>Ver Estado de mi Solicitud
                    </a>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" onclick="cerrarModalYRedirigir()">
                    <i class="fas fa-home me-1"></i>Ir al Inicio
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show"></div>
@endif

<script>
function selectCategory(categoriaId) {
    window.location.href = `/solicitudes/crear?categoria=${categoriaId}`;
}

function cerrarModalYRedirigir() {
    // Cerrar el modal
    const modal = document.getElementById('folioModal');
    const backdrop = document.querySelector('.modal-backdrop');
    
    if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
    }
    
    if (backdrop) {
        backdrop.remove();
    }
    
    // Redirigir al inicio sin parámetros para limpiar la sesión
    window.location.href = "{{ route('home') }}";
}

// Cerrar modal con la tecla Escape (opcional)
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('folioModal');
        if (modal && modal.classList.contains('show')) {
            cerrarModalYRedirigir();
        }
    }
});
</script>
@endsection
