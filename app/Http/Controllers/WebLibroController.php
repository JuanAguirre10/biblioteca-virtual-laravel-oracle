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
        // Obtener stock prestado actual
        $libroPrestado = DB::selectOne("
            SELECT (stock_total - stock_disponible) as prestados 
            FROM LIBROS 
            WHERE id_libro = ?
        ", [$id]);
        
        $stockPrestado = $libroPrestado ? $libroPrestado->prestados : 0;
        $nuevoStockDisponible = $request->stock_total - $stockPrestado;
        
        // Validar que el nuevo stock total no sea menor que los prestados
        if ($nuevoStockDisponible < 0) {
            return back()->withInput()
                        ->with('error', 'El stock total no puede ser menor que los libros prestados actualmente (' . $stockPrestado . ')');
        }

        // Actualizar el libro
        $resultado = DB::update("
            UPDATE LIBROS SET 
                titulo = ?,
                isbn = ?,
                id_autor = ?,
                id_categoria = ?,
                editorial = ?,
                anio_publicacion = ?,
                numero_paginas = ?,
                idioma = ?,
                stock_total = ?,
                stock_disponible = ?,
                descripcion = ?,
                ubicacion = ?
            WHERE id_libro = ? AND estado = 'A'
        ", [
            $request->titulo,
            $request->isbn,
            $request->id_autor,
            $request->id_categoria,
            $request->editorial,
            $request->anio_publicacion,
            $request->numero_paginas,
            $request->idioma ?? 'Espanol',
            $request->stock_total,
            $nuevoStockDisponible,
            $request->descripcion,
            $request->ubicacion,
            $id
        ]);

        if ($resultado) {
            return redirect()->route('libros.index')->with('success', 'Libro actualizado exitosamente');
        } else {
            return back()->withInput()->with('error', 'No se pudo actualizar el libro');
        }
        
    } catch (\Exception $e) {
        return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
    }
}

    public function destroy($id)
{
    try {
        // Verificar si el libro tiene préstamos activos
        $prestamosActivos = DB::selectOne("
            SELECT COUNT(*) as total 
            FROM PRESTAMOS 
            WHERE id_libro = ? AND estado = 'ACTIVO'
        ", [$id]);

        if ($prestamosActivos && $prestamosActivos->total > 0) {
            return back()->with('error', 'No se puede eliminar el libro porque tiene préstamos activos');
        }

        // Eliminar lógicamente (cambiar estado a 'I')
        $resultado = DB::update("
            UPDATE LIBROS 
            SET estado = 'I' 
            WHERE id_libro = ?
        ", [$id]);

        if ($resultado) {
            return redirect()->route('libros.index')->with('success', 'Libro eliminado exitosamente');
        } else {
            return back()->with('error', 'No se pudo eliminar el libro');
        }
        
    } catch (\Exception $e) {
        return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
    }
}
    public function show($id)
{
    try {
        $libro = DB::selectOne("
            SELECT 
                l.*,
                a.apellido || ', ' || a.nombre AS autor_completo,
                c.nombre_categoria,
                (l.stock_total - l.stock_disponible) as libros_prestados
            FROM LIBROS l
            LEFT JOIN AUTORES a ON l.id_autor = a.id_autor
            LEFT JOIN CATEGORIAS c ON l.id_categoria = c.id_categoria
            WHERE l.id_libro = ? AND l.estado = 'A'
        ", [$id]);

        if (!$libro) {
            return redirect()->route('libros.index')->with('error', 'Libro no encontrado');
        }

        return view('libros.show', compact('libro'));
        
    } catch (\Exception $e) {
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}
}