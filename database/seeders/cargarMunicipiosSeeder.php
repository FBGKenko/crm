<?php

namespace Database\Seeders;

use App\Imports\importAdapterExcel;
use App\Imports\importarMunicipios;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class cargarMunicipiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rutaArchivo = 'Catalogos/catalogos/CODIGO_POSTAL.xlsx';
        if (!Storage::exists($rutaArchivo)) {
            echo "El archivo no existe\n";
            return;
        }
        try {
            $path = Storage::path($rutaArchivo);
            $importacion = new importarMunicipios();
            Excel::import($importacion, $path);
            // echo "Buscando archivo\n";
            // $datos = Excel::toCollection($importacion, $path);
            // echo "Archivo cargado\n";
            // foreach ($datos as $index => $hoja) { // Iterar sobre cada hoja del archivo
            //     echo "Leyendo hoja: " . ($index + 1) . "\n";
            //     foreach ($hoja as $fila) {
            //         // dd($fila);

            //     }
            // }
            echo "Lectura finalizada\n";
        } catch (Exception $e) {
            echo "Fallo al leer el archivo";
            Log::info($e);
        }
    }
}
