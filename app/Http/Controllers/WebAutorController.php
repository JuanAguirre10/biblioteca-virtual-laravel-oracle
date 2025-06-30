<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AutorService;
use Illuminate\Support\Facades\DB;

class WebAutorController extends Controller
{
    protected $autorService;

    public function __construct(AutorService $autorService)
    {
        $this->autorService = $autorService;
    }

    public function index()
    {
        $autores = $this->autorService->obtenerAutores();
        return view('autores.index', compact('autores'));
    }

    public function create()
    {
        return view('autores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'nacionalidad' => 'nullable|string|max:50',
            'fecha_nacimiento' => 'nullable|date'
        ]);

        try {
            $resultado = DB::insert("
                INSERT INTO AUTORES (
                    id_autor, nombre, apellido, nacionalidad, fecha_nacimiento, biografia
                ) VALUES (
                    seq_autor.NEXTVAL, ?, ?, ?, ?, ?
                )
            ", [
                $request->nombre,
                $request->apellido,
                $request->nacionalidad,
                $request->fecha_nacimiento,
                $request->biografia
            ]);

            if ($resultado) {
                return redirect()->route('autores.index')->with('success', 'Autor creado exitosamente');
            } else {
                return back()->withInput()->with('error', 'Error al crear el autor');
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $autor = DB::selectOne("
            SELECT * FROM AUTORES WHERE id_autor = ? AND estado = 'A'
        ", [$id]);

        if (!$autor) {
            return redirect()->route('autores.index')->with('error', 'Autor no encontrado');
        }

        $libros = DB::select("
            SELECT * FROM LIBROS WHERE id_autor = ? AND estado = 'A'
        ", [$id]);

        return view('autores.show', compact('autor', 'libros'));
    }

    public function edit($id)
    {
        $autor = DB::selectOne("
            SELECT * FROM AUTORES WHERE id_autor = ? AND estado = 'A'
        ", [$id]);

        if (!$autor) {
            return redirect()->route('autores.index')->with('error', 'Autor no encontrado');
        }

        return view('autores.edit', compact('autor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'nacionalidad' => 'nullable|string|max:50',
            'fecha_nacimiento' => 'nullable|date'
        ]);

        try {
            $resultado = DB::update("
                UPDATE AUTORES SET 
                    nombre = ?,
                    apellido = ?,
                    nacionalidad = ?,
                    fecha_nacimiento = ?,
                    biografia = ?
                WHERE id_autor = ? AND estado = 'A'
            ", [
                $request->nombre,
                $request->apellido,
                $request->nacionalidad,
                $request->fecha_nacimiento,
                $request->biografia,
                $id
            ]);

            if ($resultado) {
                return redirect()->route('autores.index')->with('success', 'Autor actualizado exitosamente');
            } else {
                return back()->withInput()->with('error', 'No se pudo actualizar el autor');
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
                SELECT COUNT(*) as total FROM LIBROS WHERE id_autor = ? AND estado = 'A'
            ", [$id]);

            if ($librosAsociados && $librosAsociados->total > 0) {
                return back()->with('error', 'No se puede eliminar el autor porque tiene libros asociados');
            }

            $resultado = DB::update("
                UPDATE AUTORES SET estado = 'I' WHERE id_autor = ?
            ", [$id]);

            if ($resultado) {
                return redirect()->route('autores.index')->with('success', 'Autor eliminado exitosamente');
            } else {
                return back()->with('error', 'No se pudo eliminar el autor');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}