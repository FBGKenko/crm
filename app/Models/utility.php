<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class utility extends Model
{
    use HasFactory;
    public static function obtenerCoordenadas($codigoPostal, $estado) {
        $codigoPostal = urlencode($codigoPostal);
        $estado = urlencode($estado);

        $url = "https://nominatim.openstreetmap.org/search?postalcode={$codigoPostal}&state={$estado}&country=Mexico&format=json&limit=1";

        $opciones = [
            "http" => [
                "header" => "User-Agent: LaravelApp"
            ]
        ];
        $contexto = stream_context_create($opciones);
        $respuesta = file_get_contents($url, false, $contexto);

        $datos = json_decode($respuesta, true);

        if (!empty($datos) && isset($datos[0]['lat']) && isset($datos[0]['lon'])) {
            return [
                'lat' => $datos[0]['lat'],
                'lon' => $datos[0]['lon']
            ];
        }

        return []; // No se encontraron coordenadas
    }

}
