<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class importarDatosController extends Controller
{
    public function index(){
        return view('Pages.configuracion.importarDatos');
    }
}
