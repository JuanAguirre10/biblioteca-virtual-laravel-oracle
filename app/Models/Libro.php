<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = 'LIBROS';
    protected $primaryKey = 'id_libro';
    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'isbn',
        'id_autor',
        'id_categoria',
        'editorial',
        'anio_publicacion',
        'numero_paginas',
        'idioma',
        'stock_total',
        'stock_disponible',
        'descripcion',
        'ubicacion',
        'estado'
    ];

    protected $dates = ['fecha_registro'];

    public function autor()
    {
        return $this->belongsTo(Autor::class, 'id_autor', 'id_autor');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'id_libro', 'id_libro');
    }

    public function getDisponibleAttribute()
    {
        return $this->stock_disponible > 0;
    }
}