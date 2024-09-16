<?php

namespace App\Http\Controllers;

use App\Models\empresa;
use App\Models\Error;
use App\Models\Perfil;
use App\Models\perfilModeloRelacionado;
use App\Models\persona;
use App\Models\relacionPerfilUsuario;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class perfilUsuarioController extends Controller
{
    function index(User $usuario){
        $listaPerfil = Perfil::lista();
        $listaUsuarios = User::where('deleted_at', null)->get();
        $gruposRelacionados = Perfil::gruposAsignados($usuario->id);
        $listaEmpresas = empresa::where('deleted_at', null)
        ->select(
            'id',
            DB::raw('nombreEmpresa as nombre'),
        )
        ->get();

        $listaPersonas = persona::where('deleted_at', null)
        ->select(
            'id',
            DB::raw('CONCAT(nombres, apellido_paterno, apellido_materno, ",", apodo) as nombre'),
        )
        ->get();


        $listas = array( $listaEmpresas, $listaPersonas);
        $listaDeListas = array(
            (object) [
                'tipoLista' => 'App\Models\empresa',
                'lista' => 'LISTA DE EMPRESAS',
            ],
            (object) [
                'tipoLista' => 'App\Models\persona',
                'lista' => 'LISTA DE PERSONAS',
            ]

        );
        return view('Pages.configuracion.formularioPerfiles',
            compact('listaPerfil', 'listaUsuarios', 'gruposRelacionados', 'listas', 'listaDeListas', 'usuario'));
    }

    function manejarPerfil(User $usuario, Request $request){
        $datosParaAjax = [];
        try{
            DB::transaction(function () use($request, $usuario, &$datosParaAjax) {
                $grupo = Perfil::crearORenombrarPerfil($request->only('perfilesCreados', 'nombreGrupo'));
                // relacionPerfilUsuario::create([
                //     'user_id' => $usuario->id,
                //     'perfil_id' => $grupo->id,
                // ]);
                foreach ($request->contactos as $contacto) {
                    perfilModeloRelacionado::firstOrCreate([
                        'modelo' => $contacto["claseLista"],
                        'idAsociado' => $contacto["idModelo"],
                        'perfil_id' => $grupo->id,
                    ]);
                }
                $datosParaAjax = [
                    $grupo->id,
                    $grupo->nombre
                ];
            });
        }
        catch(Exception $e){
            Error::create([
                'descripcion' => $e->getMessage(),
                'codigo' => get_class($e),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
                'caminoRequest' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'respuesta' => false,
                'titulo' => 'Error',
                'mensaje' => 'Ocurrió un error al momento de guardar el grupo',
                'tipo' => 'error'
            ]);
        }
        return response()->json([
            'respuesta' => true,
            'titulo' => 'Éxito',
            'mensaje' => $request->perfilesCreados == 0 ? 'Grupo ha sido guardado con éxito' : 'Grupo ha sido guardado con éxito. Si cambio el nombre del grupo refresque la pagina',
            'tipo' => 'success',
            'nombrePerfil' => $datosParaAjax[1],
            'idPerfil' => $datosParaAjax[0],
            'idPerfilRequest' => $request->perfilesCreados,
        ]);
    }

    function buscarRelaciones(Request $request){
        $arreglo = Perfil::obtenerGrupoConRelaciones($request->only('idGrupo'));
        $arrayModelos = array();
        foreach ($arreglo->perfilesModelosRelacionados as $modeloAsociado) {
            $instanciaModelo = new $modeloAsociado->modelo;
            $modeloEncontrado = $instanciaModelo::find($modeloAsociado->idAsociado);
            switch ($modeloAsociado->modelo) {
                case 'App\\Models\\empresa':
                    array_push($arrayModelos, $modeloEncontrado->nombreEmpresa);
                    break;
                case 'App\\Models\\persona':
                    array_push($arrayModelos, "$modeloEncontrado->nombres $modeloEncontrado->apellido_paterno $modeloEncontrado->apellido_materno, $modeloEncontrado->apodo");
                    break;
                default:

                    break;
            }

        }
        return [
            $arreglo,
            $arrayModelos
        ];
    }

    function relacionarGruposConUsuarios(Request $request){
        $prevUrl = explode('/', url()->previous());
        try {
            DB::beginTransaction();
            foreach ($request->selectUsuarioSeleccionados as $usuario) {
                foreach ($request->selectGruposSeleccionados as $grupo) {
                    relacionPerfilUsuario::firstOrCreate([
                        'user_id' => $usuario,
                        'perfil_id' => $grupo,
                    ]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Error::create([
                'descripcion' => $e->getMessage(),
                'codigo' => get_class($e),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
                'caminoRequest' => $e->getTraceAsString(),
            ]);
            session()->flash('mensajeError', 'Ocurrió un error al momento de asignar los grupos');
            return back()->withInput();
        }
        session()->flash('mensajeExito', 'Se han asignado los grupos con éxito');
        return redirect()->route('perfil.index', $prevUrl[count($prevUrl) - 1]);
    }

    function borrarRelacion(Request $request, User $usuario){
        try{
            // DB::beginTransaction(function () use ($request, $usuario){
                relacionPerfilUsuario::where(
                    'perfil_id', $request->idPerfil,
                )
                ->where(
                    'user_id', $usuario->id
                )
                ->delete();
            // });
        }
        catch(Exception $e){
            Error::create([
                'descripcion' => $e->getMessage(),
                'codigo' => get_class($e),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
                'caminoRequest' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'respuesta' => false,
                'titulo' => 'Error',
                'mensaje' => 'Ocurrió un error al momento de borrar una relacion con grupos',
                'tipo' => 'error'
            ]);
        }
        return response()->json([
            'respuesta' => true,
            'titulo' => 'Éxito',
            'mensaje' => 'Se ha eliminado una relación con éxito',
            'tipo' => 'success',
            'idPerfilIngresado' => $request->idPerfil,
        ]);
    }
}
