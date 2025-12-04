<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'numero_empleado' => 'required|string',
            'password' => 'required|string'
        ]);

        if (Auth::guard('admin')->attempt([
            'numero_empleado' => $credentials['numero_empleado'],
            'password' => $credentials['password']
        ], $request->filled('remember'))) {
            $request->session()->regenerate();
            $empleado = auth()->user();
            if (!$empleado || !$empleado->isAdmin() || !$empleado->isActivo()) {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'numero_empleado' => 'Acceso exclusivo para administradores activos.'
                ])->onlyInput('numero_empleado');
            }
            return redirect()->route('admin.solicitudes.index');
        }

        return back()->withErrors([
            'numero_empleado' => 'Número de empleado o contraseña incorrectos.'
        ])->onlyInput('numero_empleado');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function checkEmail(Request $request)
    {
        $email = $request->query('email');
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['exists' => false, 'valid' => false]);
        }
        $exists = User::where('email', $email)->exists();
        return response()->json(['exists' => $exists, 'valid' => true]);
    }
}
