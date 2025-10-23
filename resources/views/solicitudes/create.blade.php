@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Reportar Problema Urbano</h4>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6>Por favor corrige los siguientes errores:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('solicitudes.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- SECCIÓN 1: DATOS PERSONALES -->
                        <div class="form-section mb-5">
                            <div class="section-header">
                                <h5 class="section-title">
                                    <i class="fas fa-user me-2"></i>Datos Personales
                                </h5>
                                <p class="section-description">Es importante que nos proporciones la siguiente información para contactarte en caso de tener dudas o no encontrar la ubicación del reporte.</p>
                            </div>
                            
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="nombre" class="form-label">Nombre *</label>
                                        <input type="text" 
                                               class="form-control @error('nombre') is-invalid @enderror" 
                                               id="nombre" 
                                               name="nombre" 
                                               value="{{ old('nombre') }}" 
                                               placeholder="Tu nombre"
                                               required>
                                        @error('nombre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="apellido_paterno" class="form-label">Apellido Paterno *</label>
                                        <input type="text" 
                                               class="form-control @error('apellido_paterno') is-invalid @enderror" 
                                               id="apellido_paterno" 
                                               name="apellido_paterno" 
                                               value="{{ old('apellido_paterno') }}" 
                                               placeholder="Apellido paterno"
                                               required>
                                        @error('apellido_paterno')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="apellido_materno" class="form-label">Apellido Materno</label>
                                        <input type="text" 
                                               class="form-control @error('apellido_materno') is-invalid @enderror" 
                                               id="apellido_materno" 
                                               name="apellido_materno" 
                                               value="{{ old('apellido_materno') }}" 
                                               placeholder="Apellido materno (opcional)">
                                        @error('apellido_materno')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento (DD/MM/AAAA) *</label>
                                        <input type="date" 
                                               class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
                                               id="fecha_nacimiento" 
                                               name="fecha_nacimiento" 
                                               value="{{ old('fecha_nacimiento') }}" 
                                               required>
                                        @error('fecha_nacimiento')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="celular" class="form-label">Celular *</label>
                                        <input type="tel" 
                                               class="form-control @error('celular') is-invalid @enderror" 
                                               id="celular" 
                                               name="celular" 
                                               value="{{ old('celular') }}" 
                                               placeholder="Número de celular"
                                               required>
                                        @error('celular')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="email" class="form-label">Correo electrónico</label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="correo@ejemplo.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 2: INFORMACIÓN DEL REPORTE -->
                        <div class="form-section mb-5">
                            <div class="section-header">
                                <h5 class="section-title">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Información del Reporte
                                </h5>
                                <p class="section-description">Describe el problema que has observado y proporciona la ubicación exacta.</p>
                            </div>
                            
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="categoria_id" class="form-label">Asunto *</label>
                                        <select class="form-select @error('categoria_id') is-invalid @enderror" 
                                                id="categoria_id" 
                                                name="categoria_id" 
                                                required>
                                            <option value="">Seleccione un asunto...</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{ $categoria->id }}" 
                                                        @selected((old('categoria_id') == $categoria->id) || (isset($categoriaSeleccionada) && $categoriaSeleccionada && $categoriaSeleccionada->id == $categoria->id))>
                                                    {{ $categoria->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('categoria_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="titulo" class="form-label">Título del problema *</label>
                                        <input type="text" 
                                               class="form-control @error('titulo') is-invalid @enderror" 
                                               id="titulo" 
                                               name="titulo" 
                                               value="{{ old('titulo') }}" 
                                               placeholder="Ej: Bache en la calle principal"
                                               required>
                                        @error('titulo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Solicitud *</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                              id="descripcion" 
                                              name="descripcion" 
                                              rows="4" 
                                              placeholder="Describe detalladamente el problema que has observado..."
                                              required>{{ old('descripcion') }}</textarea>
                                    @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 3: UBICACIÓN DEL REPORTE -->
                        <div class="form-section mb-5">
                            <div class="section-header">
                                <h5 class="section-title">
                                    <i class="fas fa-map-marker-alt me-2"></i>Ubicación del Reporte
                                </h5>
                                <p class="section-description">Proporciona la ubicación exacta donde se encuentra el problema.</p>
                            </div>
                            
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="colonia_id" class="form-label">Colonia *</label>
                                        <select class="form-select @error('colonia_id') is-invalid @enderror" 
                                                id="colonia_id" 
                                                name="colonia_id" 
                                                required>
                                            <option value="">Selecciona una colonia...</option>
                                            @foreach($colonias as $colonia)
                                                <option value="{{ $colonia->id }}" 
                                                        @selected(old('colonia_id') == $colonia->id)>
                                                    {{ $colonia->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('colonia_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="direccion" class="form-label">Calle *</label>
                                        <input type="text" 
                                               class="form-control @error('direccion') is-invalid @enderror" 
                                               id="direccion" 
                                               name="direccion" 
                                               value="{{ old('direccion') }}" 
                                               placeholder="Nombre de la calle"
                                               required>
                                        @error('direccion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="numero_exterior" class="form-label">Número exterior</label>
                                        <input type="text" 
                                               class="form-control @error('numero_exterior') is-invalid @enderror" 
                                               id="numero_exterior" 
                                               name="numero_exterior" 
                                               value="{{ old('numero_exterior') }}" 
                                               placeholder="Número exterior">
                                        @error('numero_exterior')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="entre_calle" class="form-label">Entre calle *</label>
                                        <input type="text" 
                                               class="form-control @error('entre_calle') is-invalid @enderror" 
                                               id="entre_calle" 
                                               name="entre_calle" 
                                               value="{{ old('entre_calle') }}" 
                                               placeholder="Primera calle de referencia"
                                               required>
                                        @error('entre_calle')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="y_calle" class="form-label">Y calle *</label>
                                        <input type="text" 
                                               class="form-control @error('y_calle') is-invalid @enderror" 
                                               id="y_calle" 
                                               name="y_calle" 
                                               value="{{ old('y_calle') }}" 
                                               placeholder="Segunda calle de referencia"
                                               required>
                                        @error('y_calle')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="referencias" class="form-label">Referencias</label>
                                    <textarea class="form-control @error('referencias') is-invalid @enderror" 
                                              id="referencias" 
                                              name="referencias" 
                                              rows="2" 
                                              placeholder="Referencias adicionales para ubicar el problema (opcional)">{{ old('referencias') }}</textarea>
                                    @error('referencias')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 4: EVIDENCIAS -->
                        <div class="form-section mb-4">
                            <div class="section-header">
                                <h5 class="section-title">
                                    <i class="fas fa-camera me-2"></i>Evidencias
                                </h5>
                                <p class="section-description">Sube fotografías que ayuden a identificar el problema.</p>
                            </div>
                            
                            <div class="section-content">
                                <div class="mb-3">
                                    <label for="evidencias" class="form-label">Evidencias (imágenes)</label>
                                    <input type="file" 
                                           class="form-control @error('evidencias.*') is-invalid @enderror" 
                                           id="evidencias" 
                                           name="evidencias[]" 
                                           multiple 
                                           accept="image/*">
                                    <div class="form-text">Puedes subir múltiples imágenes. Máximo 4MB por imagen.</div>
                                    @error('evidencias.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary me-md-2">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i>Enviar Solicitud
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
