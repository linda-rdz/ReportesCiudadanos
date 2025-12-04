<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;

class AdminEmpleadoController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->check() || (method_exists(auth()->user(), 'isAdmin') ? !auth()->user()->isAdmin() : (auth()->user()->rol ?? null) !== 'admin')) {
            return redirect()->route('login')->with('error', 'Acceso denegado. Solo administradores.');
        }
        $search = $request->get('search');
        $query = Empleado::query();
        if ($search) {
            $query->where(function($q) use ($search){
                $q->where('numero_empleado','like','%'.$search.'%')
                  ->orWhere('nombre','like','%'.$search.'%')
                  ->orWhere('rol','like','%'.$search.'%');
            });
        }
        $empleados = $query->orderBy('nombre')->paginate(15);
        return view('admin.empleados.index', compact('empleados', 'search'));
    }

    public function create()
    {
        if (!auth()->check() || (method_exists(auth()->user(), 'isAdmin') ? !auth()->user()->isAdmin() : (auth()->user()->rol ?? null) !== 'admin')) {
            return redirect()->route('login')->with('error', 'Acceso denegado. Solo administradores.');
        }
        return view('admin.empleados.create');
    }

    public function store(Request $request)
    {
        if (!auth()->check() || (method_exists(auth()->user(), 'isAdmin') ? !auth()->user()->isAdmin() : (auth()->user()->rol ?? null) !== 'admin')) {
            return redirect()->route('login')->with('error', 'Acceso denegado. Solo administradores.');
        }
        $validated = $request->validate([
            'numero_empleado' => 'required|string|max:50|unique:empleados,numero_empleado',
            'nombre' => 'required|string|max:255',
            'rol' => 'required|string|in:admin,supervisor,operador',
            'estado' => 'required|string|in:activo,inactivo',
            'password' => 'required|string|min:8|confirmed',
        ]);
        Empleado::create([
            'numero_empleado' => $validated['numero_empleado'],
            'nombre' => $validated['nombre'],
            'rol' => $validated['rol'],
            'estado' => $validated['estado'],
            'contrasena_hash' => Hash::make($validated['password']),
        ]);
        return redirect()->route('admin.empleados.index')->with('success', 'Empleado creado correctamente');
    }

    public function edit(Empleado $empleado)
    {
        if (!auth()->check() || (method_exists(auth()->user(), 'isAdmin') ? !auth()->user()->isAdmin() : (auth()->user()->rol ?? null) !== 'admin')) {
            return redirect()->route('login')->with('error', 'Acceso denegado. Solo administradores.');
        }
        return view('admin.empleados.edit', compact('empleado'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        if (!auth()->check() || (method_exists(auth()->user(), 'isAdmin') ? !auth()->user()->isAdmin() : (auth()->user()->rol ?? null) !== 'admin')) {
            return redirect()->route('login')->with('error', 'Acceso denegado. Solo administradores.');
        }
        $validated = $request->validate([
            'numero_empleado' => 'required|string|max:50|unique:empleados,numero_empleado,'.$empleado->id,
            'nombre' => 'required|string|max:255',
            'rol' => 'required|string|in:admin,supervisor,operador',
            'estado' => 'required|string|in:activo,inactivo',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        $empleado->numero_empleado = $validated['numero_empleado'];
        $empleado->nombre = $validated['nombre'];
        $empleado->rol = $validated['rol'];
        $empleado->estado = $validated['estado'];
        if (!empty($validated['password'])) {
            $empleado->contrasena_hash = Hash::make($validated['password']);
        }
        $empleado->save();
        return redirect()->route('admin.empleados.index')->with('success', 'Empleado actualizado correctamente');
    }

    public function destroy(Empleado $empleado)
    {
        if (!auth()->check() || (method_exists(auth()->user(), 'isAdmin') ? !auth()->user()->isAdmin() : (auth()->user()->rol ?? null) !== 'admin')) {
            return redirect()->route('login')->with('error', 'Acceso denegado. Solo administradores.');
        }
        $empleado->delete();
        return back()->with('success', 'Empleado eliminado');
    }
}

