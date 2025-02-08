<?php

namespace App\Http\Controllers;

use App\Models\sitioIntegracion;
use Exception;
use Illuminate\Http\Request;

class integracionesController extends Controller
{
    public function index()
    {
        $sitios = sitioIntegracion::all();
        return view('pages.integraciones', compact('sitios'));
    }

    public function crear(Request $form)
    {
        $resultado = [
            'resultado' => false,
            'mensaje' => 'Error al crear el sitio de integración',
            'sitio' => null
        ];
        try {
            $sitioIntegracion = new \App\Models\sitioIntegracion();
            $sitioIntegracion->nombre = $form->nombreSitioIntegracion;
            $sitioIntegracion->url = $form->urlSitio;
            $sitioIntegracion->save();
            $resultado['resultado'] = true;
            $resultado['mensaje'] = 'Sitio de integración creado correctamente';
            $resultado['sitio'] = $sitioIntegracion;
        } catch (Exception $e) {

        }
        return $resultado;
    }

    public function borrar(Request $form, sitioIntegracion $integracion){
        $integracion->delete();
        return [
            'resultado' => true,
            'mensaje' => 'Sitio de integración eliminado correctamente'
        ];
    }
}
