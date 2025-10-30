<?php

namespace App\Http\Controllers\Ciudadano;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use App\Models\Categoria;
use App\Models\Colonia;
use App\Models\Evidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CiudadanoSolicitudController extends Controller
{
    /**
     * Mostrar solo las solicitudes del ciudadano autenticado
     */
    public function index(Request $request)
    {
        $query = Solicitud::with(['categoria', 'colonia', 'evidencias'])
            ->where('ciudadano_id', auth()->id());
        
        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        $solicitudes = $query->latest()->paginate(10);
        
        return view('ciudadano.solicitudes.index', compact('solicitudes'));
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
        
        return view('ciudadano.solicitudes.create', compact('categorias', 'colonias', 'categoriaSeleccionada'));
    }

    /**
     * Guardar nueva solicitud
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Datos personales
            'nombre' => [
                'required', 'string', 'min:2', 'max:100',
                'regex:/^[\pL\s\'.ÁÉÍÓÚÜÑáéíóúüñ-]+$/u'
            ],
            'apellido_paterno' => [
                'required', 'string', 'min:2', 'max:100',
                'regex:/^[\pL\s\'.ÁÉÍÓÚÜÑáéíóúüñ-]+$/u'
            ],
            'apellido_materno' => [
                'nullable', 'string', 'min:2', 'max:100',
                'regex:/^[\pL\s\'.ÁÉÍÓÚÜÑáéíóúüñ-]+$/u'
            ],
            'fecha_nacimiento' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'celular' => ['required', 'string', 'regex:/^\+?[0-9\s-]{10,15}$/'],
            'email' => 'nullable|email|max:255',
            
            // Información del reporte
            'descripcion' => 'required|string|max:1000',
            'categoria_id' => 'required|exists:categorias,id',
            
            // Ubicación
            'colonia_id' => 'required|exists:colonias,id',
            'direccion' => 'required|string|max:255',
            'numero_exterior' => 'nullable|string|max:20',
            'entre_calle' => 'required|string|max:255',
            'y_calle' => 'required|string|max:255',
            'referencias' => 'nullable|string|max:500',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'evidencias.*' => 'nullable|image|max:10240' // 10MB
        ]);

        $solicitud = Solicitud::create([
            'titulo' => $validated['descripcion'],
            'descripcion' => $validated['descripcion'],
            'categoria_id' => $validated['categoria_id'],
            'colonia_id' => $validated['colonia_id'],
            'direccion' => $validated['direccion'],
            'lat' => $validated['lat'] ?? null,
            'lng' => $validated['lng'] ?? null,
            'estado' => 'Pendiente',
            'ciudadano_id' => auth()->id(), // Asignar al usuario autenticado
            'datos_personales' => json_encode([
                'nombre' => $validated['nombre'],
                'apellido_paterno' => $validated['apellido_paterno'],
                'apellido_materno' => $validated['apellido_materno'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'celular' => $validated['celular'],
                'email' => $validated['email'] ?? null,
                'numero_exterior' => $validated['numero_exterior'],
                'entre_calle' => $validated['entre_calle'],
                'y_calle' => $validated['y_calle'],
                'referencias' => $validated['referencias'],
            ]),
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

        return redirect()->route('ciudadano.solicitudes.index')
            ->with('success', 'Solicitud enviada correctamente');
    }

    /**
     * Mostrar solicitud específica (solo si pertenece al usuario)
     */
    public function show(Solicitud $solicitud)
    {
        // Verificar que la solicitud pertenece al usuario autenticado
        if ($solicitud->ciudadano_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver esta solicitud');
        }

        $solicitud->load(['categoria', 'colonia', 'evidencias']);
        
        return view('ciudadano.solicitudes.show', compact('solicitud'));
    }
}

