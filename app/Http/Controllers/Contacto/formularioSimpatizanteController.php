<?php

namespace App\Http\Controllers\Contacto;

use App\Http\Controllers\Controller;
use App\Models\bitacora;
use App\Models\colonia;
use App\Models\distritoFederal;
use App\Models\distritoLocal;
use App\Models\domicilio;
use App\Models\entidad;
use App\Models\identificacion;
use App\Models\municipio;
use App\Models\persona;
use App\Models\seccion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class formularioSimpatizanteController extends Controller
{
    public function index(){
        return view('Pages.contactos.formularioSimpatizante');
    }

    public function inicializar(){
        try{
            $user = auth()->user();
            switch ($user->nivel_acceso) {
                case 'TODO':
                    //HACER CONSULTA SIN FILTROS
                    $seccionesParaBuscar = seccion::pluck('id')->toArray();

                    break;
                case 'ENTIDAD':
                    //HACER CONSULTA FILTRAR PERSONAS QUE SU IDENTIFICACION
                    //PERTENEZCA A LA LISTA DE SECCIONES PERTENECIENTES A LAS ENTIDADES SELECCIONADAS
                    $nivelesConAcceso = explode(',', $user->niveles);
                    $seccionesParaBuscar = entidad::whereIn('entidads.id', $nivelesConAcceso)
                    ->join('distrito_federals', 'entidads.id', '=','distrito_federals.entidad_id')
                    ->join('municipios', 'distrito_federals.id', '=','municipios.distrito_federal_id')
                    ->join('distrito_locals', 'municipios.id', '=','distrito_locals.municipio_id')
                    ->join('seccions', 'distrito_locals.id', '=','seccions.distrito_local_id')
                    ->pluck('seccions.id')
                    ->toArray();

                    break;
                case 'DISTRITO FEDERAL':
                    //HACER CONSULTA FILTRAR PERSONAS QUE SU IDENTIFICACION
                    //PERTENEZCA A LA LISTA DE SECCIONES PERTENECIENTES A LOS DISTRITOS FEDERALES SELECCIONADAS
                    $nivelesConAcceso = explode(',', $user->niveles);
                    $seccionesParaBuscar = distritoFederal::whereIn('distrito_federals.id', $nivelesConAcceso)
                    ->join('municipios', 'distrito_federals.id', '=','municipios.distrito_federal_id')
                    ->join('distrito_locals', 'municipios.id', '=','distrito_locals.municipio_id')
                    ->join('seccions', 'distrito_locals.id', '=','seccions.distrito_local_id')
                    ->pluck('seccions.id')
                    ->toArray();

                    break;
                case 'DISTRITO LOCAL':
                    //HACER CONSULTA FILTRAR PERSONAS QUE SU IDENTIFICACION
                    //PERTENEZCA A LA LISTA DE SECCIONES PERTENECIENTES A LOS DISTRITOS LOCALES SELECCIONADAS
                    $nivelesConAcceso = explode(',', $user->niveles);
                    $seccionesParaBuscar = distritoLocal::whereIn('distrito_locals.id', $nivelesConAcceso)
                    ->join('seccions', 'distrito_locals.id', '=','seccions.distrito_local_id')
                    ->pluck('seccions.id')
                    ->toArray();

                    break;
                case 'SECCION':
                    //HACER CONSULTA FILTRAR PERSONAS QUE SU IDENTIFICACION
                    //PERTENEZCA A LA LISTA DE SECCIONES PERTENECIENTES A LAS SECCIONES SELECCIONADAS
                    $seccionesParaBuscar = explode(',', $user->niveles);
                    $seccionesParaBuscar = array_map('intval', $seccionesParaBuscar);

                    break;
            }

            $colonias = colonia::join('seccion_colonias', 'colonias.id', '=', 'seccion_colonias.colonia_id')
            ->join('seccions', 'seccions.id', '=', 'seccion_colonias.seccion_id')
            ->join('distrito_locals', 'distrito_locals.id', '=', 'seccions.distrito_local_id')
            ->join('municipios', 'distrito_locals.municipio_id', '=', 'municipios.id')
            ->select('colonias.id', 'colonias.nombre', 'municipios.nombre as nombreMunicipio')
            ->distinct()
            ->whereIn('seccion_colonias.seccion_id', $seccionesParaBuscar)
            ->get();

            //FILTRADO DE COLONIAS PARA ENCONTRAR REPETIDOS Y CONCATENAR NOMBRE MUNICIPIO
            $colección = collect($colonias);
            $grupos = $colección->groupBy('nombre');
            $nombresRepetidos = $grupos->filter(function ($grupo) {
                return $grupo->count() > 1;
            });
            $nombresRepetidos->each(function ($grupo) {
                $grupo->transform(function ($item) {
                    $item['nombre'] .= ', ' . $item['nombreMunicipio'];
                    return $item;
                });
            });
            $secciones = seccion::whereIn('id', $seccionesParaBuscar)
            ->distinct()
            ->orderBy('seccions.id', 'ASC')
            ->get(['seccions.id']);

            $distritosLocales = distritoLocal::join('seccions', 'seccions.distrito_local_id', '=', 'distrito_locals.id')
            ->whereIn('seccions.id', $seccionesParaBuscar)
            ->distinct()
            ->orderBy('distrito_locals.id', 'ASC')
            ->get(['distrito_locals.id']);

            $distritosFederales = distritoFederal::join('municipios', 'distrito_federals.id', '=', 'municipios.distrito_federal_id')
            ->join('distrito_locals', 'distrito_locals.municipio_id', '=', 'municipios.id')
            ->join('seccions', 'seccions.distrito_local_id', '=', 'distrito_locals.id')
            ->whereIn('seccions.id', $seccionesParaBuscar)
            ->distinct()
            ->orderBy('distrito_federals.id', 'ASC')
            ->get(['distrito_federals.id']);

            $municipios = municipio::join('distrito_locals', 'distrito_locals.municipio_id', '=', 'municipios.id')
            ->join('seccions', 'seccions.distrito_local_id', '=', 'distrito_locals.id')
            ->whereIn('seccions.id', $seccionesParaBuscar)
            ->distinct()
            ->orderBy('municipios.id', 'ASC')
            ->get(['municipios.id', 'municipios.nombre']);

            $entidades = entidad::join('distrito_federals', 'distrito_federals.entidad_id', '=', 'entidads.id')
            ->join('municipios', 'distrito_federals.id', '=', 'municipios.distrito_federal_id')
            ->join('distrito_locals', 'distrito_locals.municipio_id', '=', 'municipios.id')
            ->join('seccions', 'seccions.distrito_local_id', '=', 'distrito_locals.id')
            ->whereIn('seccions.id', $seccionesParaBuscar)
            ->distinct()
            ->orderBy('entidads.id', 'ASC')
            ->get(['entidads.id', 'entidads.nombre']);

            $promotores = persona::inRandomOrder()->limit(5)->get();

            /*
            PROGRAMAS
            */
            return [
                'colonias' => $colonias, 'municipios' => $municipios, 'secciones' => $secciones,
                'entidades' => $entidades, 'distritosFederales' => $distritosFederales,
                'distritosLocales' => $distritosLocales, 'promotores' => $promotores
            ];
        }
        catch(Exception $e){
            Log::info($e->getLine(). ' | ' . $e->getMessage());
            return null;
        }
    }

    public function filtrarColonias($colonia){
        try{
            $municipios = municipio::orderBy('id')
            ->get();
            if($colonia > 0){
                $coloniaAux = colonia::find($colonia);
                $municipio = $coloniaAux->seccionColonia[0]->seccion->distritoLocal->municipio->id;
            }
            return [
                    'codigoPostal' => $coloniaAux->codigo_postal,
                    'municipio' => $municipio,
                    'colonia' => $colonia,
                    'nombreMunicipio' => municipio::find($municipio)->nombre,
                    'nombreColonia' => $coloniaAux->nombre,
                    'nombreEntidad' => $coloniaAux->seccionColonia[0]->seccion->distritoLocal->municipio->distritoFederal->entidad->nombre,
                    'idEntidad' => $coloniaAux->seccionColonia[0]->seccion->distritoLocal->municipio->distritoFederal->entidad->id,
                ];
        }
        catch(Exception $e){
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return null;
        }

    }

    public function filtrarSecciones($seccion){
        try {
            $entidades = entidad::orderBy('id')
            ->get();
            if($seccion > 0){
                $seccionEncotrada = seccion::find($seccion);
                $entidad = $seccionEncotrada->distritoLocal->municipio->distritoFederal->entidad->id;
                $distritoFederal = $seccionEncotrada->distritoLocal->municipio->distritoFederal->id;
                $distritoLocal = $seccionEncotrada->distritoLocal->id;
            }

            return[
                    'entidad' => $entidad,
                    'distritoFederal' => $distritoFederal,
                    'distritoLocal' => $distritoLocal,
                    'municipio' => $seccionEncotrada->distritoLocal->municipio->id,
                    'seccion' => $seccion
                ];
        } catch (Exception $e) {
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return null;
        }
    }

    public function agregandoSimpatizante(Request $formulario){
        session()->flash('validarCamposFormPersona', 'Hay campos erroneos o campos vacios');
        $formulario->validate([

        ]);
        try {
            DB::beginTransaction();
            $datosPersona = [
                'idUsuario' => Auth::id(),
                'fechaRegistro' => $formulario->input('fechaRegistro'),
                'folio' => $formulario->input('folio'),
                'idPromotor' => ($formulario->input('promotor') > 0 ? $formulario->input('promotor') : null),
                'origen' => $formulario->input('origen'),
                'referenciaOrigen' => $formulario->input('referenciaOrigen'),
                'referenciaCampania' => $formulario->input('referenciaCampania'),
                'etiquetasOrigen'=> $formulario->input('etiquetasOrigen'),
                'apodo' => $formulario->input('apodo'),
                'nombres' => $formulario->input('nombres'),
                'apellidoPaterno' => $formulario->input('apellidoPaterno'),
                'apellidoMaterno' => $formulario->input('apellidoMaterno'),
                'genero' => $formulario->input('genero'),
                'fechaNacimiento' => $formulario->input('fechaNacimiento'),
                'rangoEdad' => $formulario->input('rangoEdad'),
                'telefonoCelular1' => $formulario->input('telefonoCelular1'),
                'telefonoCelular2' => $formulario->input('telefonoCelular2'),
                'telefonoCelular3' => $formulario->input('telefonoCelular3'),
                'telefonoFijo' => $formulario->input('telefonoFijo'),
                'correo' => $formulario->input('correo'),
                'correoAlternativo' => $formulario->input('correoAlternativo'),
                'nombreFacebook' => $formulario->input('nombreFacebook'),
                'twitter' => $formulario->input('twitter'),
                'instagram' => $formulario->input('instagram'),
                'observaciones' => $formulario->input('observaciones'),
                'etiquetas' => $formulario->input('etiquetas'),
            ];

            $personaCreada = Persona::crear($datosPersona);
            //AGREGAR IDENTIFICACION
            $datosIdentificacion = [
                'idPersona' => $personaCreada->id,
                'curp' => $formulario->input('curp'),
                'claveElectoral' => $formulario->input('claveElectoral'),
                'lugarNacimiento' => $formulario->input('lugarNacimiento'),
                'seccion' => $formulario->input('seccion'),
            ];
            $identificacionCreada = identificacion::crear($datosIdentificacion);
            $datosDomicilio = [
                'calle1' => $formulario->input('calle1'),
                'calle2' => $formulario->input('calle2'),
                'calle3' => $formulario->input('calle3'),
                'numeroExterior' => $formulario->input('numeroExterior'),
                'numeroInterior' => $formulario->input('numeroInterior'),
                'referencia' => $formulario->input('referencia'),
                'colonia' => $formulario->input('colonia'),
                'coordenadas' => $formulario->input('coordenadas'),
                'idIdentificacion' => $identificacionCreada->id,
            ];
            $domicilioCreado = domicilio::crear($datosDomicilio);

            $user = auth()->user();
            $bitacora = new bitacora();
            $bitacora->accion = 'Agregar nueva persona';
            $bitacora->url = url()->current();
            $bitacora->ip = $formulario->ip();
            $bitacora->tipo = 'post';
            $bitacora->user_id = $user->id;
            $bitacora->save();
            DB::commit();
            session()->forget('validarCamposFormPersona');
            session()->flash('mensajeExito', 'La persona se ha creado con éxito');
            return redirect()->route('crudSimpatizantes.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar al simpatizante'])->withInput();
        }

    }
}
