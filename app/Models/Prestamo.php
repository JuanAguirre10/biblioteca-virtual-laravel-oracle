<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $table = 'PRESTAMOS';
    protected $primaryKey = 'id_prestamo';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_libro',
        'fecha_prestamo',
        'fecha_limite',
        'fecha_devolucion',
        'estado',
        'observaciones',
        'multa'
    ];

    protected $dates = [
        'fecha_prestamo',
        'fecha_limite',
        'fecha_devolucion'
    ];

    protected $casts = [
        'multa' => 'decimal:2'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function libro()
    {
        return $this->belongsTo(Libro::class, 'id_libro', 'id_libro');
    }

    public function getVencidoAttribute()
    {
        return $this->fecha_limite < now() && $this->estado === 'ACTIVO';
    }

    public function getDiasVencidoAttribute()
    {
        if (!$this->vencido) {
            return 0;
        }
        return now()->diffInDays($this->fecha_limite);
    }
}