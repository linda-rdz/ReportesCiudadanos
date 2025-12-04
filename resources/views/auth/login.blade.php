@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <img src="https://www.miguelaleman.gob.mx/wp-content/uploads/2024/11/logo-municipio-slogan-bw-1.png" alt="Logo" class="login-logo mb-2">
                    <h4 class="mb-0">Iniciar Sesión</h4>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="numero_empleado" class="form-label">Número de empleado</label>
                            <input type="text" 
                                   class="form-control @error('numero_empleado') is-invalid @enderror" 
                                   id="numero_empleado" 
                                   name="numero_empleado" 
                                   placeholder="Ej. I25"
                                   value="{{ old('numero_empleado') }}" 
                                   required 
                                   autofocus>
                            @error('numero_empleado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Contraseña"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Recordarme
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Ingresar</button>
                        </div>
                    </form>
                    
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="mb-2">Acceso exclusivo para administradores.</p>
                        <p class="mb-2"><a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a></p>
                        <p class="mb-0">¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

