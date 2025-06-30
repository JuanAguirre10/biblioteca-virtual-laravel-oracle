<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PrestamoService
{
    public function obtenerPrestamosActivos()
    {
        try {
            $results = DB::select("
                SELECT p.id_prestamo, 
                       u.nombre || ' ' || u.apellido AS usuario,
                       u.email,
                       l.titulo AS libro,
                       p.fecha_prestamo,
                       p.fecha_limite,
                       CASE 
                           WHEN p.fecha_limite < SYSDATE THEN 'VENCIDO'
                           WHEN p.fecha_limite - SYSDATE <= 3 THEN 'POR_VENCER'
                           ELSE 'ACTIVO'
                       END AS estado_detalle,
                       p.observaciones
                FROM PRESTAMOS p
                JOIN USUARIOS u ON p.id_usuario = u.id_usuario
                JOIN LIBROS l ON p.id_libro = l.id_libro
                WHERE p.estado = 'ACTIVO'
                ORDER BY p.fecha_limite
            ");
            
            return $results;
        } catch (\Exception $e) {
            throw new \Exception('Error al obtener préstamos activos: ' . $e->getMessage());
        }
    }

    public function registrarPrestamo($idUsuario, $idLibro, $diasPrestamo = 15, $observaciones = null)
    {
        $sql = "BEGIN 
                    PKG_PRESTAMOS.registrar_prestamo(
                        :id_usuario, :id_libro, :dias_prestamo, :observaciones, 
                        :resultado, :mensaje
                    );
                END;";

        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare($sql);
        
        $resultado = 0;
        $mensaje = '';
        
        $stmt->bindValue(':id_usuario', $idUsuario);
        $stmt->bindValue(':id_libro', $idLibro);
        $stmt->bindValue(':dias_prestamo', $diasPrestamo);
        $stmt->bindValue(':observaciones', $observaciones);
        $stmt->bindParam(':resultado', $resultado, \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT);
        $stmt->bindParam(':mensaje', $mensaje, \PDO::PARAM_STR|\PDO::PARAM_INPUT_OUTPUT, 500);
        
        $stmt->execute();
        
        return [
            'resultado' => $resultado,
            'mensaje' => $mensaje
        ];
    }

    public function devolverLibro($idPrestamo, $observaciones = null)
    {
        $sql = "BEGIN 
                    PKG_PRESTAMOS.devolver_libro(:id_prestamo, :observaciones, :resultado, :mensaje);
                END;";

        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare($sql);
        
        $resultado = 0;
        $mensaje = '';
        
        $stmt->bindValue(':id_prestamo', $idPrestamo);
        $stmt->bindValue(':observaciones', $observaciones);
        $stmt->bindParam(':resultado', $resultado, \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT);
        $stmt->bindParam(':mensaje', $mensaje, \PDO::PARAM_STR|\PDO::PARAM_INPUT_OUTPUT, 500);
        
        $stmt->execute();
        
        return [
            'resultado' => $resultado,
            'mensaje' => $mensaje
        ];
    }

    public function verificarDisponibilidad($idLibro)
    {
        $sql = "BEGIN :stock := PKG_PRESTAMOS.verificar_disponibilidad(:id_libro); END;";
        
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare($sql);
        
        $stock = 0;
        $stmt->bindParam(':stock', $stock, \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT);
        $stmt->bindValue(':id_libro', $idLibro);
        
        $stmt->execute();
        
        return $stock;
    }

    public function obtenerPrestamosVencidos()
    {
        try {
            $results = DB::select("
                SELECT p.id_prestamo,
                       u.nombre || ' ' || u.apellido AS usuario,
                       u.email,
                       u.telefono,
                       l.titulo AS libro,
                       p.fecha_prestamo,
                       p.fecha_limite,
                       TRUNC(SYSDATE - p.fecha_limite) AS dias_vencido,
                       (SYSDATE - p.fecha_limite) * 2 AS multa_calculada
                FROM PRESTAMOS p
                JOIN USUARIOS u ON p.id_usuario = u.id_usuario
                JOIN LIBROS l ON p.id_libro = l.id_libro
                WHERE p.estado = 'ACTIVO' AND p.fecha_limite < SYSDATE
                ORDER BY 
            CASE p.estado 
                WHEN 'VENCIDO' THEN 1 
                WHEN 'ACTIVO' THEN 2 
                ELSE 3 
            END,
            p.id_prestamo ASC
            ");
            
            return $results;
        } catch (\Exception $e) {
            throw new \Exception('Error al obtener préstamos vencidos: ' . $e->getMessage());
        }
    }
}