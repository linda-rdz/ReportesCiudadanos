<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'numero_empleado' => 'required|string|max:50|unique:empleados,numero_empleado',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $empleado = Empleado::create([
            'nombre' => $validated['nombre'],
            'numero_empleado' => $validated['numero_empleado'],
            'contrasena_hash' => Hash::make($validated['password']),
            'rol' => 'admin',
            'estado' => 'activo',
        ]);

        Auth::guard('admin')->login($empleado);

        return redirect()->route('admin.solicitudes.index');
    }
}

