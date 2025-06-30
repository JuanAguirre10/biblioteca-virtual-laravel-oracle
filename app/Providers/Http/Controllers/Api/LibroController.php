<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LibroService;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    protected $libroService;

    public function __construct(LibroService $libroService)
    {
        $this->libroService = $libroService;
    }

    public function index()
    {
        try {
            $libros = $this->libroService->obtenerLibros();
            return response()->json([
                'success' => true,
                'data' => $libros
            ], 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener libros: ' . $e->getMessage()
            ], 500);
        }
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
                return response()->json([
                    'success' => true,
                    'message' => 'Libro creado exitosamente'
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el libro'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear libro: ' . $e->getMessage()
            ], 500);
        }
    }
}