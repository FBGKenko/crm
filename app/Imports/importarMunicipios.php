<?php

namespace App\Imports;

use App\Models\colonia;
use App\Models\distritoFederal;
use App\Models\distritoLocal;
use App\Models\entidad;
use App\Models\localidad;
use App\Models\municipio;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class importarMunicipios implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        $entidadPadre = entidad::where('id', strtoupper($row["c_estado"]))->first();
        if($entidadPadre){
            $municipio = municipio::where('entidad_id', $entidadPadre->id)
            ->where('nombre', strtoupper($row["d_mnpio"]))
            ->first();
            if(!isset($municipio)){
                $municipio = municipio::create([
                    'nombre' => strtoupper($row['d_mnpio']),
                    'entidad_id' => $entidadPadre->id
                ]);
            }
            $ciudad = localidad::where('municipio_id', $municipio->id)
            ->where('nombre', strtoupper($row["d_ciudad"]))
            ->first();
            if(!isset($ciudad)){
                $ciudad = localidad::create([
                    'nombre' => strtoupper($row["d_ciudad"]),
                    'municipio_id' => $municipio->id,
                ]);
            }
            $colonia = colonia::where('nombre', strtoupper($row["d_asenta"]))
            ->where('localidad_id', $ciudad->id)
            ->first();
            if(!isset($colonia)){
                $colonia = colonia::create([
                    'nombre' => strtoupper($row["d_asenta"]),
                    'codigo_postal' => $row["d_codigo"],
                    'tipo' => $row['d_tipo_asenta'],
                    'localidad_id' => $ciudad->id,
                ]);
            }
        }
    }
    public function chunkSize(): int
    {
        return 500;
    }
}
