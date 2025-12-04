<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use App\Models\Mensaje;
use App\Models\Categoria;
use App\Models\Colonia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSolicitudController extends Controller
{
    /**
     * Mostrar todas las solicitudes (Panel de administrador)
     */
    public function index(Request $request)
    {
        // Verificar que sea administrador
        if (!auth()->check() || (method_exists(auth()->user(), 'isAdmin') ? !auth()->user()->isAdmin() : (auth()->user()->rol ?? auth()->user()->role ?? null) !== 'admin')) {
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
        if (!auth()->check() || (method_exists(auth()->user(), 'isAdmin') ? !auth()->user()->isAdmin() : (auth()->user()->rol ?? auth()->user()->role ?? null) !== 'admin')) {
            return redirect()->route('login')->with('error', 'Acceso denegado. Solo administradores.');
        }
        $solicitud->load(['categoria', 'colonia', 'evidencias', 'mensajes.admin']);
        
        return view('admin.solicitudes.show', compact('solicitud'));
    }

    /**
     * Eliminar una solicitud y sus recursos asociados
     */
    public function destroy(Solicitud $solicitud)
    {
        if (!auth()->check() || (method_exists(auth()->user(), 'isAdmin') ? !auth()->user()->isAdmin() : (auth()->user()->rol ?? null) !== 'admin')) {
            return redirect()->route('login')->with('error', 'Acceso denegado. Solo administradores.');
        }

        $solicitud->load('evidencias', 'mensajes');

        foreach ($solicitud->evidencias as $evidencia) {
            $path = $evidencia->ruta_archivo;
            $thumb = preg_replace('#^evidencias/#', 'evidencias/thumbs/', $path);
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            if ($thumb) {
                Storage::disk('public')->delete($thumb);
            }
        }

        $solicitud->mensajes()->delete();
        $solicitud->evidencias()->delete();
        $solicitud->delete();

        return back()->with('success', 'Solicitud eliminada correctamente');
    }

    /**
     * Actualizar el estado de una solicitud
     */
    public function updateEstado(Request $request, Solicitud $solicitud)
    {
        // Verificar que sea administrador
        if (!auth()->check() || (method_exists(auth()->user(), 'isAdmin') ? !auth()->user()->isAdmin() : (auth()->user()->rol ?? null) !== 'admin')) {
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

    public function storeMensaje(Request $request, Solicitud $solicitud)
    {
        if (!auth()->check() || (method_exists(auth()->user(), 'isAdmin') ? !auth()->user()->isAdmin() : (auth()->user()->rol ?? null) !== 'admin')) {
            return redirect()->route('login')->with('error', 'Acceso denegado. Solo administradores.');
        }
        $validated = $request->validate([
            'contenido' => 'required|string|max:2000',
            'tipo' => 'nullable|string|max:50'
        ]);

        Mensaje::create([
            'solicitud_id' => $solicitud->id,
            'user_id' => auth()->id(),
            'contenido' => $validated['contenido'],
            'tipo' => $validated['tipo'] ?? 'estado',
        ]);

        return back()->with('success', 'Mensaje enviado al ciudadano');
    }
}

