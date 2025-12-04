<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Categoria;
use App\Models\Colonia;
use App\Models\Evidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        Categoria::firstOrCreate(['nombre' => 'OTRO']);
        $categorias = Categoria::orderByRaw("CASE WHEN nombre = 'OTRO' THEN 1 ELSE 0 END, nombre")->get();
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
            'direccion' => 'nullable|string|max:255',
            'numero_exterior' => 'nullable|string|max:20',
            'entre_calle' => 'nullable|string|max:255',
            'y_calle' => 'nullable|string|max:255',
            'referencias' => 'nullable|string|max:500',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'evidencias.*' => 'nullable|image|max:10240'
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
            // Guardar datos personales como array (Laravel los serializa a JSON por el cast)
            'datos_personales' => [
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
            ],
        ]);

        // Guardar evidencias si existen
        if ($request->hasFile('evidencias')) {
            foreach ($request->file('evidencias') as $file) {
                if (!$file->isValid()) {
                    continue;
                }
                $data = @file_get_contents($file->getRealPath());
                if (!\function_exists('imagecreatefromstring')) {
                    $path = $file->store('evidencias', 'public');
                    Evidencia::create([
                        'solicitud_id' => $solicitud->id,
                        'ruta_archivo' => $path,
                    ]);
                    continue;
                }
                $img = @\imagecreatefromstring($data);
                if ($img === false) {
                    $path = $file->store('evidencias', 'public');
                    Evidencia::create([
                        'solicitud_id' => $solicitud->id,
                        'ruta_archivo' => $path,
                    ]);
                    continue;
                }
                $uuid = Str::uuid()->toString();
                $mainPath = 'evidencias/' . $uuid . '.jpg';
                ob_start();
                \imagejpeg($img, null, 85);
                $jpeg = ob_get_clean();
                Storage::disk('public')->put($mainPath, $jpeg);
                $w = \imagesx($img);
                $h = \imagesy($img);
                $ratio = min(400 / max($w, 1), 400 / max($h, 1));
                $tw = max((int)($w * $ratio), 1);
                $th = max((int)($h * $ratio), 1);
                $thumb = \imagecreatetruecolor($tw, $th);
                \imagecopyresampled($thumb, $img, 0, 0, 0, 0, $tw, $th, $w, $h);
                ob_start();
                \imagejpeg($thumb, null, 80);
                $thumbJpeg = ob_get_clean();
                $thumbPath = 'evidencias/thumbs/' . $uuid . '.jpg';
                Storage::disk('public')->put($thumbPath, $thumbJpeg);
                \imagedestroy($img);
                \imagedestroy($thumb);
                Evidencia::create([
                    'solicitud_id' => $solicitud->id,
                    'ruta_archivo' => $mainPath,
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
        $solicitud->load(['categoria', 'colonia', 'evidencias', 'mensajes']);
        
        return view('solicitudes.show', compact('solicitud'));
    }

    /**
     * Buscar solicitud por folio
     */
    public function buscar(Request $request)
    {
        $solicitud = null;
        $folio = $request->input('folio');
        $telefono = $request->input('telefono');
        if ($folio && $telefono) {
            $solicitud = Solicitud::with(['categoria', 'colonia', 'evidencias', 'mensajes'])
                ->where('folio', strtoupper(trim($folio)))
                ->where('datos_personales->celular', preg_replace('/\D/', '', $telefono))
                ->first();
        }
        return view('solicitudes.buscar', compact('solicitud', 'folio', 'telefono'));
    }
}
