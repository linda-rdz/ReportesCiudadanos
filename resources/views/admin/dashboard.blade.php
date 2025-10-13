@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('Panel de Administración') }}</h4>
                    <div>
                        <span class="text-muted">{{ __('Bienvenido, ') }} {{ Auth::guard('admin')->user()->name }}</span>
                        <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm ms-2">
                                {{ __('Cerrar Sesión') }}
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total Solicitudes</h5>
                                    <h2>{{ \App\Models\Solicitud::count() }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Pendientes</h5>
                                    <h2>{{ \App\Models\Solicitud::where('estado', 'Pendiente')->count() }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Resueltas</h5>
                                    <h2>{{ \App\Models\Solicitud::where('estado', 'Resuelto')->count() }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Acciones Rápidas</h5>
                                </div>
                                <div class="card-body">
                                    <a href="{{ route('admin.solicitudes.index') }}" class="btn btn-primary">
                                        {{ __('Gestionar Solicitudes') }}
                                    </a>
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
