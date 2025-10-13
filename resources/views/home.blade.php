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
                <p class="mb-0">Todos los reportes son confidenciales y serán atendidos por las autoridades correspondientes.</p>
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
@endsection
