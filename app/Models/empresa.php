<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class empresa extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombreEmpresa',
        'telefono1',
        'telefono2',
        'telefono3',
        'correo1',
        'correo2',
        'correo3',
        'paginaWeb',
        'persona_id'
    ];

    public function relacionDomicilio(){
        return $this->hasMany(empresaDomicilio::class);
    }
    public function representante(){
        return $this->belongsTo(persona::class, 'persona_id');
    }
    public function relacionEmpresaPersonas(){
        return $this->hasMany(RelacionPersonaEmpresa::class);
    }

    public static function crear($datos){
        $empresa = empresa::create($datos);
        $datosDomicilio = [
            'calle1' => $datos['calle1'],
            'calle2' => $datos['calle2'],
            'calle3' => $datos['calle3'],
            'numero_exterior' => $datos['numero_exterior'],
            'numero_interior' => $datos['numero_interior'],
            'colonia_id' => $datos['colonia_id'],
            //'identificacion_id' => null,
            'referencia' => $datos['referencia'],
            'latitud' => $datos['latitud'],
            'longitud' => $datos['longitud'],
        ];
        $domicilio = domicilio::create($datosDomicilio);
        empresaDomicilio::create([
            'empresa_id' => $empresa->id,
            'domicilio_id' => $domicilio->id
        ]);
    }
    public static function modificar($datos, $empresa){
        $empresa->update($datos);
        $datosDomicilio = [
            'calle1' => $datos['calle1'],
            'calle2' => $datos['calle2'],
            'calle3' => $datos['calle3'],
            'numero_exterior' => $datos['numero_exterior'],
            'numero_interior' => $datos['numero_interior'],
            // 'latitud' => null,
            // 'longitud' => null,
            'colonia_id' => $datos['colonia_id'],
            //'empresa_id' => $empresa->id,
            'referencia' => $datos['referencia'],
            'latitud' => $datos['latitud'],
            'longitud' => $datos['longitud'],
        ];
        $relacion = empresaDomicilio::where('empresa_id', $empresa->id)->first();
        domicilio::where('id', $relacion->domicilio_id)->update($datosDomicilio);
    }

    public static function borrar($empresa){

    }
}
