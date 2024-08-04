<?php

namespace App\Models;

use DragonCode\Contracts\Cashier\Auth\Auth;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class bitacora extends Model
{
    use HasFactory;
    public function user(){
        return $this->belongsTo(User::class);
    }

    public static function crearRegistro($accion, $ip, $tipo){
        $bitacora = new bitacora();
        $bitacora->accion = $accion;
        $bitacora->url = url()->current();
        $bitacora->ip = $ip;
        $bitacora->tipo = $tipo;
        $bitacora->user_id = Auth()->id();
        $bitacora->save();
        DB::commit();
    }
}
