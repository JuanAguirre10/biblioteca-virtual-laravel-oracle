<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LibroController;
use App\Http\Controllers\Api\PrestamoController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    
    // RUTAS DE LIBROS
    Route::prefix('libros')->group(function () {
        Route::get('/', [LibroController::class, 'index']);
        Route::post('/', [LibroController::class, 'store']);
        Route::put('/{id}', [LibroController::class, 'update']);
        Route::delete('/{id}', [LibroController::class, 'destroy']);
        Route::get('/buscar', [LibroController::class, 'buscar']);
    });
    
    // RUTAS DE PRÉSTAMOS
    Route::prefix('prestamos')->group(function () {
        Route::get('/', [PrestamoController::class, 'index']);
        Route::post('/', [PrestamoController::class, 'store']);
        Route::put('/{id}/devolver', [PrestamoController::class, 'devolver']);
        Route::put('/{id}/renovar', [PrestamoController::class, 'renovar']);
        Route::get('/usuario/{idUsuario}', [PrestamoController::class, 'porUsuario']);
        Route::get('/vencidos', [PrestamoController::class, 'vencidos']);
        Route::get('/disponibilidad/{idLibro}', [PrestamoController::class, 'verificarDisponibilidad']);
    });

    Route::get('/autores/{id}/libros', function($id) {
    $libros = DB::select("
        SELECT l.titulo, l.editorial, l.anio_publicacion, l.stock_total, l.stock_disponible
        FROM libros l
        WHERE l.id_autor = ? AND l.estado = 'A'
        ORDER BY l.titulo
    ", [$id]);
    
    return response()->json(['libros' => $libros]);
});
    
});