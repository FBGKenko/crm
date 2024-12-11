<?php

namespace App\Http\Controllers;

use App\Models\colonia;
use App\Models\empresa;
use App\Models\persona;
use App\Models\relacionPerfilUsuario;
use App\Models\RelacionPersonaEmpresa;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmpresaController extends Controller
{
    function index(){
        $query = empresa::where('deleted_at', null)
        ->with(['representante'])
        ->select('id', 'nombreEmpresa', 'persona_id');

        $usuarioActual = auth()->user();
        if($usuarioActual->getRoleNames()->first() != 'SUPER ADMINISTRADOR' && $usuarioActual->getRoleNames()->first() != 'ADMINISTRADOR'){
            $modelosAsociados = relacionPerfilUsuario::join('perfils', 'perfils.id', '=', 'relacion_perfil_usuarios.perfil_id')
            ->join('perfil_modelo_relacionados', 'perfil_modelo_relacionados.perfil_id', '=', 'perfils.id')
            ->where([
                ['user_id', '=', $usuarioActual->id],
                ['modelo', '=', 'App\Models\empresa']
            ])
            ->distinct()
            ->select(
                'perfil_modelo_relacionados.modelo',
                'perfil_modelo_relacionados.idAsociado',
            )
            ->get();

            $query->where(function($consulta) use ($modelosAsociados) {
                foreach ($modelosAsociados as $modelo) {
                    $consulta->orWhere('empresas.id', $modelo->idAsociado);
                }
            });
        }

        $listaEmpresa = $query->get();

        $listaPersonas = persona::where('deleted_at', null)
        ->select(
            'id',
            DB::raw("CONCAT(COALESCE(nombres, '') , ' ' , COALESCE(apellido_paterno, '') , ' ' , COALESCE(apellido_materno, '') , ', ' , COALESCE(apodo, '')) as NombreCompleto")
        )
        ->get();
        return view('Pages.empresa.index', compact('listaEmpresa', 'listaPersonas'));
    }

    function cargarTabla(Request $formulario){

        $draw = ($formulario->get('draw') != null) ? $formulario->get('draw') : 1;
        $start = ($formulario->get('start') != null) ? $formulario->get('start') : 0;
        $length = ($formulario->get('length') != null) ? $formulario->get('length') : 10;
        $filter = $formulario->get('search');
        $search = (isset($filter['value']))? $filter['value'] : false;

        $query = empresa::where('empresas.deleted_at', null)->leftJoin('personas', 'empresas.persona_id', '=', 'personas.id')
        ->select(
            'empresas.id',
            'nombreEmpresa',
            'persona_id',
            DB::raw('CONCAT(nombres, " ", apellido_paterno) as nombreRepresentante')
        );

        $usuarioActual = auth()->user();
        if($usuarioActual->getRoleNames()->first() != 'SUPER ADMINISTRADOR' && $usuarioActual->getRoleNames()->first() != 'ADMINISTRADOR'){
            $modelosAsociados = relacionPerfilUsuario::join('perfils', 'perfils.id', '=', 'relacion_perfil_usuarios.perfil_id')
            ->join('perfil_modelo_relacionados', 'perfil_modelo_relacionados.perfil_id', '=', 'perfils.id')
            ->where([
                ['user_id', '=', $usuarioActual->id],
                ['modelo', '=', 'App\Models\empresa']
            ])
            ->distinct()
            ->select(
                'perfil_modelo_relacionados.modelo',
                'perfil_modelo_relacionados.idAsociado',
            )
            ->get();

            $query->where(function($consulta) use ($modelosAsociados) {
                foreach ($modelosAsociados as $modelo) {
                    $consulta->orWhere('empresas.id', $modelo->idAsociado);
                }
            });
        }

        //FILTRAR EN CASO DE QUE EL BUSCADOR TENGA UN VALOR SE FILTRARA
        // if ($search != false) {
        //     Bitacora::registrarBitacora(
        //         'Buscó un incidente que concida con: ' . $search,
        //         'GET',
        //         null,
        //         null,
        //         'BUSCAR'
        //     );
        //     $consulta->where(function($query) use ($search, $formulario) {
        //         $query->where(DB::raw("CONCAT(DATE_FORMAT(Incidentes.FechaInicio, '%d-%m-%Y'), ' ', DATE_FORMAT(Incidentes.Hora, '%H:%i'))"), 'LIKE', '%' . $search . '%')
        //         ->orWhere("Incidentes.Ticket", 'LIKE', '%' . $search . '%')
        //         ->orWhere(DB::raw("IFNULL(Incidentes.NumeroDenuncia, 'SIN REGISTRO')"), 'LIKE', '%' . $search . '%')
        //         ->orWhere("CatalogoIncidentes.Nombre", 'LIKE', '%' . $search . '%')
        //         ->orWhere("Incidentes.Riesgo", 'LIKE', '%' . $search . '%')
        //         ->orWhere("Dependencias.Nombre", 'LIKE', '%' . $search . '%')
        //         ->orWhere(DB::raw("IF(
        //             TRIM(CONCAT(IFNULL(Personas.Nombres, ''), ' ', IFNULL(Personas.PrimerApellido, ''), ' ', IFNULL(Personas.SegundoApellido, ''))) = '',
        //             'SIN NOMBRE',
        //             CONCAT(IFNULL(Personas.Nombres, ''), ' ', IFNULL(Personas.PrimerApellido, ''), ' ', IFNULL(Personas.SegundoApellido, ''))
        //         )"), 'LIKE', '%' . $search . '%');
        //     });
        // }

        $total = $query->count();
        $datos = $query->orderBy('empresas.id', 'desc')
        ->skip($start)
        ->take($length)
        ->get();

        return [
            'data' => $datos,
            'length' => $length,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'start' => $start,
            'draw' => $draw,
        ];

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
                if(strlen($datos['coordenadas']) > 0){
                    $arrayCoordenadas = explode(',', $datos['coordenadas']);
                    $datos = $datos->merge([
                        'latitud' => $arrayCoordenadas[0],
                        'longitud' => $arrayCoordenadas[1],
                    ]);
                }
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
        $empresa = empresa::with('relacionDomicilio.domicilio')->find($empresa->id);
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
                if(strlen($datos['coordenadas']) > 0){
                    $arrayCoordenadas = explode(',', $datos['coordenadas']);
                    $datos = $datos->merge([
                        'latitud' => $arrayCoordenadas[0],
                        'longitud' => $arrayCoordenadas[1],
                    ]);
                }
                empresa::modificar($datos->all(), $empresa);
            });
            session()->flash('mensajeExito', 'Se ha modificado una empresa exitosamente');
            return redirect()->route('empresas.index');
        } catch (Exception $e) {
            Log::error($e->getMessage() . '::' . $e->getLine());
            session()->flash('mensajeError', 'Verifique los campos del formulario');
            return back()->withInput();
        }
    }

    function borrar(empresa $empresa){
        try {
            DB::transaction(function() use($empresa){
                $idEmpresa = $empresa->id;
                $empresa->deleted_at = Carbon::now();
                $empresa->save();
                RelacionPersonaEmpresa::where('empresa_id', $idEmpresa)->delete();
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

    function verListaPersonas($empresa){
        $empresa = Empresa::with([
            'relacionEmpresaPersonas' => function ($query) {
                $query->select(
                    'id',
                    'persona_id',
                    'empresa_id',
                    'puesto',
                );
            },
            'relacionEmpresaPersonas.personas' => function ($query) {
                $query->select(
                    'id',
                    'apodo',
                    'nombres',
                    'apellido_paterno',
                    'apellido_materno',

                );
            }
        ])->select(
            'id',
            'nombreEmpresa'
        )
        ->find($empresa);
        return $empresa;
    }
}
