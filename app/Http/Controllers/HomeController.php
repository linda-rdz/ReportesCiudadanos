<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Mostrar página principal con categorías
     */
    public function index()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        
        return view('home', compact('categorias'));
    }
}
