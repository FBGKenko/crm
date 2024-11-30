<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\configuracion;
use Illuminate\Http\Request;

class personalizarController extends Controller
{
    public function index(){
        $rutaLogo = asset('img/logotipo.png');
        $rutaFondo = asset('Plantilla/assets/img/fondo.jpg');
        $nombreEmpresa = configuracion::first()->nombreEmpresa;
        return view('Pages.configuracion.personalizacion', compact('rutaLogo', 'nombreEmpresa', 'rutaFondo'));
    }

    public function configurar(Request $request){
        $mensajeSalida = 'Se ha guardado con éxito la personalización';
        if ($request->hasFile('imagenesProyecto')) {
            $newFile = $request->file('imagenesProyecto');
            $newFileName = 'logotipo.png';
            $newFile->move(public_path('img'), $newFileName);
            $mensajeSalida .= '. La actualización del logotipo puede tardar en cambiar';
        }
        if ($request->hasFile('fondoInicio')) {
            $newFile = $request->file('fondoInicio');
            $newFileName = 'fondo.jpg';
            $newFile->move(public_path('Plantilla/assets/img'), $newFileName);
        }
        $configuracion = configuracion::first();
        $configuracion->nombreEmpresa = $request->nombreEmpresaPropietaria;
        $configuracion->save();
        session()->flash('mensajeExito', $mensajeSalida);
        return redirect()->route('personalizar.index');
    }
}
