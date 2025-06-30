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
    $prestamos = DB::select("
        SELECT 
            p.id_prestamo,
            p.fecha_prestamo,
            p.fecha_limite,
            p.fecha_devolucion,
            p.estado,
            p.observaciones,
            p.multa,
            u.nombre || ' ' || u.apellido AS usuario,
            u.email,
            l.titulo AS libro,
            CASE 
                WHEN p.fecha_limite < SYSDATE AND p.estado = 'ACTIVO' THEN 'VENCIDO'
                WHEN p.fecha_limite - SYSDATE <= 3 AND p.estado = 'ACTIVO' THEN 'POR_VENCER'
                ELSE p.estado
            END AS estado_real
        FROM PRESTAMOS p
        JOIN USUARIOS u ON p.id_usuario = u.id_usuario
        JOIN LIBROS l ON p.id_libro = l.id_libro
        WHERE p.estado IN ('ACTIVO', 'VENCIDO')
        ORDER BY 
            CASE 
                WHEN p.fecha_limite < SYSDATE AND p.estado = 'ACTIVO' THEN 1
                WHEN p.estado = 'VENCIDO' THEN 1
                WHEN p.fecha_limite - SYSDATE <= 3 AND p.estado = 'ACTIVO' THEN 2
                WHEN p.estado = 'ACTIVO' THEN 3
                ELSE 4
            END,
            p.id_prestamo ASC
    ");
    
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
        $prestamos = DB::select("
        SELECT 
            p.id_prestamo,
            p.fecha_prestamo,
            p.fecha_limite,
            p.fecha_devolucion,
            p.estado,
            p.observaciones,
            p.multa,
            u.nombre || ' ' || u.apellido AS usuario,
            u.email,
            u.telefono,
            l.titulo AS libro,
            TRUNC(SYSDATE - p.fecha_limite) AS dias_vencido,
            (SYSDATE - p.fecha_limite) * 2 AS multa_calculada
        FROM prestamos p
        JOIN usuarios u ON p.id_usuario = u.id_usuario
        JOIN libros l ON p.id_libro = l.id_libro
        WHERE (p.estado = 'VENCIDO' OR (p.estado = 'ACTIVO' AND p.fecha_limite < SYSDATE))
        ORDER BY 
            CASE p.estado 
                WHEN 'VENCIDO' THEN 1 
                WHEN 'ACTIVO' THEN 2 
                ELSE 3 
            END,
            p.id_prestamo ASC
    ");
    
    return view('prestamos.vencidos', compact('prestamos'));
    
    return view('prestamos.vencidos', compact('prestamos'));
    }
    public function historial(Request $request)
{
    $fechaInicio = $request->fecha_inicio;
    $fechaFin = $request->fecha_fin;
    $usuario = $request->usuario;
    $estado = $request->estado;
    
    $query = "
        SELECT 
            p.id_prestamo,
            p.fecha_prestamo,
            p.fecha_limite,
            p.fecha_devolucion,
            p.estado,
            p.observaciones,
            p.multa,
            u.nombre || ' ' || u.apellido AS usuario,
            u.email,
            l.titulo AS libro,
            a.apellido || ', ' || a.nombre AS autor,
            c.nombre_categoria AS categoria
        FROM prestamos p
        JOIN usuarios u ON p.id_usuario = u.id_usuario
        JOIN libros l ON p.id_libro = l.id_libro
        JOIN autores a ON l.id_autor = a.id_autor
        JOIN categorias c ON l.id_categoria = c.id_categoria
        WHERE 1=1
    ";
    
    $params = [];
    
    if ($fechaInicio) {
        $query .= " AND p.fecha_prestamo >= TO_DATE(?, 'YYYY-MM-DD')";
        $params[] = $fechaInicio;
    }
    
    if ($fechaFin) {
        $query .= " AND p.fecha_prestamo <= TO_DATE(?, 'YYYY-MM-DD')";
        $params[] = $fechaFin;
    }
    
    if ($usuario) {
        $query .= " AND UPPER(u.nombre || ' ' || u.apellido) LIKE UPPER(?)";
        $params[] = '%' . $usuario . '%';
    }
    
    if ($estado && $estado != 'TODOS') {
        $query .= " AND p.estado = ?";
        $params[] = $estado;
    }
    
    $query .= " ORDER BY p.fecha_prestamo DESC";
    
    $prestamos = DB::select($query, $params);
    
    // Obtener usuarios para el filtro
    $usuarios = DB::select("
        SELECT DISTINCT u.nombre || ' ' || u.apellido AS nombre_completo
        FROM usuarios u 
        JOIN prestamos p ON u.id_usuario = p.id_usuario
        ORDER BY nombre_completo
    ");
    
    return view('prestamos.historial', compact('prestamos', 'usuarios', 'fechaInicio', 'fechaFin', 'usuario', 'estado'));
}
}