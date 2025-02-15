<?php

namespace Database\Seeders;

use App\Imports\importAdapterExcel;
use App\Models\distritoFederal;
use App\Models\entidad;
use App\Models\persona;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class cargarCatalogoEstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rutaArchivo = 'Catalogos/catalogos/ENTIDAD_FEDERATIVA_201602.xlsx';
        $path = Storage::path($rutaArchivo);
        $importacion = new importAdapterExcel();
        $datos = Excel::toCollection($importacion, $path);
        foreach ($datos as $dato) {
            foreach ($dato as $fila) {
                // dd($fila);
                if(!in_array($fila["catalog_key"], ["00", "88", "99"])){
                    $entidad = entidad::firstOrCreate([
                        'id' => $fila["catalog_key"],
                        'nombre' => $fila["entidad_federativa"],
                        'abreviatura' => $fila["abreviatura"]
                    ]);

                    $distritoFederal = distritoFederal::firstOrCreate([
                        'entidad_id' => $entidad->id,
                    ]);
                    echo "Persona creada: " . $entidad->id . "\n";
                }
            }
        }
    }
}
