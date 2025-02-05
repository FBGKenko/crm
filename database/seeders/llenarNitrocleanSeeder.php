<?php

namespace Database\Seeders;

use App\Imports\importacionDeKommoNitroclean;
use App\Imports\importAdapterExcel;
use App\Models\colonia;
use App\Models\correo;
use App\Models\domicilio;
use App\Models\empresaDomicilio;
use App\Models\persona;
use App\Models\personaDomicilio;
use App\Models\telefono;
use App\Models\utility;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class llenarNitrocleanSeeder extends Seeder
{
    public function run(): void
    {
        $rutaArchivo = 'importarExceles/nitroclean/kommo_export_contacts_2025-01-18.xlsx';
        $path = Storage::path($rutaArchivo);
        $importacion = new importAdapterExcel();
        $datos = Excel::toCollection($importacion, $path);
        foreach ($datos as $dato) {
            foreach ($dato as $fila) {
                // dd($fila);
                $fechaRegistro = Carbon::parse($fila["fecha_de_creacion"])->format('Y-m-d');
                $persona = persona::create([
                    'identificadorOrigen' => $fila["id"],
                    'fecha_registro' => $fechaRegistro,
                    'origen' => 'Kommo',
                    'nombres' => $fila["nombre"],
                    'apellido_paterno' => $fila["apellido"],
                    'etiquetas' => $fila["etiquetas"],
                    'apodo' => $fila["alias_contacto"],
                    'observaciones' => $fila["observaciones_contacto"],
                ]);

                $codigoPostal = $fila["codigo_postal_contacto"];
                $estado = $fila["estado_contacto"];
                $coordenadas = [];
                echo "{$estado}, {$codigoPostal}\n";
                if($codigoPostal != null){
                    $coordenadas = utility::obtenerCoordenadas($codigoPostal, '');
                }
                else if($estado != null){
                    $coordenadas = utility::obtenerCoordenadas('', $estado);
                }
                if(count($coordenadas) > 0){
                    echo "Coordenadas encontradas: " . $coordenadas['lat'] . " " . $coordenadas['lon'] . "\n";
                }
                $Colonia = colonia::where('codigo_postal', $fila["codigo_postal_contacto"])->first();
                if($Colonia || count($coordenadas) > 0){
                    $domicilio = domicilio::create([
                        'colonia_id' => $Colonia ? $Colonia->id : null,
                        'latitud' => count($coordenadas) > 0 ? $coordenadas['lat'] : null,
                        'longitud' => count($coordenadas) > 0 ? $coordenadas['lon'] : null,
                    ]);
                    personaDomicilio::create([
                        'domicilio_id' => $domicilio->id,
                        'persona_id' => $persona->id,
                    ]);
                }
                correo::create([
                    'correo' => $fila["correo"],
                    'etiqueta' => 'correo principal',
                    'principal' => true,
                    'persona_id' => $persona->id,
                ]);
                telefono::create([
                    'telefono' => $fila["telefono_oficina"],
                    'etiqueta'=> 'telefono de oficina',
                    'principal' => true,
                    'persona_id' => $persona->id,
                ]);
                echo "Persona creada: " . $persona->id . "\n";
            }
        }
    }
}
