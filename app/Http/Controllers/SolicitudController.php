<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Categoria;
use App\Models\Colonia;
use App\Models\Evidencia;
use App\Enums\EstadoSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SolicitudController extends Controller
{
    /**
     * Mostrar listado de solicitudes
     */
    public function index(Request $request)
    {
        $query = Solicitud::with(['categoria', 'colonia', 'evidencias']);
        
        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        $solicitudes = $query->latest()->paginate(10);
        
        return view('solicitudes.index', compact('solicitudes'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create(Request $request)
    {
        $categorias = Categoria::orderBy('nombre')->get();
        $colonias = Colonia::orderBy('nombre')->get();
        
        // Si viene una categoría seleccionada, precargarla
        $categoriaSeleccionada = null;
        if ($request->has('categoria')) {
            $categoriaSeleccionada = Categoria::find($request->categoria);
        }
        
        return view('solicitudes.create', compact('categorias', 'colonias', 'categoriaSeleccionada'));
    }

    /**
     * Guardar nueva solicitud
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'required|string|max:1000',
            'categoria_id' => 'required|exists:categorias,id',
            'colonia_id' => 'required|exists:colonias,id',
            'direccion' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'evidencias.*' => 'nullable|image|max:4096'
        ]);

        $solicitud = Solicitud::create([
            ...$validated,
            'estado' => EstadoSolicitud::PENDIENTE,
            'ciudadano_id' => null,
        ]);

        // Guardar evidencias si existen
        if ($request->hasFile('evidencias')) {
            foreach ($request->file('evidencias') as $file) {
                $path = $file->store('evidencias', 'public');
                Evidencia::create([
                    'solicitud_id' => $solicitud->id,
                    'ruta_archivo' => $path,
                ]);
            }
        }

        return redirect()->route('solicitudes.index')
            ->with('success', 'Solicitud enviada correctamente');
    }

    /**
     * Mostrar solicitud específica
     */
    public function show(Solicitud $solicitud)
    {
        // Público: sin verificación de propietario

        $solicitud->load(['categoria', 'colonia', 'evidencias', 'ciudadano', 'funcionario']);
        
        return view('solicitudes.show', compact('solicitud'));
    }

    /**
     * Panel de administración para funcionarios
     */
    public function adminIndex(Request $request)
    {
        $solicitudes = Solicitud::with(['categoria', 'colonia', 'ciudadano', 'evidencias'])
            ->when($request->estado, function($query, $estado) {
                return $query->where('estado', $estado);
            })
            ->latest()
            ->paginate(15);

        return view('admin.solicitudes.index', compact('solicitudes'));
    }

    /**
     * Actualizar estado de solicitud (funcionarios)
     */
    public function updateEstado(Request $request, Solicitud $solicitud)
    {
        // Público: sin autorización de rol

        $request->validate([
            'estado' => 'required|in:Pendiente,En proceso,Resuelto,Rechazado'
        ]);

        $solicitud->update([
            'estado' => $request->estado,
            'funcionario_id' => null,
        ]);

        return back()->with('success', 'Estado actualizado correctamente');
    }

    /**
     * Autorizar que solo funcionarios puedan realizar ciertas acciones
     */
    protected function authorizeFuncionario() {}
}
