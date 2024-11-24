<?php

namespace App\Http\Controllers\Estadistica;

use App\Http\Controllers\Controller;
use App\Models\distritoFederal;
use App\Models\distritoLocal;
use App\Models\entidad;
use App\Models\estatus;
use App\Models\meta;
use App\Models\persona;
use App\Models\seccion;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;

class estadisticaController extends Controller
{
    public function index(){
        $listaSecciones = seccion::all();
        $listaDistritoLocal = distritoLocal::all();
        $litaDistritoFederal = distritoFederal::all();
        $listaEntidadFederativa = entidad::all();
        $listaEstatus = estatus::all();
        $listaRelaciones = ['CLIENTE', 'PROMOTOR', 'COLABORADOR', 'AFILIADO', 'SIMPATIZANTE', 'RELACIÓN PERSONALIZADA'];
        return view('Pages.estadistica.estadistica', compact('listaSecciones',  'listaDistritoLocal',
        'litaDistritoFederal', 'listaEntidadFederativa', 'listaEstatus', 'listaRelaciones'));
    }

    public function inicializar(Request $formulario){
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

        $filtroEntidades = $formulario->entidades;
        $filtroDistritoFederal = $formulario->distritosFederales;
        $filtroDistritoLocal = $formulario->distritosLocales;
        $filtroSeccion = $formulario->secciones;
        $filtroFechaInicio = $formulario->fechaInicio;
        $filtroFechaFin = $formulario->fechaFin;
        $filtroEstatus = $formulario->estatus;
        $filtroTipoPersona = $formulario->tipoPersonas;
        $query = persona::leftJoin('identificacions', 'identificacions.persona_id', '=', 'personas.id')
        ->leftJoin('seccions', 'seccions.id', '=', 'identificacions.seccion_id')
        ->leftJoin('distrito_locals', 'distrito_locals.id', '=', 'seccions.distrito_local_id')
        ->leftJoin('municipios', 'municipios.id', '=', 'distrito_locals.municipio_id')
        ->leftJoin('distrito_federals', 'distrito_federals.id', '=', 'municipios.distrito_federal_id')
        ->where('personas.deleted_at', null);

        if(isset($filtroEntidades) && count($filtroEntidades) > 0 && !in_array('TODOS', $filtroEntidades)){
            $query->whereIn('entidad_id', $filtroEntidades);
        }
        if(isset($filtroDistritoFederal) && count($filtroDistritoFederal) > 0 && !in_array('TODOS', $filtroDistritoFederal)){
            $query->whereIn('distrito_federal_id', $filtroDistritoFederal);
        }
        if(isset($filtroDistritoLocal) && count($filtroDistritoLocal) > 0 && !in_array('TODOS', $filtroDistritoLocal)){
            $query->whereIn('distrito_local_id', $filtroDistritoLocal);
        }
        Log::info($formulario);
        if(isset($filtroSeccion) && count($filtroSeccion) > 0 && !in_array('TODOS', $filtroSeccion)){
            $query->whereIn('seccion_id', $filtroSeccion);
        }
        if(isset($filtroFechaInicio)){
            $query->where('fecha_registro', '>=', $filtroFechaInicio);
        }
        if(isset($filtroFechaFin)){
            $query->where('fecha_registro', '<=', $filtroFechaFin);
        }
        if(isset($filtroEstatus) && count($filtroEstatus) > 0 && !in_array('TODOS', $filtroEstatus)){
            $query->whereIn('personas.estatus', $filtroEstatus);
        }
        if(isset($filtroTipoPersona) && count($filtroTipoPersona) > 0 && !in_array('TODOS', $filtroTipoPersona)){
            if(in_array('CLIENTE', $filtroTipoPersona)){
                $query->where('personas.cliente', 'SI');
            }
            if(in_array('PROMOTOR', $filtroTipoPersona)){
                $query->where('personas.promotor', 'SI');
            }
            if(in_array('COLABORADOR', $filtroTipoPersona)){
                $query->where('personas.colaborador', 'SI');
            }
            if(in_array('AFILIADO', $filtroTipoPersona)){
                $query->where('personas.afiliado', 'SI');
            }
            if(in_array('SIMPATIZANTE', $filtroTipoPersona)){
                $query->where('personas.simpatizante', 'SI');
            }
            if(in_array('RELACIÓN PERSONALIZADA', $filtroTipoPersona)){
                $query->where('personas.cliente', '!=', NULL);
            }
        }

        Log::info($query->toSql());
        $primerResultado = clone $query;
        $segundoResultado = clone $query;
        $tercerResultado = clone $query;
        $cuartoResultado = clone $query;



        $conteoSupervisados = $primerResultado->groupBy(
            'supervisado'
        )
        ->select(
            DB::raw('COUNT(supervisado) as conteo'),
            DB::raw('IF(supervisado = true, "SUPERVISADO", "SIN SUPERVISAR") as tipo')
        );

        $conteoEstatus = $segundoResultado->groupBy(
            'estatus'
        )
        ->select(
            'estatus',
            DB::raw('COUNT(*) as conteo'),
        );

        $personas = $tercerResultado->select('seccion_id', 'poblacion', 'objetivo', DB::raw('COUNT(*) as conteoTotal'))
        ->groupBy('seccion_id', 'poblacion', 'objetivo');

        $registrosPorFechas = $cuartoResultado->select('fecha_registro', DB::raw('COUNT(*) as conteoTotal'))
        ->groupBy('fecha_registro')
        ->orderBy('fecha_registro', 'ASC');







        $registrosPorFechas = $registrosPorFechas->get();
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $conteos = [];
        $dias = [];
        $maximo = 0;
        foreach ($registrosPorFechas as $registro) {
            $fechaActual = Carbon::parse($registro->fecha_registro)->format('d-F-Y');
            $fecha = Carbon::parse($fechaActual);
            $mes = $meses[($fecha->format('n')) - 1];
            $fechaFormateada = $fecha->format('d') . ' de ' . $mes . ' ' . $fecha->format('Y');
            array_push($dias, $fechaFormateada);
            array_push($conteos, $registro->conteoTotal);
            if($maximo < $registro->conteoTotal){
                $maximo = $registro->conteoTotal;
            }

        }

        $seccions = seccion::select('id', 'poblacion', 'objetivo')->get();
        return [
            'conteoSeparado' => $personas->get(),
            'registrosPorFechas' => [
                'conteos' => $conteos,
                'fechas' => $dias,
                'maximo' => $maximo
            ],
            'conteoSupervisados' => ['labels' => $conteoSupervisados->pluck('tipo'), 'datos' => $conteoSupervisados->pluck('conteo')],
            'conteoEstatus' => ['labels' => $conteoEstatus->pluck('estatus'), 'datos' => $conteoEstatus->pluck('conteo')],


            'seccionesAccesibles' => $seccionesParaBuscar,
            'seccionesConfigurarMetas' => $seccions
        ];
    }

