<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventario extends Model
{
    use HasFactory;
    protected $fillable = [
        'codigo',
        'nombreProducto',
        'costo',
        'precio',
        'existencia',
        'unidadMedida',
    ];

    public static function crear($datos){
        inventario::create($datos);
    }

    public static function buscarProductoPorCodigo($codigo){
        return inventario::where('codigo', $codigo)->first() != null;
    }

    public static function cambiarExistencia($datos){
        $producto = inventario::find($datos['idProducto']);
        $nuevaExistencia = $producto->existencia + $datos['cantidad'];
        $producto->update([
            'existencia' => $nuevaExistencia
        ]);
    }
}
