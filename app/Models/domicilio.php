<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class domicilio extends Model
{
    use HasFactory;
    protected $fillable = [
        'calle1',
        'calle2',
        'calle3',
        'numero_exterior',
        'numero_interior',
        'latitud',
        'longitud',
        'colonia_id',
        'referencia',
        'rfc'
    ];
    public function identificacion(){
        return $this->belongsTo(identificacion::class);
    }

    public function relacionPersona(){
        return $this->hasMany(personaDomicilio::class);
    }

    public function colonia(){
        return $this->belongsTo(colonia::class);
    }

    public function relacionDomicilio(){
        return $this->hasMany(empresaDomicilio::class);
    }
    public static function crear($response){
        $coordenadas = explode(',',$response['coordenadas']);
        $domicilio = new domicilio();
        $domicilio->calle1 = trim(strtoupper($response['calle1']));
        $domicilio->calle2 = trim(strtoupper($response['calle2']));
        $domicilio->calle3 = trim(strtoupper($response['calle3']));
        $domicilio->numero_exterior = $response['numeroExterior'];
        $domicilio->numero_interior = $response['numeroInterior'];
        $domicilio->referencia = trim(strtoupper($response['referencia']));
        if($response['colonia'] > 0){
            $domicilio->colonia_id = $response['colonia'];
        }
        $domicilio->identificacion_id = $response['idIdentificacion'];
        if(isset($coordenadas) && count($coordenadas) > 1){
            $domicilio->latitud = $coordenadas[0];
            $domicilio->longitud = $coordenadas[1];
        }
        $domicilio->save();
        return $domicilio;
    }

    public static function modificar($response, $domicilio){
        $coordenadas = explode(',',$response['coordenadas']);
        $domicilio->calle1 = trim(strtoupper($response['calle1']));
        $domicilio->calle2 = trim(strtoupper($response['calle2']));
        $domicilio->calle3 = trim(strtoupper($response['calle3']));
        $domicilio->numero_exterior = $response['numeroExterior'];
        $domicilio->numero_interior = $response['numeroInterior'];
        $domicilio->referencia = trim(strtoupper($response['referencia']));
        if($response['colonia'] > 0){
            $domicilio->colonia_id = $response['colonia'];
        }
        $domicilio->identificacion_id = $response['idIdentificacion'];
        if(isset($coordenadas) && count($coordenadas) > 1){
            $domicilio->latitud = $coordenadas[0];
            $domicilio->longitud = $coordenadas[1];
        }
        $domicilio->save();
        return $domicilio;
    }
}
