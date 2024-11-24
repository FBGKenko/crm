<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\configuracion;
use Illuminate\Http\Request;

class personalizarController extends Controller
{
    public function index(){
        $rutaLogo = asset('img/logotipo.png');
        $nombreEmpresa = configuracion::first()->nombreEmpresa;
        return view('Pages.configuracion.personalizacion', compact('rutaLogo', 'nombreEmpresa'));
    }

    public function configurar(Request $request){
            $mensajeSalida = 'Se ha guardado con éxito la personalización';
        if ($request->hasFile('imagenesProyecto')) {
            $newFile = $request->file('imagenesProyecto');
            $newFileName = 'logotipo.png';
            $newFile->move(public_path('img'), $newFileName);
            $mensajeSalida .= '. La actualización del logotipo puede tardar en cambiar';
        }
        $configuracion = configuracion::first();
        $configuracion->nombreEmpresa = $request->nombreEmpresaPropietaria;
        $configuracion->save();
        session()->flash('mensajeExito', $mensajeSalida);
        return redirect()->route('personalizar.index');
    }
}
