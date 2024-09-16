<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Perfil extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'tipo',
    ];

    public function perfilesModelosRelacionados(){
        return $this->hasMany(perfilModeloRelacionado::class);
    }

    public static function lista(){
        return Perfil::join('perfil_modelo_relacionados', 'perfils.id', '=', 'perfil_modelo_relacionados.perfil_id')
        ->groupBy(
            'perfil_id',
            'nombre',
        )
        ->select(
            'perfil_id',
            'nombre',
        )
        ->get();
    }

    public static function gruposAsignados($idUsuario){
        return Perfil::join('relacion_perfil_usuarios', 'perfils.id', '=',  'relacion_perfil_usuarios.perfil_id')
        ->where('user_id', $idUsuario)
        ->get();
    }

    public static function crearORenombrarPerfil($datos){
        $instanciaPerfil = Perfil::firstOrCreate([
            'id' => $datos['perfilesCreados']
        ]);
        $instanciaPerfil->nombre = $datos['nombreGrupo'];
        $instanciaPerfil->save();
        return $instanciaPerfil;
    }

    public static function obtenerGrupoConRelaciones($datos){

        return Perfil::with('perfilesModelosRelacionados')
        ->find($datos['idGrupo']);
    }
}
