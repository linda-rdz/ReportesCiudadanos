<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use App\Models\Categoria;
use App\Models\Colonia;
use Illuminate\Http\Request;

class AdminSolicitudController extends Controller
{
    /**
     * Mostrar todas las solicitudes (Panel de administrador)
     */
    public function index(Request $request)
    {
        // Verificar que sea administrador
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Acceso denegado. Solo administradores.');
        }
        $query = Solicitud::with(['categoria', 'colonia', 'evidencias']);
        
        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        // Filtro por categoría
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }
        
        // Búsqueda
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->search . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->search . '%');
            });
        }
        
        $solicitudes = $query->latest()->paginate(15);
        $categorias = Categoria::orderBy('nombre')->get();
        
        // Estadísticas
        $estadisticas = [
            'total' => Solicitud::count(),
            'pendientes' => Solicitud::where('estado', 'Pendiente')->count(),
            'en_proceso' => Solicitud::where('estado', 'En proceso')->count(),
            'resueltas' => Solicitud::where('estado', 'Resuelto')->count(),
            'rechazadas' => Solicitud::where('estado', 'Rechazado')->count(),
        ];
        
        return view('admin.solicitudes.index', compact('solicitudes', 'categorias', 'estadisticas'));
    }

    /**
     * Ver detalles de una solicitud
     */
    public function show(Solicitud $solicitud)
    {
        // Verificar que sea administrador
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Acceso denegado. Solo administradores.');
        }
        $solicitud->load(['categoria', 'colonia', 'evidencias']);
        
        return view('admin.solicitudes.show', compact('solicitud'));
    }

    /**
     * Actualizar el estado de una solicitud
     */
    public function updateEstado(Request $request, Solicitud $solicitud)
    {
        // Verificar que sea administrador
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Acceso denegado. Solo administradores.');
        }
        $request->validate([
            'estado' => 'required|in:Pendiente,En proceso,Resuelto,Rechazado'
        ]);

        $solicitud->update([
            'estado' => $request->estado,
            'funcionario_id' => auth()->id()
        ]);

        return back()->with('success', 'Estado actualizado correctamente');
    }
}

