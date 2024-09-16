<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class permisosController extends Controller
{
    function index(){
        
        return view('Pages.configuracion.gestorPermisos');
    }
}
