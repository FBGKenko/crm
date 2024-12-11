<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class catalogosController extends Controller
{
    public function index(){
        return view('Pages.configuracion.catalogos');
    }

    public function obtenerCatalogo(Request $request){
        switch ($request->catalogoEscogido) {
            case 'Secciones':
                # code...
                break;
            case 'Distritos Locales':
                # code...
                break;
            case 'Municipios':
                # code...
                break;
            case 'Distritos Federales':
                # code...
                break;
            case 'Entidades':
                # code...
                break;
            case 'Colonias':
                # code...
                break;
            case 'Estatus':
                # code...
                break;
            case 'Tipo Funciones Personalizadas':
                # code...
                break;
            case 'Origenes':
                # code...
                break;
            case 'Estados':
                # code...
                break;
            default:
                # code...
                break;
        }
    }
}
