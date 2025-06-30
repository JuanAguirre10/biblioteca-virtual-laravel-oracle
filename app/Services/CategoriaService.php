<?php

namespace App\Services;

use DB;
use PDO;

class CategoriaService
{
    public function obtenerCategorias()
    {
        try {
            $categorias = DB::select("
                SELECT 
                    id_categoria AS ID_CATEGORIA,
                    nombre_categoria AS NOMBRE_CATEGORIA,
                    descripcion AS DESCRIPCION,
                    estado AS ESTADO,
                    fecha_creacion AS FECHA_CREACION
                FROM CATEGORIAS 
                WHERE estado = 'A' 
                ORDER BY nombre_categoria
            ");

            return $categorias;

        } catch (\Exception $e) {
            \Log::error('Error al obtener categorías: ' . $e->getMessage());
            return [];
        }
    }
}