<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LibroService;
use Illuminate\Support\Facades\DB;

class WebLibroController extends Controller
{
    protected $libroService;

    public function __construct(LibroService $libroService)
    {
        $this->libroService = $libroService;
    }

    public function index()
    {
        $libros = $this->libroService->obtenerLibros();
        return view('libros.index', compact('libros'));
    }

    public function create()
    {
        $autores = DB::select("SELECT id_autor, nombre, apellido FROM AUTORES WHERE estado = 'A' ORDER BY apellido");
        $categorias = DB::select("SELECT id_categoria, nombre_categoria FROM CATEGORIAS WHERE estado = 'A' ORDER BY nombre_categoria");
        
        return view('libros.create', compact('autores', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:200',
            'id_autor' => 'required|integer',
            'id_categoria' => 'required|integer',
            'stock_total' => 'required|integer|min:1'
        ]);

        try {
            $resultado = $this->libroService->insertarLibro($request->all());
            
            if ($resultado == 1) {
                return redirect()->route('libros.index')->with('success', 'Libro creado exitosamente');
            } else {
                return back()->with('error', 'Error al crear el libro');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $libro = DB::selectOne("
            SELECT l.*, a.nombre as autor_nombre, a.apellido as autor_apellido, c.nombre_categoria 
            FROM LIBROS l 
            JOIN AUTORES a ON l.id_autor = a.id_autor 
            JOIN CATEGORIAS c ON l.id_categoria = c.id_categoria 
            WHERE l.id_libro = ? AND l.estado = 'A'
        ", [$id]);
        
        if (!$libro) {
            return redirect()->route('libros.index')->with('error', 'Libro no encontrado');
        }

        $autores = DB::select("SELECT id_autor, nombre, apellido FROM AUTORES WHERE estado = 'A' ORDER BY apellido");
        $categorias = DB::select("SELECT id_categoria, nombre_categoria FROM CATEGORIAS WHERE estado = 'A' ORDER BY nombre_categoria");
        
        return view('libros.edit', compact('libro', 'autores', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:200',
            'id_autor' => 'required|integer',
            'id_categoria' => 'required|integer',
            'stock_total' => 'required|integer|min:1'
        ]);

        try {
            return redirect()->route('libros.index')->with('success', 'Libro actualizado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            return redirect()->route('libros.index')->with('success', 'Libro eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}