<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Categoria;
use App\Models\Colonia;
use App\Models\Evidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SolicitudController extends Controller
{
    /**
     * Mostrar listado de solicitudes
     * Redirige a la página de búsqueda por folio para ciudadanos
     */
    public function index(Request $request)
    {
        // Si viene con un folio (después de crear), redirigir a buscar
        if ($request->has('folio')) {
            return redirect()->route('solicitudes.buscar', ['folio' => $request->folio]);
        }
        
        // Para ciudadanos públicos, redirigir a la página de búsqueda
        return redirect()->route('solicitudes.buscar');
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
            // Datos personales
            'nombre' => [
                'required', 'string', 'min:2', 'max:100',
                'regex:/^[\pL\s\'.ÁÉÍÓÚÜÑáéíóúüñ-]+$/u',
                'not_regex:/[0-9]/' // No permitir números
            ],
            'apellido_paterno' => [
                'required', 'string', 'min:2', 'max:100',
                'regex:/^[\pL\s\'.ÁÉÍÓÚÜÑáéíóúüñ-]+$/u',
                'not_regex:/[0-9]/' // No permitir números
            ],
            'apellido_materno' => [
                'nullable', 'string', 'min:2', 'max:100',
                'regex:/^[\pL\s\'.ÁÉÍÓÚÜÑáéíóúüñ-]+$/u',
                'not_regex:/[0-9]/' // No permitir números
            ],
            'fecha_nacimiento' => ['required', 'date_format:d/m/Y', 'before:today', 'after:01/01/1900'],
            'celular' => ['required', 'string', 'regex:/^[0-9]{10}$/'], // Solo números, exactamente 10 dígitos
            'email' => 'nullable|email|max:255',
            
            // Información del reporte
            'descripcion' => 'required|string|max:1000',
            'categoria_id' => 'required|exists:categorias,id',
            
            // Ubicación
            'colonia_id' => 'required|exists:colonias,id',
            'direccion' => 'required|string|max:255',
            'numero_exterior' => 'nullable|string|max:20',
            'entre_calle' => 'required|string|max:255',
            'y_calle' => 'nullable|string|max:255',
            'referencias' => 'nullable|string|max:500',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'evidencias.*' => 'nullable|image|max:10240' // 10MB
        ]);

        $solicitud = Solicitud::create([
            'folio' => Solicitud::generarFolio(),
            'titulo' => $validated['descripcion'], // Usar descripción como título
            'descripcion' => $validated['descripcion'],
            'categoria_id' => $validated['categoria_id'],
            'colonia_id' => $validated['colonia_id'],
            'direccion' => $validated['direccion'],
            'lat' => $validated['lat'] ?? null,
            'lng' => $validated['lng'] ?? null,
            'estado' => 'Pendiente',
            'ciudadano_id' => null, // Sin login, no hay usuario autenticado
            // Guardar datos personales en un campo JSON
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

        return redirect()->route('home')
            ->with('success', 'Solicitud enviada correctamente')
            ->with('folio', $solicitud->folio);
    }

    /**
     * Mostrar solicitud específica
     */
    public function show(Solicitud $solicitud)
    {
        $solicitud->load(['categoria', 'colonia', 'evidencias']);
        
        return view('solicitudes.show', compact('solicitud'));
    }

    /**
     * Buscar solicitud por folio
     */
    public function buscar(Request $request)
    {
        $solicitud = null;
        $folio = $request->input('folio');

        if ($folio) {
            $solicitud = Solicitud::with(['categoria', 'colonia', 'evidencias'])
                ->where('folio', strtoupper(trim($folio)))
                ->first();
        }

        return view('solicitudes.buscar', compact('solicitud', 'folio'));
    }
}