    public function cargarMeta(Request $formulario){
        $meta = seccion::find($formulario->idSeccion);
        try {
            DB::beginTransaction();
            $meta->objetivo = $formulario->cantidadObjetivo;
            $meta->poblacion = $formulario->poblacion;
            $meta->save();
            DB::commit();
            return [1, 'Se ha modificado la sección: ' . $formulario->idSeccion . ' se recomienda recargar la página.'];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return [0, 'Ha ocurrido un error al cambiar una meta.'];
        }
    }

    public function filtrar(Request $formulario){
        $banderaAgrupacion = $formulario->banderaAgrupacion;
        $seccionesSeleccionadas = $formulario->seccionesSeleccionadas;
        $fechaInicio = $formulario->fechaInicio;
        $fechaFin = $formulario->fechaFin;
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
        $personas = persona::join('identificacions', 'identificacions.persona_id', '=', 'personas.id')
        ->leftJoin('seccions', 'identificacions.seccion_id', '=', 'seccions.id')
        ->select('seccion_id', 'poblacion', 'objetivo', DB::raw('COUNT(*) as conteoTotal'))
        ->groupBy('seccion_id', 'poblacion', 'objetivo')
        ->get();

        $registrosPorFechas = persona::select('fecha_registro', DB::raw('COUNT(*) as conteoTotal'))
        ->groupBy('fecha_registro')
        ->orderBy('fecha_registro', 'ASC')
        ->get();

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $conteos = [];
        $dias = [];
        $maximo = 0;
        foreach ($registrosPorFechas as $registro) {
            $fechaActual = Carbon::parse($registro->fecha_registro)->format('d-F');
            $fecha = Carbon::parse($fechaActual);
            $mes = $meses[($fecha->format('n')) - 1];
            $fechaFormateada = $fecha->format('d') . ' de ' . $mes;
            array_push($dias, $fechaFormateada);
            array_push($conteos, $registro->conteoTotal);
            if($maximo < $registro->conteoTotal){
                $maximo = $registro->conteoTotal;
            }

        }

        $conteoSupervisados = persona::
            groupBy(
                'supervisado'
            )
            ->select(
                DB::raw('COUNT(supervisado) as conteo'),
                DB::raw('IF(supervisado = true, "SUPERVISADO", "SIN SUPERVISAR") as tipo')
            )
            ->get();
        $conteoEstatus = persona::
            groupBy(
                'estatus'
            )
            ->select(
                'estatus',
                DB::raw('COUNT(*) as conteo'),
            )
            ->get();


        $seccions = seccion::select('id', 'poblacion', 'objetivo')->get();
        return [
            'conteoSeparado' => $personas,
            'registrosPorFechas' => [
                'conteos' => $conteos,
                'fechas' => $dias,
                'maximo' => $maximo
            ],
            'conteoSupervisados' => ['labels' => $conteoSupervisados->pluck('tipo'), 'datos' => $conteoSupervisados->pluck('conteo')],
            'conteoEstatus' => ['labels' => $conteoEstatus->pluck('estatus'), 'datos' => $conteoEstatus->pluck('conteo')],


            'seccionesAccesibles' => $seccionesParaBuscar,
            'seccionesConfigurarMetas' => $seccions
        ];

    }
}
