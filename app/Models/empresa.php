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

    public function domicilio(){
        return $this->hasOne(domicilio::class);
    }

    public function representante(){
        return $this->belongsTo(persona::class, 'persona_id');
    }

    public static function crear($datos){
        $empresa = empresa::create($datos);
        $datosDomicilio = [
            'calle1' => $datos['calle1'],
            'calle2' => $datos['calle2'],
            'calle3' => $datos['calle3'],
            'numero_exterior' => $datos['numero_exterior'],
            'numero_interior' => $datos['numero_interior'],
            'latitud' => null,
            'longitud' => null,
            'colonia_id' => $datos['colonia_id'],
            'identificacion_id' => null,
            'empresa_id' => $empresa->id,
            'referencia' => $datos['referencia'],
        ];
        domicilio::create($datosDomicilio);
    }
    public static function modificar($datos, $empresa){
        $empresa->update($datos);
        $datosDomicilio = [
            'calle1' => $datos['calle1'],
            'calle2' => $datos['calle2'],
            'calle3' => $datos['calle3'],
            'numero_exterior' => $datos['numero_exterior'],
            'numero_interior' => $datos['numero_interior'],
            'latitud' => null,
            'longitud' => null,
            'colonia_id' => $datos['colonia_id'],
            'identificacion_id' => null,
            'empresa_id' => $empresa->id,
            'referencia' => $datos['referencia'],
        ];
        $domicilio = domicilio::where('empresa_id', $empresa->id)->first();
        $domicilio->update($datosDomicilio);
    }

    public static function borrar($empresa){
        $empresa->deleted_at = Carbon::now();
        $empresa->save();
    }
}
