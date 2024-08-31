<?php

namespace App\Http\Controllers;

use App\Models\colonia;
use App\Models\empresa;
use App\Models\persona;
use App\Models\RelacionPersonaEmpresa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmpresaController extends Controller
{
    function index(){
        $listaEmpresa = empresa::with(['domicilio', 'representante'])->select('id', 'nombreEmpresa', 'persona_id')->get();
        $listaPersonas = persona::where('deleted_at', null)
        ->select(
            'id',
            DB::raw("CONCAT(COALESCE(nombres, '') , ' ' , COALESCE(apellido_paterno, '') , ' ' , COALESCE(apellido_materno, '') , ', ' , COALESCE(apodo, '')) as NombreCompleto")
        )
        ->get();
        return view('Pages.empresa.index', compact('listaEmpresa', 'listaPersonas'));
    }
    function vistaAgregar(){
        $empresa = null;
        $urlFormulario = route('empresas.agregar');
        $listaPersonas = persona::where('deleted_at', null)->get();
        $listaColonias = colonia::with('seccionColonia.seccion.distritoLocal.municipio')
        ->select(
            'id',
            'nombre'
        )
        ->get();
        return view('Pages.empresa.formulario', compact('listaPersonas', 'listaColonias', 'urlFormulario', 'empresa'));
    }

    function crear(Request $request){
        $datos = collect($request->except('_token'));
        try {
            DB::transaction(function() use($datos){
                if($datos['persona_id'] == 0)
                    $datos = $datos->merge([
                        'persona_id' => null
                    ]);
                if($datos['colonia_id'] == 0)
                    $datos = $datos->merge([
                        'colonia_id' => null
                    ]);
                empresa::crear($datos->all());
            });
            session()->flash('mensajeExito', 'Se ha creado una empresa exitosamente');
            return redirect()->route('empresas.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            session()->flash('mensajeError', 'Verifique los campos del formulario');
            return back()->withInput();
        }
    }

    function vistaModificar(empresa $empresa){
        $empresa = empresa::with('domicilio')->find($empresa->id);
        $urlFormulario = route('empresas.modificar', $empresa->id);
        $listaPersonas = persona::where('deleted_at', null)->get();
        $listaColonias = colonia::with('seccionColonia.seccion.distritoLocal.municipio')
        ->select(
            'id',
            'nombre'
        )
        ->get();
        return view('Pages.empresa.formulario', compact('listaPersonas', 'listaColonias', 'empresa', 'urlFormulario'));
    }

    function modificar(empresa $empresa, Request $request){
        $datos = collect($request->except('_token'));
        try {
            DB::transaction(function() use($datos, $empresa){
                if($datos['persona_id'] == 0)
                    $datos = $datos->merge([
                        'persona_id' => null
                    ]);
                if($datos['colonia_id'] == 0)
                    $datos = $datos->merge([
                        'colonia_id' => null
                    ]);
                    empresa::modificar($datos->all(), $empresa);
            });
            session()->flash('mensajeExito', 'Se ha modificado una empresa exitosamente');
            return redirect()->route('empresas.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            session()->flash('mensajeError', 'Verifique los campos del formulario');
            return back()->withInput();
        }
    }

    function borrar(empresa $empresa){
        try {
            DB::transaction(function() use($empresa){
                empresa::borrar($empresa);
            });
            session()->flash('mensajeExito', 'Se ha borrado una empresa exitosamente');
            return redirect()->route('empresas.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            session()->flash('mensajeError', 'Verifique los campos del formulario');
            return back()->withInput();
        }
    }

    function cargarContactosAsignados(Request $request, $empresa){
        return RelacionPersonaEmpresa::with('personas')->where('empresa_id', $empresa)->get();
    }

    function guardarContactosAsignados(Request $request, $empresa){
        try {
            DB::transaction(function() use($request, $empresa){
                foreach ($request->Relaciones as $relacion) {
                    $datos = array_merge($relacion, ["empresa_id" => $empresa]);
                    RelacionPersonaEmpresa::agregarNuevaRelacion($datos);
                }
            });
            session()->flash('mensajeExito', 'Se creo relaciones entre las personas de forma éxitosa');
            return redirect()->route('empresas.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            session()->flash('mensajeError', 'Verifique los campos del formulario al asignar');
            return back()->withInput();
        }

    }
}
