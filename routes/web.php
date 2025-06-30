<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebLibroController;
use App\Http\Controllers\WebPrestamoController;
use App\Http\Controllers\WebCategoriaController;
use App\Http\Controllers\WebAutorController;

Route::get('/', function () {
    return redirect('/libros');
});

Route::prefix('libros')->group(function () {
    Route::get('/', [WebLibroController::class, 'index'])->name('libros.index');
    Route::get('/crear', [WebLibroController::class, 'create'])->name('libros.create');
    Route::post('/', [WebLibroController::class, 'store'])->name('libros.store');
    Route::get('/{id}/editar', [WebLibroController::class, 'edit'])->name('libros.edit');
    Route::put('/{id}', [WebLibroController::class, 'update'])->name('libros.update');
    Route::delete('/{id}', [WebLibroController::class, 'destroy'])->name('libros.destroy');
    Route::get('/{id}', [WebLibroController::class, 'show'])->name('libros.show');
});

Route::prefix('prestamos')->group(function () {
    Route::get('/', [WebPrestamoController::class, 'index'])->name('prestamos.index');
    Route::get('/crear', [WebPrestamoController::class, 'create'])->name('prestamos.create');
    Route::post('/', [WebPrestamoController::class, 'store'])->name('prestamos.store');
    Route::put('/{id}/devolver', [WebPrestamoController::class, 'devolver'])->name('prestamos.devolver');
    Route::get('/vencidos', [WebPrestamoController::class, 'vencidos'])->name('prestamos.vencidos');
    Route::get('/historial', [WebPrestamoController::class, 'historial'])->name('prestamos.historial');  // ← NUEVA RUTA

});

Route::prefix('autores')->group(function () {
    Route::get('/', [WebAutorController::class, 'index'])->name('autores.index');
    Route::get('/crear', [WebAutorController::class, 'create'])->name('autores.create');
    Route::post('/', [WebAutorController::class, 'store'])->name('autores.store');
    Route::get('/{id}', [WebAutorController::class, 'show'])->name('autores.show');
    Route::get('/{id}/editar', [WebAutorController::class, 'edit'])->name('autores.edit');
    Route::put('/{id}', [WebAutorController::class, 'update'])->name('autores.update');
    Route::delete('/{id}', [WebAutorController::class, 'destroy'])->name('autores.destroy');
});

Route::prefix('categorias')->group(function () {
    Route::get('/', [WebCategoriaController::class, 'index'])->name('categorias.index');
    Route::get('/crear', [WebCategoriaController::class, 'create'])->name('categorias.create');
    Route::post('/', [WebCategoriaController::class, 'store'])->name('categorias.store');
    Route::get('/{id}', [WebCategoriaController::class, 'show'])->name('categorias.show');
    Route::get('/{id}/editar', [WebCategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('/{id}', [WebCategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/{id}', [WebCategoriaController::class, 'destroy'])->name('categorias.destroy');
});

Route::get('/api/autores/{id}/libros', [WebAutorController::class, 'obtenerLibrosAutor'])->name('api.autores.libros');
