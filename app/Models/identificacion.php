<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class identificacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'persona_id',
        'curp',
        'rfc',
        'ine',
        'lugarNacimiento',
        'clave_elector',
        'seccion_id',
    ];
    public function persona(){
        return $this->belongsTo(persona::class);
    }

    public function domicilio(){
        return $this->hasOne(domicilio::class);
    }
    public function seccion(){
        return $this->belongsTo(seccion::class);
    }
    public static function crear($response){
        $identificacion = new identificacion();
        $identificacion->persona_id = $response['idPersona'];
        $identificacion->curp = trim(strtoupper($response['curp']));
        $identificacion->clave_elector = trim(strtoupper($response['claveElectoral']));
        $identificacion->lugarNacimiento = trim(strtoupper($response['lugarNacimiento']));
        // if($response['seccion'] > 0){
        //     $identificacion->seccion_id = $response['seccion'];
        // }
        $identificacion->save();
        return $identificacion;
    }

    public static function modificar($response, $identificacion){
        $identificacion->persona_id = $response['idPersona'];
        $identificacion->curp = trim(strtoupper($response['curp']));
        $identificacion->clave_elector = trim(strtoupper($response['claveElectoral']));
        $identificacion->lugarNacimiento = trim(strtoupper($response['lugarNacimiento']));
        // if($response['seccion'] > 0){
        //     $identificacion->seccion_id = $response['seccion'];
        // }
        $identificacion->save();
        return $identificacion;
    }
}
