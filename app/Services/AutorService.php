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
                    id_autor AS ID_AUTOR,
                    nombre AS NOMBRE,
                    apellido AS APELLIDO,
                    nacionalidad AS NACIONALIDAD,
                    fecha_nacimiento AS FECHA_NACIMIENTO,
                    biografia AS BIOGRAFIA,
                    estado AS ESTADO
                FROM AUTORES 
                WHERE estado = 'A' 
                ORDER BY id_autor ASC
            ");

            return $autores;

        } catch (\Exception $e) {
            \Log::error('Error al obtener autores: ' . $e->getMessage());
            return [];
        }
    }
}