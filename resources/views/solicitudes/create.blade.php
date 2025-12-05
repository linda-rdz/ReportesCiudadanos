@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Reportar Problema Urbano</h4>
                    <button type="button" class="btn btn-outline-dark btn-volver" id="headerBackBtn">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </button>
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

                    <!-- Indicador de pasos -->
                    <div class="step-indicator mb-4">
                        <div class="step active" id="step1-indicator">
                            <span class="step-number">1</span>
                            <span class="step-title">Datos y Reporte</span>
                        </div>
                        <div class="step-line"></div>
                        <div class="step" id="step2-indicator">
                            <span class="step-number">2</span>
                            <span class="step-title">Ubicación y Evidencias</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('solicitudes.store') }}" enctype="multipart/form-data" id="reportForm">
                        @csrf
                        
                        <!-- DATOS PERSONALES  -->
                        <div class="form-step" id="step1">
                            <div class="form-section mb-5">
                                <div class="section-header">
                                    <h5 class="section-title">
                                        <i class="fas fa-user me-2"></i>Datos Personales y Reporte
                                    </h5>
                                    <p class="section-description">Es importante que nos proporciones la siguiente información para contactarte en caso de tener dudas o no encontrar la ubicación del reporte.</p>
                                </div>
                                
                                <div class="section-content">
                                    <!-- Datos Personales -->
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="nombre" class="form-label required">Nombre</label>
                                            <input type="text" 
                                                   class="form-control @error('nombre') is-invalid @enderror" 
                                                   id="nombre" 
                                                   name="nombre" 
                                                   value="{{ old('nombre') }}" 
                                                   placeholder="Ingresa tu nombre"
                                                   minlength="2"
                                                   maxlength="100"
                                                   pattern="^[\p{L}\s'.ÁÉÍÓÚÜÑáéíóúüñ-]+$"
                                                   onkeypress="return soloLetras(event)"
                                                   required>
                                            @error('nombre')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="apellido_paterno" class="form-label required">Apellido Paterno</label>
                                            <input type="text" 
                                                   class="form-control @error('apellido_paterno') is-invalid @enderror" 
                                                   id="apellido_paterno" 
                                                   name="apellido_paterno" 
                                                   value="{{ old('apellido_paterno') }}" 
                                                   placeholder="Ingresa apellido paterno"
                                                   minlength="2"
                                                   maxlength="100"
                                                   pattern="^[\p{L}\s'.ÁÉÍÓÚÜÑáéíóúüñ-]+$"
                                                   onkeypress="return soloLetras(event)"
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
                                                   placeholder="Ingresa apellido materno"
                                                   minlength="2"
                                                   maxlength="100"
                                                   pattern="^[\p{L}\s'.ÁÉÍÓÚÜÑáéíóúüñ-]+$"
                                                   onkeypress="return soloLetras(event)">
                                            @error('apellido_materno')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="fecha_nacimiento" class="form-label required">Fecha de nacimiento</label>
                                            <input type="text" 
                                                   class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
                                                   id="fecha_nacimiento" 
                                                   name="fecha_nacimiento" 
                                                   value="{{ old('fecha_nacimiento') }}" 
                                                   placeholder="DD/MM/AAAA"
                                                   maxlength="10"
                                                   pattern="(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}"
                                                   required>
                                            <small class="form-text text-muted"> </small>
                                            @error('fecha_nacimiento')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="celular" class="form-label required">Celular</label>
                                            <input type="tel" 
                                                   class="form-control @error('celular') is-invalid @enderror" 
                                                   id="celular" 
                                                   name="celular" 
                                                   value="{{ old('celular') }}" 
                                                   placeholder="Ingresa tu celular"
                                                   pattern="^[0-9]{10}$"
                                                   maxlength="10"
                                                   onkeypress="return soloNumeros(event)"
                                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                   required>
                                            <small class="form-text text-muted"></small>
                                            @error('celular')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Información del Reporte -->
                                    <hr class="my-4">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-exclamation-triangle me-2"></i>Información del Reporte
                                    </h6>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="categoria_id" class="form-label required">Asunto</label>
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
                                    </div>

                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label required">Descripción del problema</label>
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

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <div>
                                    <a href="{{ route('home') }}" class="btn btn-outline-secondary me-md-2">
                                        Cancelar
                                    </a>
                                    <button type="button" class="btn btn-primary" id="nextStep">
                                        Siguiente <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!--  UBICACIÓN Y EV -->
                        <div class="form-step" id="step2" style="display: none;">
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
                                            <label for="colonia_id" class="form-label required">Colonia</label>
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
                                            <label for="direccion" class="form-label">Calle</label>
                                            <input type="text" 
                                                   class="form-control @error('direccion') is-invalid @enderror" 
                                                   id="direccion" 
                                                   name="direccion" 
                                                   value="{{ old('direccion') }}" 
                                                   placeholder="Nombre de la calle">
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
                                            <label for="entre_calle" class="form-label">Entre calle</label>
                                            <input type="text" 
                                                   class="form-control @error('entre_calle') is-invalid @enderror" 
                                                   id="entre_calle" 
                                                   name="entre_calle" 
                                                   value="{{ old('entre_calle') }}" 
                                                   placeholder="Primera calle de referencia">
                                            @error('entre_calle')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="y_calle" class="form-label">Y calle (opcional)</label>
                                            <input type="text" 
                                                   class="form-control @error('y_calle') is-invalid @enderror" 
                                                   id="y_calle" 
                                                   name="y_calle" 
                                                   value="{{ old('y_calle') }}" 
                                                   placeholder="Segunda calle de referencia">
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

                            <!-- EVIDENCIAS -->
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
                                        <div class="form-text">Puedes subir múltiples imágenes. Máximo 10MB por imagen.</div>
                                        @error('evidencias.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <div>
                                    <a href="{{ route('home') }}" class="btn btn-outline-secondary me-md-2">
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-1"></i>Enviar Solicitud
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nextBtn = document.getElementById('nextStep');
    const prevBtns = document.querySelectorAll('[data-action="prev-step"]');
    const headerBackBtn = document.getElementById('headerBackBtn');
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step1Indicator = document.getElementById('step1-indicator');
    const step2Indicator = document.getElementById('step2-indicator');
    const fechaInput = document.getElementById('fecha_nacimiento');
    
    // Función para permitir solo letras (incluyendo acentos y espacios)
    window.soloLetras = function(event) {
        const char = String.fromCharCode(event.which || event.keyCode);
        const regex = /^[\p{L}\s'.ÁÉÍÓÚÜÑáéíóúüñ-]+$/u;
        // Permitir teclas especiales: Backspace, Delete, Tab, Enter, etc.
        if (event.keyCode === 8 || event.keyCode === 46 || event.keyCode === 9 || event.keyCode === 27 || 
            event.keyCode === 13 || (event.keyCode === 65 && event.ctrlKey === true) || 
            (event.keyCode >= 35 && event.keyCode <= 40)) {
            return true;
        }
        // Permitir espacios y caracteres especiales permitidos
        if (char === ' ' || char === '.' || char === "'" || char === '-') {
            return true;
        }
        // Verificar si es una letra (incluyendo acentos)
        if (!regex.test(char)) {
            event.preventDefault();
            return false;
        }
        return true;
    };

    // Función para permitir solo números
    window.soloNumeros = function(event) {
        const char = String.fromCharCode(event.which || event.keyCode);
        // Permitir teclas especiales: Backspace, Delete, Tab, Enter, etc.
        if (event.keyCode === 8 || event.keyCode === 46 || event.keyCode === 9 || event.keyCode === 27 || 
            event.keyCode === 13 || (event.keyCode === 65 && event.ctrlKey === true) || 
            (event.keyCode >= 35 && event.keyCode <= 40)) {
            return true;
        }
        // Solo permitir números
        if (char < '0' || char > '9') {
            event.preventDefault();
            return false;
        }
        return true;
    };

    // Validación adicional para el campo celular
    const celularInput = document.getElementById('celular');
    if (celularInput) {
        celularInput.addEventListener('input', function(e) {
            // Eliminar cualquier carácter que no sea número
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            // Limitar a 10 dígitos
            if (e.target.value.length > 10) {
                e.target.value = e.target.value.slice(0, 10);
            }
        });

        celularInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            const numbersOnly = pastedText.replace(/[^0-9]/g, '').slice(0, 10);
            e.target.value = numbersOnly;
        });
    }
    
    // Función para regresar al paso anterior
    function goToPreviousStep() {
        // Verificar si el paso 2 está visible usando getComputedStyle para mayor confiabilidad
        const step2Display = window.getComputedStyle(step2).display;
        const step2Visible = step2Display !== 'none';
        
        if (step2Visible) {
            // Si estamos en el paso 2, regresar al paso 1
            step2.style.display = 'none';
            step1.style.display = 'block';
            step2Indicator.classList.remove('active');
            step1Indicator.classList.add('active');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            // Si estamos en el paso 1, ir a home
            window.location.href = "{{ route('home') }}";
        }
    }
    
    // Manejar el botón de volver del header
    headerBackBtn.addEventListener('click', function(e) {
        e.preventDefault();
        goToPreviousStep();
    });

    // Formatear y validar fecha de nacimiento (DD/MM/YYYY)
    if (fechaInput) {
        fechaInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Solo números
            
            // Limitar a 8 dígitos (sin contar las barras)
            if (value.length > 8) {
                value = value.slice(0, 8);
            }
            
            // Formatear automáticamente con barras
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2);
            }
            if (value.length >= 5) {
                value = value.slice(0, 5) + '/' + value.slice(5, 9);
            }
            
            e.target.value = value;
            
            // Validar fecha cuando se complete
            if (value.length === 10) {
                validarFecha(e.target);
            }
        });

        fechaInput.addEventListener('blur', function(e) {
            validarFecha(e.target);
        });
    }

    function validarFecha(input) {
        const fechaStr = input.value;
        const fechaRegex = /^(\d{2})\/(\d{2})\/(\d{4})$/;
        const match = fechaStr.match(fechaRegex);
        
        if (!match) {
            input.setCustomValidity('Formato inválido. Use DD/MM/YYYY');
            input.classList.add('is-invalid');
            return false;
        }
        
        const dia = parseInt(match[1], 10);
        const mes = parseInt(match[2], 10);
        const anio = parseInt(match[3], 10);
        const hoy = new Date();
        const fechaMax = new Date(hoy.getFullYear() - 1, hoy.getMonth(), hoy.getDate());
        const fechaMin = new Date(1900, 0, 1);
        const fechaIngresada = new Date(anio, mes - 1, dia);
        
        // Validar rango de día
        if (dia < 1 || dia > 31) {
            input.setCustomValidity('El día debe estar entre 01 y 31');
            input.classList.add('is-invalid');
            return false;
        }
        
        // Validar rango de mes
        if (mes < 1 || mes > 12) {
            input.setCustomValidity('El mes debe estar entre 01 y 12');
            input.classList.add('is-invalid');
            return false;
        }
        
        // Validar si la fecha es válida (ej: no permitir 31/02/2003)
        if (fechaIngresada.getDate() !== dia || fechaIngresada.getMonth() !== (mes - 1) || fechaIngresada.getFullYear() !== anio) {
            input.setCustomValidity('Fecha inválida. Verifique día, mes y año');
            input.classList.add('is-invalid');
            return false;
        }
        
        // Validar que la fecha esté en el rango permitido (antes de hoy, después de 1900)
        if (fechaIngresada > fechaMax || fechaIngresada < fechaMin) {
            input.setCustomValidity('La fecha debe ser anterior a hoy y posterior a 01/01/1900');
            input.classList.add('is-invalid');
            return false;
        }
        
        input.setCustomValidity('');
        input.classList.remove('is-invalid');
        return true;
    }

    nextBtn.addEventListener('click', function() {
        // Validar campos del paso 1
        const requiredFields = step1.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Validar fecha de nacimiento específicamente
        if (fechaInput && fechaInput.value.length === 10) {
            if (!validarFecha(fechaInput)) {
                isValid = false;
                alert('Por favor ingrese una fecha de nacimiento válida en formato DD/MM/YYYY');
            }
        } else if (fechaInput && fechaInput.value.trim() !== '') {
            fechaInput.classList.add('is-invalid');
            isValid = false;
            alert('La fecha de nacimiento debe tener el formato DD/MM/YYYY (ejemplo: 06/01/2003)');
        }

        if (isValid) {
            step1.style.display = 'none';
            step2.style.display = 'block';
            step1Indicator.classList.remove('active');
            step2Indicator.classList.add('active');
            // Hacer scroll hacia arriba para mostrar el inicio del paso 2 (Ubicación del Reporte)
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            alert('Por favor completa todos los campos requeridos correctamente antes de continuar.');
        }
    });

    prevBtns.forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault();
            goToPreviousStep();
        });
    });
});
</script>

<style>
/* Asegurar que solo haya un asterisco en campos requeridos */
.form-label.required::after {
    content: ' *';
    color: #dc3545;
    font-weight: bold;
}

/* Eliminar asteriscos duplicados si existen en el texto */
.form-label.required {
    position: relative;
}

</style>
<style>
.btn-volver { border-width: 2px; font-weight: 600; padding: 0.5rem 1rem; }
.btn-volver i { font-size: 0.95rem; }
</style>
@endsection

