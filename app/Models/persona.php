<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class persona extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'fecha_registro',
        'folio',
        'persona_id',
        'origen',
        'referenciaOrigen',
        'referenciaCampania',
        'etiquetasOrigen',
        'estatus',
        'apodo',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'genero',
        'fecha_nacimiento',
        'edadPromedio',
        'telefonoCelular1',
        'telefonoCelular2',
        'telefonoCelular3',
        'telefono_fijo',
        'correo',
        'correoAlternativo',
        'nombre_en_facebook',
        'twitter',
        'instagram',
        'observaciones',
        'etiquetas',
        'supervisado',
        'tipo',
        'cliente',
        'promotor',
        'colaborador',
        'campoPersonalizado',
    ];
    public function identificacion(){
        return $this->hasOne(identificacion::class);
    }
    public function empresa(){
        return $this->hasMany(empresa::class, 'persona_id');
    }

    public function relacionPersonaEmpresa(){
        return $this->hasMany(relacionPersonaEmpresa::class, 'persona_id');
    }

    public static function crear($response){
        $personaNueva = persona::create($response);
        return $personaNueva;
    }

    public static function modificar($response, $persona){
        $persona->update($response);
        return $persona;
    }

    public static function cambiarEstatus($estatus, $persona){
        $persona->estatus = $estatus;
        $persona->save();
    }

    public static function eliminarLogico($persona){
        $persona->deleted_at = Carbon::now()->format('Y-m-d');
        $persona->save();
    }

    public static function supervisar($persona){
        $persona->supervisado = !$persona->supervisado;
        $persona->save();
        return $persona->supervisado;
    }
}
