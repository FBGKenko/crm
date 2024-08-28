<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // function metodos(persona $persona, Request $request){
    //     try{
    //         DB::beginTransaction();

    //         //codigo a realizar

    //         DB::commit();
    //         return redirect()->route('contactos.index');
    //     }
    //     catch(Exception $e){
    //         DB::rollBack();
    //         bitacora::crearRegistro($e->getLine(). ' :: ' .$e->getMessage(), $request->ip(), 'ERROR');
    //         session()->flash('mensajeError', 'Ocurrió un error al intentar supervisar una persona');
    //         return back()->withInput();
    //     }
    // }
}
