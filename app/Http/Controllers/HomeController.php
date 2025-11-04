<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Mostrar página principal con categorías
     */
    public function index(Request $request)
    {
        $categorias = Categoria::orderBy('nombre')->get();
        
        // Si hay un folio en la sesión y el usuario viene de crear una solicitud,
        // mostrar el modal (se mostrará en la vista)
        // La sesión se limpiará cuando el usuario cierre el modal y se redirija
        
        return view('home', compact('categorias'));
    }
}
