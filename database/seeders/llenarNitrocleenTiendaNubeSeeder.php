<?php

namespace Database\Seeders;

use App\Imports\importAdapterExcel;
use App\Models\colonia;
use App\Models\correo;
use App\Models\domicilio;
use App\Models\persona;
use App\Models\personaDomicilio;
use App\Models\telefono;
use App\Models\utility;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class llenarNitrocleenTiendaNubeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rutaArchivo = 'importarExceles/nitroclean/TIENDA NUBE_CONTACTOS.xlsx';
        $path = Storage::path($rutaArchivo);
        $importacion = new importAdapterExcel();
        $datos = Excel::toCollection($importacion, $path);
        foreach ($datos as $dato) {
            foreach ($dato as $fila) {
                // dd($fila);
                if(!key_exists("fecha", $fila->toArray())){
                    break;
                }
                $fechaRegistro = Carbon::parse($fila["fecha"])->format('Y-m-d');
                $persona = persona::create([
                    'fecha_registro' => $fechaRegistro,
                    'origen' => 'Tienda Nube',
                    'nombres' => $fila["nombre_y_apellido"],
                    'estatus' => $fila["cantidad_de_compras"] > 0 ? 'CALIENTE' : 'TIBIO',
                ]);



                if($fila["cantidad_de_compras"] > 0){
                    $codigoPostal = $fila["codigo_postal"];
                    $estado = $fila["estado"];
                    $coordenadas = [];
                    if($codigoPostal != null){
                        $coordenadas = utility::obtenerCoordenadas($codigoPostal, '');
                    }
                    $Colonia = colonia::where('codigo_postal', $fila["codigo_postal"])->first();
                    if(!$Colonia && $fila["colonia"]){
                        $Colonia = colonia::create([
                            'nombre' => strtoupper($fila["colonia"]),
                            'tipo' => '',
                            'codigo_postal' => $fila["codigo_postal"],
                            'control' => 0
                        ]);
                    }
                    if($Colonia || count($coordenadas) > 0){
                        $domicilio = domicilio::create([
                            'calle1' => strtoupper($fila["direccion"]),
                            'numero_exterior' => $fila["numero_exterior"],
                            'numero_interior' => $fila["numero_interior"],
                            'colonia_id' => $Colonia ? $Colonia->id : null,
                            'latitud' => count($coordenadas) > 0 ? $coordenadas['lat'] : null,
                            'longitud' => count($coordenadas) > 0 ? $coordenadas['lon'] : null,
                        ]);
                        personaDomicilio::create([
                            'domicilio_id' => $domicilio->id,
                            'persona_id' => $persona->id,
                        ]);
                    }
                }



                if($fila["correo_electronico"]){
                    correo::create([
                        'correo' => $fila["correo_electronico"],
                        'etiqueta' => 'correo principal',
                        'principal' => true,
                        'persona_id' => $persona->id,
                    ]);
                }
                if($fila["telefono"]){
                    telefono::create([
                        'telefono' => $fila["telefono"],
                        'etiqueta'=> 'telefono de contacto',
                        'principal' => true,
                        'persona_id' => $persona->id,
                    ]);
                }
                echo "Persona creada: " . $persona->id . "\n";
            }
        }
    }
}
