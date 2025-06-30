<?php

namespace App\Services;

use DB;
use PDO;

class LibroService
{
    public function obtenerLibros()
    {
        try {
        $libros = DB::select("
            SELECT 
                l.id_libro,
                l.titulo,
                l.isbn,
                l.id_autor,
                l.id_categoria,
                l.editorial,
                l.anio_publicacion,
                l.numero_paginas,
                l.idioma,
                l.stock_total,
                l.stock_disponible,
                l.descripcion,
                l.ubicacion,
                l.estado,
                l.fecha_registro,
                a.apellido || ', ' || a.nombre AS autor_completo,
                c.nombre_categoria
            FROM LIBROS l
            LEFT JOIN AUTORES a ON l.id_autor = a.id_autor
            LEFT JOIN CATEGORIAS c ON l.id_categoria = c.id_categoria
            WHERE l.estado = 'A'
            ORDER BY l.id_libro ASC
        ");

            return $libros;

        } catch (\Exception $e) {
            \Log::error('Error al obtener libros: ' . $e->getMessage());
            return [];
        }
    }
    public function insertarLibro($datos)
{
    try {
        // Usar INSERT directo en lugar del package
        $resultado = DB::insert("
            INSERT INTO LIBROS (
                id_libro, titulo, isbn, id_autor, id_categoria, 
                editorial, anio_publicacion, numero_paginas, idioma, 
                stock_total, stock_disponible, descripcion, ubicacion
            ) VALUES (
                seq_libro.NEXTVAL, ?, ?, ?, ?, 
                ?, ?, ?, ?, 
                ?, ?, ?, ?
            )
        ", [
            $datos['titulo'],
            $datos['isbn'] ?? null,
            $datos['id_autor'],
            $datos['id_categoria'],
            $datos['editorial'] ?? null,
            $datos['anio_publicacion'] ?? null,
            $datos['numero_paginas'] ?? null,
            $datos['idioma'] ?? 'Espanol',
            $datos['stock_total'],
            $datos['stock_total'], // stock_disponible = stock_total inicialmente
            $datos['descripcion'] ?? null,
            $datos['ubicacion'] ?? null
        ]);

        return $resultado ? 1 : 0;

    } catch (\Exception $e) {
        \Log::error('Error al insertar libro: ' . $e->getMessage());
        throw $e;
    }
}
    public function obtenerLibroPorId($id)
{
    try {
        $libro = DB::selectOne("
            SELECT 
                l.id_libro,
                l.titulo,
                l.isbn,
                l.id_autor,
                l.id_categoria,
                l.editorial,
                l.anio_publicacion,
                l.numero_paginas,
                l.idioma,
                l.stock_total,
                l.stock_disponible,
                l.descripcion,
                l.ubicacion,
                l.estado,
                l.fecha_registro
            FROM LIBROS l
            WHERE l.id_libro = ? AND l.estado = 'A'
        ", [$id]);

        return $libro;

    } catch (\Exception $e) {
        \Log::error('Error al obtener libro por ID: ' . $e->getMessage());
        return null;
    }
}
    
}