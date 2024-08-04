<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class persona extends Model
{
    use HasFactory;
    public function identificacion(){
        return $this->hasOne(identificacion::class);
    }

    public function promotor(){
        return $this->belongsTo(persona::class, 'persona_id');
    }
    public static function crear($response){
        $personaNueva = new persona();
        $personaNueva->user_id = $response['idUsuario'];
        $personaNueva->fecha_registro = $response['fechaRegistro'];
        $personaNueva->folio = $response['folio'];
        $personaNueva->persona_id = $response['idPromotor'];
        $personaNueva->origen = trim(strtoupper($response['origen']));
        $personaNueva->referenciaOrigen = trim(strtoupper($response['referenciaOrigen']));
        $personaNueva->referenciaCampania = trim(strtoupper($response['referenciaCampania']));
        $personaNueva->etiquetasOrigen = trim(strtoupper($response['etiquetasOrigen']));
        $personaNueva->apodo = trim(strtoupper($response['apodo']));
        $personaNueva->nombres = trim(strtoupper($response['nombres']));
        $personaNueva->apellido_paterno = trim(strtoupper($response['apellidoPaterno']));
        $personaNueva->apellido_materno = trim(strtoupper($response['apellidoMaterno']));
        $personaNueva->genero = trim(strtoupper($response['genero']));
        $personaNueva->fecha_nacimiento = $response['fechaNacimiento'];
        $personaNueva->edadPromedio = trim(strtoupper($response['rangoEdad']));
        $personaNueva->telefonoCelular1 = $response['telefonoCelular1'];
        $personaNueva->telefonoCelular2 = $response['telefonoCelular2'];
        $personaNueva->telefonoCelular3 = $response['telefonoCelular3'];
        $personaNueva->telefono_fijo = $response['telefonoFijo'];
        $personaNueva->correo = trim(strtoupper($response['correo']));
        $personaNueva->correoAlternativo = trim(strtoupper($response['correoAlternativo']));
        $personaNueva->nombre_en_facebook = trim(strtoupper($response['nombreFacebook']));
        $personaNueva->twitter = trim(strtoupper($response['twitter']));
        $personaNueva->instagram = trim(strtoupper($response['instagram']));
        $personaNueva->observaciones = trim(strtoupper($response['observaciones']));
        $personaNueva->etiquetas = $response['etiquetas'];
        $personaNueva->save();
        return $personaNueva;
    }

    public static function modificar($response, $persona){
        $persona->user_id = $response['idUsuario'];
        $persona->fecha_registro = $response['fechaRegistro'];
        $persona->folio = $response['folio'];
        $persona->persona_id = $response['idPromotor'];
        $persona->origen = trim(strtoupper($response['origen']));
        $persona->referenciaOrigen = trim(strtoupper($response['referenciaOrigen']));
        $persona->referenciaCampania = trim(strtoupper($response['referenciaCampania']));
        $persona->etiquetasOrigen = trim(strtoupper($response['etiquetasOrigen']));
        $persona->apodo = trim(strtoupper($response['apodo']));
        $persona->nombres = trim(strtoupper($response['nombres']));
        $persona->apellido_paterno = trim(strtoupper($response['apellidoPaterno']));
        $persona->apellido_materno = trim(strtoupper($response['apellidoMaterno']));
        $persona->genero = trim(strtoupper($response['genero']));
        $persona->fecha_nacimiento = $response['fechaNacimiento'];
        $persona->edadPromedio = trim(strtoupper($response['rangoEdad']));
        $persona->telefonoCelular1 = $response['telefonoCelular1'];
        $persona->telefonoCelular2 = $response['telefonoCelular2'];
        $persona->telefonoCelular3 = $response['telefonoCelular3'];
        $persona->telefono_fijo = $response['telefonoFijo'];
        $persona->correo = trim(strtoupper($response['correo']));
        $persona->correoAlternativo = trim(strtoupper($response['correoAlternativo']));
        $persona->nombre_en_facebook = trim(strtoupper($response['nombreFacebook']));
        $persona->twitter = trim(strtoupper($response['twitter']));
        $persona->instagram = trim(strtoupper($response['instagram']));
        $persona->observaciones = trim(strtoupper($response['observaciones']));
        $persona->etiquetas = $response['etiquetas'];
        $persona->save();
        return $persona;
    }

    public function cambiarEstatus($estatus, $persona){
        $persona->estatus = $estatus;
        $persona->save();
    }

    public function eliminarLogico($persona){
        $persona->deleted_at = Carbon::now()->format('Y-m-d');
        $persona->save();
    }
}
