<?php

namespace App\Http\Controllers\Contacto;

use App\Http\Controllers\Controller;
use App\Models\bitacora;
use App\Models\ciudad;
use App\Models\colonia;
use App\Models\domicilio;
use App\Models\estatus;
use App\Models\identificacion;
use App\Models\localidad;
use App\Models\municipio;
use App\Models\persona;
use App\Models\relacionPerfilUsuario;
use App\Models\RelacionPersonaEmpresa;
use DragonCode\Contracts\Cashier\Auth\Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class personaController extends Controller
{
    function index(){
        $query = persona::where('deleted_at', null)
        ->select(
            'personas.id',
            'apodo',
            DB::raw('IF(apellido_paterno != "", CONCAT(nombres, " ", apellido_paterno), nombres) as nombre_completo'),
            'telefonoCelular1',
            'supervisado',
        );

        $usuarioActual = auth()->user();
        if($usuarioActual->getRoleNames()->first() != 'SUPER ADMINISTRADOR' && $usuarioActual->getRoleNames()->first() != 'ADMINISTRADOR'){
            $modelosAsociados = relacionPerfilUsuario::join('perfils', 'perfils.id', '=', 'relacion_perfil_usuarios.perfil_id')
            ->join('perfil_modelo_relacionados', 'perfil_modelo_relacionados.perfil_id', '=', 'perfils.id')
            ->where([
                ['user_id', '=', $usuarioActual->id],
                ['modelo', '=', 'App\Models\persona']
            ])
            ->distinct()
            ->select(
                'perfil_modelo_relacionados.modelo',
                'perfil_modelo_relacionados.idAsociado',
            )
            ->get();

            $query->where(function($consulta) use ($modelosAsociados) {
                foreach ($modelosAsociados as $modelo) {
                    $consulta->orWhere('personas.id', $modelo->idAsociado);
                }
            });
        }

        $personas = $query->get();
        return view('Pages.contactos.index', compact('personas'));
    }

    function cargarEmpresasAsignadas(Request $request, $idPersona){
        return RelacionPersonaEmpresa::where('persona_id', $idPersona)->get();
    }

    function gardarEmpresasAsignadas(Request $request, $idPersona){

    }

    function vistaAgregar(){
        $listaPersonas = persona::where('deleted_at', null)->get();
        $listaPromotores = persona::where('deleted_at', null)->where('promotor','SI')->get();
        $listaEstatus = estatus::get();
        $listaColonias = colonia::with('seccionColonia.seccion.distritoLocal.municipio')->get();
        $listaMunicipios = municipio::get();
        return view('Pages.contactos.formulario', compact('listaPersonas', 'listaPromotores', 'listaColonias', 'listaMunicipios', 'listaEstatus'));
    }

    function agregar(Request $request){
        try{
            DB::beginTransaction();
            $datos = [
                'user_id' => Auth()->id(),
                'fecha_registro' => $request->input('fechaRegistro'),
                'folio' => trim($request->input('folio')),
                'persona_id' => ($request->input('promotor') != 0) ? $request->input('promotor') : null,
                'origen' => null,
                'referenciaOrigen' => ($request->input('referenciaOrigen') != 0) ? $request->input('referenciaOrigen') : null,
                'referenciaCampania' => null,
                'etiquetasOrigen' => null,
                'estatus' => ($request->input('estatus') != 0) ? $request->input('estatus') : null,
                'apodo' => trim(strtoupper($request->input('apodo'))),
                'nombres' => trim(strtoupper($request->input('nombres'))),
                'apellido_paterno' => trim(strtoupper($request->input('apellidoPaterno'))),
                'apellido_materno' => trim(strtoupper($request->input('apellidoMaterno'))),
                'genero' => ($request->input('genero') != 0) ? $request->input('genero') : null,
                'fecha_nacimiento' => $request->input('fechaNacimiento'),
                'edadPromedio' => ($request->input('rangoEdad') != 0) ? $request->input('rangoEdad') : null,
                'telefonoCelular1' => trim($request->input('telefonoCelular1')),
                'telefonoCelular2' => trim($request->input('telefonoCelular2')),
                'telefonoCelular3' => trim($request->input('telefonoCelular3')),
                'telefono_fijo' => trim($request->input('telefonoFijo')),
                'correo' => trim(strtoupper($request->input('correo'))),
                'correoAlternativo' => trim(strtoupper($request->input('correoAlternativo'))),
                'nombre_en_facebook' => trim(strtoupper($request->input('nombreFacebook'))),
                'twitter' => trim(strtoupper($request->input('twitter'))),
                'instagram' => trim(strtoupper($request->input('instagram'))),
                'observaciones' => trim(strtoupper($request->input('observaciones'))),
                'etiquetas' => $request->input('etiquetas'),
                'supervisado' => 0,
                'tipo' => "SIN DEFINIR",
                'cliente' => ($request->input('cliente') != 0) ? $request->input('cliente') : null,
                'promotor' => ($request->input('promotorEstructura') != 0) ? $request->input('promotorEstructura') : null,
                'colaborador' => ($request->input('colaborador') != 0) ? $request->input('colaborador') : null,
                'campoPersonalizado' => NULL,
            ];
            $persona = persona::crear($datos);
            $datos = [
                'idPersona' => $persona->id,
                'curp' => $request->input('curp'),
                'claveElectoral' => $request->input('claveElectoral'),
                'lugarNacimiento' => $request->input('lugarNacimiento'),
            ];
            $identificacion = identificacion::crear($datos);
            $datos = [
                'coordenadas' => $request->input('coordenadas'),
                'calle1' => $request->input('calle1'),
                'calle2' => $request->input('calle2'),
                'calle3' => $request->input('calle3'),
                'numeroExterior' => $request->input('numeroExterior'),
                'numeroInterior' => $request->input('numeroInterior'),
                'referencia' => $request->input('referencia'),
                'colonia' => $request->input('colonia'),
                'idIdentificacion' => $identificacion->id,
            ];
            $domicilio = domicilio::crear($datos);

            session()->flash('mensajeExito', 'Se ha agregado la persona con éxito');
            bitacora::crearRegistro('Se ha agregado la persona con éxito', $request->ip(), 'ÉXITO');
            DB::commit();
            return redirect()->route('contactos.index');
        }
        catch(Exception $e){
            DB::rollBack();
            bitacora::crearRegistro($e->getLine(). ' :: ' .$e->getMessage(), $request->ip(), 'ERROR');
            session()->flash('mensajeError', 'Ocurrió un error al intentar agregar una persona');
            return back()->withInput();
        }
    }

    function vistaModificar(persona $persona, Request $request){
        $registro = persona::with('identificacion.domicilio.colonia')
        ->where('personas.id', $persona->id)
        ->first();
        $listaPersonas = persona::where('deleted_at', null)->get();
        $listaPromotores = persona::where('deleted_at', null)->where('promotor','SI')->get();
        $listaEstatus = estatus::get();
        $listaColonias = colonia::with('seccionColonia.seccion.distritoLocal.municipio')->get();

        $listaMunicipios = municipio::get();
        return view('Pages.contactos.formulario', [
            'listaPersonas' => $listaPersonas,
            'persona' => $registro,
            'listaPromotores' => $listaPromotores,
            'listaColonias' => $listaColonias,
            'listaMunicipios' => $listaMunicipios,
            'listaEstatus' => $listaEstatus,
        ]);
    }

    function modificar(persona $persona, Request $request){
        try{
            DB::beginTransaction();

            $datos = [
                'user_id' => $persona->idUsuario,
                'fecha_registro' => $request->input('fechaRegistro'),
                'folio' => trim($request->input('folio')),
                'persona_id' => ($request->input('promotor') != 0) ? $request->input('promotor') : null,
                'origen' => null,
                'referenciaOrigen' => ($request->input('referenciaOrigen') != 0) ? $request->input('referenciaOrigen') : null,
                'referenciaCampania' => null,
                'etiquetasOrigen' => null,
                'estatus' => ($request->input('estatus') != 0) ? $request->input('estatus') : null,
                'apodo' => trim(strtoupper($request->input('apodo'))),
                'nombres' => trim(strtoupper($request->input('nombres'))),
                'apellido_paterno' => trim(strtoupper($request->input('apellidoPaterno'))),
                'apellido_materno' => trim(strtoupper($request->input('apellidoMaterno'))),
                'genero' => ($request->input('genero') != 0) ? $request->input('genero') : null,
                'fecha_nacimiento' => $request->input('fechaNacimiento'),
                'edadPromedio' => ($request->input('rangoEdad') != 0) ? $request->input('rangoEdad') : null,
                'telefonoCelular1' => trim($request->input('telefonoCelular1')),
                'telefonoCelular2' => trim($request->input('telefonoCelular2')),
                'telefonoCelular3' => trim($request->input('telefonoCelular3')),
                'telefono_fijo' => trim($request->input('telefonoFijo')),
                'correo' => trim(strtoupper($request->input('correo'))),
                'correoAlternativo' => trim(strtoupper($request->input('correoAlternativo'))),
                'nombre_en_facebook' => trim(strtoupper($request->input('nombreFacebook'))),
                'twitter' => trim(strtoupper($request->input('twitter'))),
                'instagram' => trim(strtoupper($request->input('instagram'))),
                'observaciones' => trim(strtoupper($request->input('observaciones'))),
                'etiquetas' => $request->input('etiquetas'),
                'supervisado' => 0,
                'tipo' => "SIN DEFINIR",
                'cliente' => ($request->input('cliente') != 0) ? $request->input('cliente') : null,
                'promotor' => ($request->input('promotorEstructura') != 0) ? $request->input('promotorEstructura') : null,
                'colaborador' => ($request->input('colaborador') != 0) ? $request->input('colaborador') : null,
                'campoPersonalizado' => NULL,
            ];
            persona::modificar($datos, $persona);

            $datos = [
                'idPersona' => $persona->id,
                'curp' => $request->input('curp'),
                'claveElectoral' => $request->input('claveElectoral'),
                'lugarNacimiento' => $request->input('lugarNacimiento'),
            ];
            $identificacion = identificacion::find($persona->identificacion->id);
            identificacion::modificar($datos, $identificacion);

            $datos = [
                'coordenadas' => $request->input('coordenadas'),
                'calle1' => $request->input('calle1'),
                'calle2' => $request->input('calle2'),
                'calle3' => $request->input('calle3'),
                'numeroExterior' => $request->input('numeroExterior'),
                'numeroInterior' => $request->input('numeroInterior'),
                'referencia' => $request->input('referencia'),
                'colonia' => $request->input('colonia'),
                'idIdentificacion' => $identificacion->id,
            ];
            $domicilio = domicilio::find($identificacion->domicilio->id);
            domicilio::modificar($datos, $domicilio);

            session()->flash('mensajeExito', 'Se ha modificado la persona con éxito');
            bitacora::crearRegistro('Se ha modificado la persona con éxito', $request->ip(), 'ÉXITO');
            DB::commit();
            return redirect()->route('contactos.index');
        }
        catch(Exception $e){
            DB::rollBack();
            bitacora::crearRegistro($e->getLine(). ' :: ' .$e->getMessage(), $request->ip(), 'ERROR');
            session()->flash('mensajeError', 'Ocurrió un error al intentar modificar una persona');
            return back()->withInput();
        }
    }

    function vistaVer(persona $persona, Request $request){
        $registro = persona::with('identificacion.domicilio.colonia')
        ->where('personas.id', $persona->id)
        ->first();
        return view('Pages.contactos.consultar', [
            'persona' => $registro,
        ]);
    }

    function borrar(persona $persona, Request $request){
        try{
            DB::beginTransaction();

            $persona = persona::eliminarLogico($persona);

            session()->flash('mensajeExito', 'Se ha borrado la persona con éxito');
            bitacora::crearRegistro('Se ha borrado la persona con éxito', $request->ip(), 'ÉXITO');
            DB::commit();
            return redirect()->route('contactos.index');
        }
        catch(Exception $e){
            DB::rollBack();
            bitacora::crearRegistro($e->getLine(). ' :: ' .$e->getMessage(), $request->ip(), 'ERROR');
            session()->flash('mensajeError', 'Ocurrió un error al intentar borrar una persona');
            return back()->withInput();
        }
    }

    function supervisar(persona $persona, Request $request){
        try{
            DB::beginTransaction();

            $bandera = persona::supervisar($persona);
            if($bandera){
                session()->flash('mensajeExito', 'Se ha autorizado la persona con éxito');
                bitacora::crearRegistro('Se ha autorizado la persona con éxito', $request->ip(), 'ÉXITO');
            }
            else{
                session()->flash('mensajeExito', 'Se ha vuleto no supervizado la persona con éxito');
                bitacora::crearRegistro('Se ha autorizado la persona con éxito', $request->ip(), 'ÉXITO');
            }

            DB::commit();
            return redirect()->route('contactos.index');
        }
        catch(Exception $e){
            DB::rollBack();
            bitacora::crearRegistro($e->getLine(). ' :: ' .$e->getMessage(), $request->ip(), 'ERROR');
            session()->flash('mensajeError', 'Ocurrió un error al intentar supervisar una persona');
            return back()->withInput();
        }
    }

    function fichaTecnica(persona $persona, Request $request){
        $registro = persona::with('identificacion.domicilio.colonia.seccionColonia.seccion.distritoLocal.municipio.distritoFederal.entidad')
        ->where('personas.id', $persona->id)
        ->first();
        return view('Pages.contactos.ficha', [
            'persona' => $registro,
        ]);
    }
}

