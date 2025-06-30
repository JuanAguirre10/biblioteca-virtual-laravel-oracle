<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PrestamoService;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    protected $prestamoService;

    public function __construct(PrestamoService $prestamoService)
    {
        $this->prestamoService = $prestamoService;
    }

    public function index()
    {
        try {
            $prestamos = $this->prestamoService->obtenerPrestamosActivos();
            return response()->json([
                'success' => true,
                'data' => $prestamos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener préstamos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|integer',
            'id_libro' => 'required|integer',
            'dias_prestamo' => 'nullable|integer|min:1|max:30',
            'observaciones' => 'nullable|string|max:500'
        ]);

        try {
            $resultado = $this->prestamoService->registrarPrestamo(
                $request->id_usuario,
                $request->id_libro,
                $request->dias_prestamo ?? 15,
                $request->observaciones
            );
            
            if ($resultado['resultado'] == 1) {
                return response()->json([
                    'success' => true,
                    'message' => $resultado['mensaje']
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $resultado['mensaje']
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar préstamo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function devolver(Request $request, $id)
    {
        $request->validate([
            'observaciones' => 'nullable|string|max:500'
        ]);

        try {
            $resultado = $this->prestamoService->devolverLibro(
                $id,
                $request->observaciones
            );
            
            if ($resultado['resultado'] == 1) {
                return response()->json([
                    'success' => true,
                    'message' => $resultado['mensaje']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $resultado['mensaje']
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al devolver libro: ' . $e->getMessage()
            ], 500);
        }
    }

    public function renovar(Request $request, $id)
    {
        $request->validate([
            'dias_adicionales' => 'nullable|integer|min:1|max:30'
        ]);

        try {
            $resultado = $this->prestamoService->renovarPrestamo(
                $id,
                $request->dias_adicionales ?? 15
            );
            
            if ($resultado['resultado'] == 1) {
                return response()->json([
                    'success' => true,
                    'message' => $resultado['mensaje']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $resultado['mensaje']
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al renovar préstamo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function porUsuario($idUsuario)
    {
        try {
            $prestamos = $this->prestamoService->obtenerPrestamosUsuario($idUsuario);
            return response()->json([
                'success' => true,
                'data' => $prestamos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener préstamos del usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function vencidos()
    {
        try {
            $prestamos = $this->prestamoService->obtenerPrestamosVencidos();
            return response()->json([
                'success' => true,
                'data' => $prestamos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener préstamos vencidos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verificarDisponibilidad($idLibro)
    {
        try {
            $stock = $this->prestamoService->verificarDisponibilidad($idLibro);
            return response()->json([
                'success' => true,
                'stock_disponible' => $stock,
                'disponible' => $stock > 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar disponibilidad: ' . $e->getMessage()
            ], 500);
        }
    }
}