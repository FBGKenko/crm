<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelacionPersonaEmpresa extends Model
{
    use HasFactory;
    protected $fillable = [
        'persona_id',
        'empresa_id',
        'esCliente',
        'esPromotor',
        'esColaborador',
        'puesto'
    ];
    public function personas(){
        return $this->belongsTo(persona::class, 'persona_id');
    }

    static function agregarNuevaRelacion($datos){
        $encontrarRelacion = RelacionPersonaEmpresa::where('persona_id', $datos['persona_id'])
            ->where('empresa_id', $datos['empresa_id'])
            ->first();
        if(!$encontrarRelacion){
            RelacionPersonaEmpresa::create($datos);
        }
        else{
            $encontrarRelacion->update($datos);
        }
    }


}
