<?php

namespace App\Imports;

use App\Models\categoria;
use App\Models\producto;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CatalogoProductoImport implements ToCollection, WithHeadingRow, WithBatchInserts
{

    public function collection(Collection $rows)
    {
        $recorrer = 0;

        foreach ($rows as $index => $row) {
            $numeroVariantes = $rows->where('nombre_corto', $row['nombre_corto'])->count();

            $categoria = categoria::where('nombre', trim(strtoupper($row['categorias'])))->first();
            if(!$categoria){
                $categoria = categoria::create([
                    'nombre' => trim(strtoupper($row['categorias'])),
                ]);
            }
            Log::info($recorrer . '||' . $row);
            //buscar o crear productos
            $producto = producto::firstOrCreate([
                // 'claveCamarena' => trim(strtoupper($row['clave_camarena'] ?? '')),
                'nombreCorto' => trim(strtoupper($row['nombre_corto'])),
                'descripcion' => trim(strtoupper($row['descripcion'])),
                // 'identificadorUrl' => trim(strtoupper($row['identificador_de_url'] ?? null)),
                // 'nombreWeb' => trim(strtoupper($row['nombre_web'])),
                'videoUsoUrl' => $row['video_uso'],
                'fichaTecnicaUrl' => $row['ficha_tecnica'],
                // 'descripcionWeb' => trim(strtoupper($row['descripcion_web'])),
                'categoria_id' => $categoria->id,
            ]);
            // $codigoProducto = 'NIT' . strtoupper(substr($categoria->nombre, 0, 2)) . trim(strtoupper($row['presentacion'])) . $producto->id;
            // $producto->codigo = $codigoProducto;
            // $producto->save();

            $precioCamarenaCrudo = substr(explode('*', $row['precio_camarena'])[0], 1);
            if($numeroVariantes > 1){
                $variante = $producto->variantes()->firstOrCreate([
                    'sku' => trim(strtoupper($row['sku'])),
                    'nombre' => trim(strtoupper($row['variantes'])),
                    'presentacion' => trim(strtoupper($row['presentacion2'])),
                    'cantidad' => $row['cantidad_analisis'],
                    'unidad' => $row['unidad_analisis'],
                    'producto_id' => $producto->id,
                ]);
                $codigoProducto = 'NIT' . strtoupper(substr($categoria->nombre, 0, 2)) . trim(strtoupper($row['presentacion'])) . $producto->id;
                $variante->codigo = $codigoProducto . '-' . $variante->id;
                $variante->save();
                $variante->precios()->createMany([
                    [
                        'monto' => floatval($precioCamarenaCrudo) * 1.16,
                        'nombre' => 'PRECIO CAMARENA',
                    ],
                    [
                        'monto' => (is_numeric(trim($row['precio_web'] ?? '')) ? (float) $row['precio_web'] : 0.0),
                        'nombre' => 'PRECIO WEB',
                    ],
                    [
                        'monto' => (is_numeric(trim($row['precio_cat_pdf'] ?? '')) ? (float) $row['precio_cat_pdf'] : 0.0),
                        'nombre' => 'PRECIO CAT PDF',
                    ],
                    [
                        'monto' => (is_numeric(trim($row['precio_mayoreo'] ?? '')) ? (float) $row['precio_mayoreo'] : 0.0),
                        'nombre' => 'PRECIO MAYOREO',
                    ],
                    [
                        'monto' => (is_numeric(trim($row['precio_distribuidor'] ?? '')) ? (float) $row['precio_distribuidor'] : 0.0),
                        'nombre' => 'PRECIO DISTRIBUIDOR',
                    ],
                    [
                        'monto' => (is_numeric(trim($row['precio_sugerido'] ?? '')) ? (float) $row['precio_sugerido'] : 0.0),
                        'nombre' => 'PRECIO SUGERIDO',
                    ],
                    [
                        'monto' => (is_numeric(trim($row['costo'] ?? '')) ? (float) $row['costo'] : 0.0),
                        'nombre' => 'COSTO',
                    ]
                ]);
            }
            else{
                $producto->presentacion = trim(strtoupper($row['presentacion']));
                $codigoProducto = 'NIT' . strtoupper(substr($categoria->nombre, 0, 2)) . trim(strtoupper($row['presentacion'])) . $producto->id;
                $producto->codigo = $codigoProducto;
                $producto->save();
                $producto->precios()->createMany([
                    [
                        'monto' => floatval($precioCamarenaCrudo) * 1.16,
                        'nombre' => 'PRECIO CAMARENA',
                    ],
                    [
                        'monto' => (is_numeric(trim($row['precio_web'] ?? '')) ? (float) $row['precio_web'] : 0.0),
                        'nombre' => 'PRECIO WEB',
                    ],
                    [
                        'monto' => (is_numeric(trim($row['precio_cat_pdf'] ?? '')) ? (float) $row['precio_cat_pdf'] : 0.0),
                        'nombre' => 'PRECIO CAT PDF',
                    ],
                    [
                        'monto' => (is_numeric(trim($row['precio_mayoreo'] ?? '')) ? (float) $row['precio_mayoreo'] : 0.0),
                        'nombre' => 'PRECIO MAYOREO',
                    ],
                    [
                        'monto' => (is_numeric(trim($row['precio_distribuidor'] ?? '')) ? (float) $row['precio_distribuidor'] : 0.0),
                        'nombre' => 'PRECIO DISTRIBUIDOR',
                    ],
                    [
                        'monto' => (is_numeric(trim($row['precio_sugerido'] ?? '')) ? (float) $row['precio_sugerido'] : 0.0),
                        'nombre' => 'PRECIO SUGERIDO',
                    ],
                    [
                        'monto' => (is_numeric(trim($row['costo'] ?? '')) ? (float) $row['costo'] : 0.0),
                        'nombre' => 'COSTO',
                    ]
                ]);
            }
        }
    }

    public function batchSize(): int
    {
        return 200;
    }

    public function headings(): array
    {
        return [
            "origen",
            "categoria",
            "presentacion",
            "num",
            "codigo",
            "sku",
            "categorias",
            "subcategoria",
            "marca",
            "nombre_corto",
            "presentacion2",
            "variantes",
            "cantidad_analisis",
            "unidad_analisis",
            "descripcion",
            "identificador_de_url",
            "nombre_web",
            "precio_litro",
            "clave_camarena",
            "precio_camarena",
            "precio_web",
            "precio_cat_pdf",
            "precio_mayoreo",
            "costo",
            "precio_distribuidor",
            "precio_sugerido",
            "video_uso",
            "ficha_tecnica",
            "descripcion_web",
            "fotos",
            "tienda_nube",
            "cotizador",
            "google",
            "campo",
            "tomar_fotos",
            "fotos_renombradas",
            "editar_fotos",
            "cargar_fotos_en_web"
        ];
    }
}
