<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoriaService;
use Illuminate\Support\Facades\DB;

class WebCategoriaController extends Controller
{
    protected $categoriaService;

    public function __construct(CategoriaService $categoriaService)
    {
        $this->categoriaService = $categoriaService;
    }

    public function index()
    {
        $categorias = $this->categoriaService->obtenerCategorias();
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_categoria' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:500'
        ]);

        try {
            $resultado = DB::insert("
                INSERT INTO CATEGORIAS (
                    id_categoria, nombre_categoria, descripcion
                ) VALUES (
                    seq_categoria.NEXTVAL, ?, ?
                )
            ", [
                $request->nombre_categoria,
                $request->descripcion
            ]);

            if ($resultado) {
                return redirect()->route('categorias.index')->with('success', 'Categoría creada exitosamente');
            } else {
                return back()->withInput()->with('error', 'Error al crear la categoría');
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $categoria = DB::selectOne("
            SELECT * FROM CATEGORIAS WHERE id_categoria = ? AND estado = 'A'
        ", [$id]);

        if (!$categoria) {
            return redirect()->route('categorias.index')->with('error', 'Categoría no encontrada');
        }

        $libros = DB::select("
            SELECT l.*, a.apellido || ', ' || a.nombre AS autor_completo
            FROM LIBROS l
            LEFT JOIN AUTORES a ON l.id_autor = a.id_autor
            WHERE l.id_categoria = ? AND l.estado = 'A'
            ORDER BY l.titulo
        ", [$id]);

        return view('categorias.show', compact('categoria', 'libros'));
    }

    public function edit($id)
    {
        $categoria = DB::selectOne("
            SELECT * FROM CATEGORIAS WHERE id_categoria = ? AND estado = 'A'
        ", [$id]);

        if (!$categoria) {
            return redirect()->route('categorias.index')->with('error', 'Categoría no encontrada');
        }

        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_categoria' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:500'
        ]);

        try {
            $resultado = DB::update("
                UPDATE CATEGORIAS SET 
                    nombre_categoria = ?,
                    descripcion = ?
                WHERE id_categoria = ? AND estado = 'A'
            ", [
                $request->nombre_categoria,
                $request->descripcion,
                $id
            ]);

            if ($resultado) {
                return redirect()->route('categorias.index')->with('success', 'Categoría actualizada exitosamente');
            } else {
                return back()->withInput()->with('error', 'No se pudo actualizar la categoría');
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Verificar si tiene libros asociados
            $librosAsociados = DB::selectOne("
                SELECT COUNT(*) as total FROM LIBROS WHERE id_categoria = ? AND estado = 'A'
            ", [$id]);

            if ($librosAsociados && $librosAsociados->total > 0) {
                return back()->with('error', 'No se puede eliminar la categoría porque tiene libros asociados');
            }

            $resultado = DB::update("
                UPDATE CATEGORIAS SET estado = 'I' WHERE id_categoria = ?
            ", [$id]);

            if ($resultado) {
                return redirect()->route('categorias.index')->with('success', 'Categoría eliminada exitosamente');
            } else {
                return back()->with('error', 'No se pudo eliminar la categoría');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}