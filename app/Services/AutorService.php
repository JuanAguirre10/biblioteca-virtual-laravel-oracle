<?php

namespace App\Services;

use DB;
use PDO;

class AutorService
{
    public function obtenerAutores()
{
    try {
        $autores = DB::select("
            SELECT 
                id_autor,
                nombre,
                apellido,
                COALESCE(nacionalidad, 'No registrada') as nacionalidad,
                fecha_nacimiento,
                biografia,
                estado
            FROM autores 
            WHERE estado = 'A' 
            ORDER BY apellido, nombre
        ");

        return $autores;

    } catch (\Exception $e) {
        \Log::error('Error al obtener autores: ' . $e->getMessage());
        return [];
    }
}
}