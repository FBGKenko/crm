<?php

namespace App\Imports;

use App\Models\distritoFederal;
use App\Models\distritoLocal;
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
        $distritoFederalPadre = distritoFederal::where('entidad_id', strtoupper($row["c_estado"]))->first();
        if($distritoFederalPadre){
            $municipio = municipio::where('distrito_federal_id', $distritoFederalPadre->id)
            ->where('nombre', strtoupper($row["d_mnpio"]))
            ->first();
            if(!isset($municipio)){
                municipio::create([
                    'nombre' => strtoupper($row['d_mnpio']),
                    'distrito_federal_id' => $distritoFederalPadre->id
                ]);
            }
        }
    }
    public function chunkSize(): int
    {
        return 500;
    }
}
