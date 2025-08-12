<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\imagenProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CatalogoController extends Controller
{
    public function listaDistribuidores(Request $request){
        $nombresPrecios = ['PRECIO_DISTRIBUIDOR'];
        //FALTA AGREGAR CATEGORIA, CODIGO, NOMBRE CORTO, DESCRIPCION, PRESENTACION
        $selects = [
            "p.id AS producto_id",
            'p.codigo',
            "COALESCE(c.nombre, 'SIN CATEGORÍA') AS categoria",
            "p.nombreCorto AS producto",
            'p.descripcion',
            "COALESCE(P.presentacion, 'POR DEFINIR') AS presentacion",
            "COALESCE(v.nombre, 'SIN VARIANTES') AS variante"
        ];
        $headings = [

        ];

        foreach ($nombresPrecios as $nombre) {
            $selects[] = "MAX(CASE WHEN pr.nombre = '$nombre' THEN pr.monto END) AS `$nombre`";
            $headings[] = $nombre;
        }
        $sql = "
            SELECT " . implode(", ", $selects) . "
            FROM productos p
            LEFT JOIN categorias c ON c.id = p.categoria_id
            LEFT JOIN variantes v ON v.producto_id = p.id
            LEFT JOIN precios pr
                ON (pr.variante_id = v.id OR (v.id IS NULL AND pr.producto_id = p.id))
            GROUP BY
                p.id, p.codigo, c.nombre, p.nombreCorto, p.descripcion,
                p.presentacion, v.nombre, v.id
        ";
        $resultado = DB::select($sql);
        // Convertir stdClass a array
        $datos = array_map(function ($row) use ($headings) {
            $rowArray = (array) $row;
            $imagenes = imagenProducto::where('producto_id', $rowArray['producto_id'])->pluck('ruta');
            $rowArray['imagenes'] = $imagenes;
            foreach (array_slice($headings, 0) as $col) {
                if (!isset($rowArray[$col]) || $rowArray[$col] === null) {
                    $rowArray[$col] = '0.00';
                }
            }
            return $rowArray;
        }, $resultado);

        return $datos;
    }
}
