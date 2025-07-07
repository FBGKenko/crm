<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class perfilPrecio extends Model
{
    use HasFactory;

    public static function ComprobarSiTienenPrecios($producto)
    {
        $resultado = (object)[
            'sinPrecios' => true,
            'variantes' => false,
        ];
        if ($producto->variantes->count() > 0) {
            foreach ($producto->variantes as $variante) {
                if ($variante->precios->count() > 0) {
                    $resultado->variantes = true;
                    $resultado->sinPrecios = false;
                    break;
                }
            }
        } else {
            if ($producto->precios->count() > 0) {
                $resultado->sinPrecios = false;
            }
        }
        return $resultado;
    }
}
