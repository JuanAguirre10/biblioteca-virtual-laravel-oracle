<?php

namespace App\Http\Controllers\Api;

use App\Services\LibroService;
use App\Services\AutorService;
use App\Services\CategoriaService;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    protected $libroService;
    protected $autorService;
    protected $categoriaService;

    public function __construct(
        LibroService $libroService,
        AutorService $autorService,
        CategoriaService $categoriaService
    ) {
        $this->libroService = $libroService;
        $this->autorService = $autorService;
        $this->categoriaService = $categoriaService;
    }

    public function index()
    {
        try {
            $libros = $this->libroService->obtenerLibros();
            return view('libros.index', compact('libros'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar los libros: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $autores = $this->autorService->obtenerAutores();
            $categorias = $this->categoriaService->obtenerCategorias();

            // Debug para verificar datos
            \Log::info('Autores obtenidos:', ['count' => count($autores), 'first' => $autores[0] ?? null]);
            \Log::info('Categorías obtenidas:', ['count' => count($categorias), 'first' => $categorias[0] ?? null]);

            return view('libros.create', compact('autores', 'categorias'));
        } catch (\Exception $e) {
            \Log::error('Error en create: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar el formulario: ' . $e->getMessage());
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
                return redirect()->route('libros.index')
                               ->with('success', 'Libro creado exitosamente');
            } else {
                return back()->withInput()
                           ->with('error', 'Error al crear el libro');
            }
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error al crear el libro: ' . $e->getMessage());
        }
    }
}