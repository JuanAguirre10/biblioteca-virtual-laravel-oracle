<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PrestamoService;
use Illuminate\Support\Facades\DB;

class WebPrestamoController extends Controller
{
    protected $prestamoService;

    public function __construct(PrestamoService $prestamoService)
    {
        $this->prestamoService = $prestamoService;
    }

    public function index()
    {
        $prestamos = $this->prestamoService->obtenerPrestamosActivos();
        return view('prestamos.index', compact('prestamos'));
    }

    public function create()
    {
        $usuarios = DB::select("SELECT id_usuario, nombre, apellido, email FROM USUARIOS WHERE estado = 'A' ORDER BY apellido");
        $libros = DB::select("
            SELECT l.id_libro, l.titulo, l.stock_disponible, 
                   a.nombre || ' ' || a.apellido AS autor
            FROM LIBROS l 
            JOIN AUTORES a ON l.id_autor = a.id_autor 
            WHERE l.estado = 'A' AND l.stock_disponible > 0 
            ORDER BY l.titulo
        ");
        
        return view('prestamos.create', compact('usuarios', 'libros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|integer',
            'id_libro' => 'required|integer',
            'dias_prestamo' => 'nullable|integer|min:1|max:30'
        ]);

        try {
            $resultado = $this->prestamoService->registrarPrestamo(
                $request->id_usuario,
                $request->id_libro,
                $request->dias_prestamo ?? 15,
                $request->observaciones
            );
            
            if ($resultado['resultado'] == 1) {
                return redirect()->route('prestamos.index')->with('success', $resultado['mensaje']);
            } else {
                return back()->with('error', $resultado['mensaje']);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function devolver(Request $request, $id)
    {
        try {
            $resultado = $this->prestamoService->devolverLibro($id, $request->observaciones);
            
            if ($resultado['resultado'] == 1) {
                return redirect()->route('prestamos.index')->with('success', $resultado['mensaje']);
            } else {
                return back()->with('error', $resultado['mensaje']);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function vencidos()
    {
        $prestamos = $this->prestamoService->obtenerPrestamosVencidos();
        return view('prestamos.vencidos', compact('prestamos'));
    }
}