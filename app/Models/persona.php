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
        'supervisado',
        'fecha_registro',
        'folio',
        'promotor_id',
        'origen',
        'identificadorOrigen',
        'referenciaOrigen',
        'referenciaCampania',
        'etiquetasOrigen',
        'estatus',
        'apodo',
        'apellido_paterno',
        'apellido_materno',
        'nombres',
        'genero',
        'fecha_nacimiento',
        'rangoEdad',
        'nombre_en_facebook',
        'twitter',
        'instagram',
        'afiliado',
        'simpatizante',
        'programa',
        'cliente',
        'promotor',
        'colaborador',
        'etiquetas',
        'observaciones',
        'rolEstructura',
        'coordinadorDe',
        'funcionAsignada',
    ];
    public function identificacion(){
        return $this->hasOne(identificacion::class);
    }
    public function empresa(){
        return $this->hasMany(empresa::class, 'persona_id');
    }

    public function telefonos(){
        return $this->hasMany(telefono::class);
    }

    public function correos(){
        return $this->hasMany(correo::class);
    }

    public function relacionDomicilio(){
        return $this->hasMany(personaDomicilio::class);
    }

    public function relacionPersonaEmpresa(){
        return $this->hasMany(RelacionPersonaEmpresa::class, 'persona_id');
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
