@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Recuperar contraseña</h4>
                </div>
                <div class="card-body p-4">
                    @if(session('status'))
                        <div class="d-none" id="reset-status">{{ session('status') }}</div>
                    @endif
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Enviar enlace de recuperación</button>
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    <!-- Modal de confirmación de envío -->
    <div class="modal fade" id="resetSentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-envelope"></i> Enlace enviado</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Hemos enviado un enlace a tu correo para cambiar la contraseña.</p>
                    <p class="mb-0"><small>Si no lo ves, revisa la bandeja de spam.</small></p>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('login') }}" class="btn btn-primary">Ir al inicio de sesión</a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function(){
        var statusEl = document.getElementById('reset-status');
        if (statusEl && statusEl.textContent.trim().length > 0) {
            var modalEl = document.getElementById('resetSentModal');
            if (modalEl) {
                var m = new bootstrap.Modal(modalEl);
                m.show();
            }
        }
    })();
    </script>
@endsection
