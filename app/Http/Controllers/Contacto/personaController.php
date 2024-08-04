<?php

namespace App\Http\Controllers\Contacto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class personaController extends Controller
{
    function index(){
        return view('Pages.contactos.index');
    }

    function vistaAgregar(){
        return view('Pages.contactos.formulario');
    }

    function agregar(){

    }

    function vistaModificar(){
        return view('Pages.contactos.formulario');
    }

    function modificar(){

    }

    function vistaVer(){
        return view('Pages.contactos.consultar');
    }
    function borrar(){

    }

    function supervisar(){

    }







    public function index(){
        return view('Pages.contactos.tablaSimpatizantes');
    }
    public function index(persona $persona){
        if(auth()->user()->getRoleNames()->first() == 'CAPTURISTA' && $persona->supervisado){
            session()->flash('personaModificarDenegada', 'No se puede modificar una persona autorizada');
            return redirect()->route('crudSimpatizantes.index');
        }
        return view('Pages.contactos.formularioSimpatizante', ['persona' => $persona->id, 'latitud' => $persona->identificacion->domicilio->latitud,
        'longitud' => $persona->identificacion->domicilio->longitud]);
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
    public function inicializar(Request $formulario){
        try {
            //CONTROLADOR DE NIVELES DE ACCESO
            $user = auth()->user();

            $draw = ($formulario->get('draw') != null) ? $formulario->get('draw') : 1;
            $start = ($formulario->get('start') != null) ? $formulario->get('start') : 0;
            $length = ($formulario->get('length') != null) ? $formulario->get('length') : 10;
            $filter = $formulario->get('search');
            $search = (isset($filter['value']))? $filter['value'] : false;



            $personaQuery = persona::where('deleted_at', null)
            ->join('identificacions', 'personas.id', '=', 'identificacions.persona_id');

            if ($search != false) {
                $personaQuery->where(function($query) use ($search) {
                    $query->where('nombres', 'LIKE', '%' . $search . '%')
                        ->orWhere('telefonoCelular1', 'LIKE', '%' . $search . '%');
                });
            }
            $total = $personaQuery->count();

            if($user->getRoleNames()->first() == 'CAPTURISTA'){
                $personas = $personaQuery->orderBy('supervisado', 'ASC')->orderBy('id', 'DESC')
                    ->select(
                    'personas.id',
                    'apodo',
                    DB::raw('IF(apellido_paterno != "", CONCAT(nombres, " ", apellido_paterno), nombres) as nombre_completo'),
                    'telefonoCelular1',
                    'persona.tipo',
                    'supervisado',
                )
                ->skip($start)
                ->take($length)
                ->get();
            }
            else{
                $personas = $personaQuery->orderBy('supervisado', 'DESC')->orderBy('id', 'DESC')
                ->select(
                    'personas.id',
                    'apodo',
                    DB::raw('IF(apellido_paterno != "", CONCAT(nombres, " ", apellido_paterno), nombres) as nombre_completo'),
                    'telefonoCelular1',
                    'supervisado',
                )
                ->skip($start)
                ->take($length)
                ->get();
            }


            $personaQuery->where('supervisado', 0)
            ->count();

            return [
                'data' => $personas,
                'length' => $length,
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'start' => $start,
                'draw' => $draw,
            ];

        } catch (Exception $e) {
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return $e->getLine() . ' :: ' . $e->getMessage();
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
                    'municipio' => $municipio,
                    'colonia' => $colonia,
                    'codigoPostal' => $coloniaAux->codigo_postal,
                    'nombreMunicipio' => municipio::find($municipio)->nombre,
                    'nombreColonia' => $coloniaAux->nombre,
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

    public function cargarPersona(persona $persona){
        return [
            'persona' => $persona,
            'identificacion' => $persona->identificacion,
            'domicilio' => $persona->identificacion->domicilio,
            'colonia' => $persona->identificacion->domicilio->colonia,
            'municipio' => isset($persona->identificacion->domicilio->colonia) ? $persona->identificacion->domicilio->colonia->seccionColonia[0]->seccion->distritoLocal->municipio->id : null,
            'entidad' => isset($persona->identificacion->seccion) ? $persona->identificacion->seccion->distritoLocal->municipio->distritoFederal->entidad->id : null,
            'distritoFederal' => isset($persona->identificacion->seccion) ? $persona->identificacion->seccion->distritoLocal->municipio->distritoFederal->id : null,
            'distritoLocal' => isset($persona->identificacion->seccion) ? $persona->identificacion->seccion->distritoLocal->id : null,
            'seccion' => isset($persona->identificacion->seccion) ? $persona->identificacion->seccion->id : null,
        ];
    }

    public function modificarPersona(persona $persona, Request $formulario){
        session()->flash('validarCamposFormPersona', 'Hay campos erroneos o campos vacios');
        session()->flash('noEsCargaInicial', true);
        $formulario->validate([
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'nullable',
            'correo' => 'nullable|email',
            'genero' => 'nullable',
            'telefonoCelular' => 'nullable',
            'escolaridad' => 'nullable',
            'claveElectoral' => 'nullable|regex:/^([A-Z]{6})(\d{8})([B-DF-HJ-NP-TV-Z]{1})(\d{3})$/',
            'curp' => 'nullable|regex:/^([A-Z]{4})(\d{6})([HM])([A-Z]{5})([0-9A-Z]{2})$/',
            'esAfiliado' => 'nullable',
            'esSimpatizante' => 'nullable',
            'programa' => 'nullable',
            'funciones' => 'nullable',

            'calle' => 'nullable',
            'numeroExterior' => 'nullable',
        ]);
        $coordenadas = explode(',',$formulario->coordenadas);
        $curpRepetido = identificacion::where('curp', strtoupper($formulario->curp))->first();
            if(!isset($formulario->curp) || !isset($curpRepetido)){
                try {
                    DB::beginTransaction();
                    $persona->apellido_paterno = strtoupper($formulario->apellido_paterno);
                    $persona->apellido_materno = strtoupper($formulario->apellido_materno);
                    $persona->nombres = strtoupper($formulario->nombre);
                    $persona->tipoRegistro = $formulario->tipoRegistro;
                    $persona->genero = strtoupper($formulario->genero);
                    $persona->telefono_celular = strtoupper($formulario->telefonoCelular);
                    $persona->correo = strtoupper($formulario->correo);
                    $persona->afiliado = strtoupper($formulario->esAfiliado);
                    $persona->programa = strtoupper($formulario->programa);
                    $persona->simpatizante = strtoupper($formulario->esSimpatizante);
                    $persona->funcion_en_campania = strtoupper($formulario->funciones);
                    $persona->telefono_fijo = strtoupper($formulario->telefonoFijo);
                    $persona->escolaridad = strtoupper($formulario->escolaridad);
                    $persona->edadPromedio = $formulario->rangoEdad;
                    if(isset($formulario->rolEstructura) && $formulario->rolEstructura != -1){
                        if(isset($formulario->rolNumero)){
                            $persona->rolEstructura = $formulario->rolEstructura;
                            $persona->rolNumero = $formulario->rolNumero;
                        }
                        else if($formulario->rolEstructura == 'PROMOTOR'){
                            $persona->rolEstructura = $formulario->rolEstructura;
                        }
                        else{
                            DB::rollBack();
                            switch ($formulario->rolEstructura) {
                                case 'COORDINADOR ESTATAL':
                                    return back()->withErrors(['rolNumero' => 'Debe especificar que entidad coordina'])->withInput();
                                    break;
                                case 'COORDINADOR DE DISTRITO LOCAL':
                                    return back()->withErrors(['rolNumero' => 'Debe especificar que distrito coordina'])->withInput();
                                    break;
                                case 'COORDINADOR DE SECCIÓN':
                                    return back()->withErrors(['rolNumero' => 'Debe especificar que sección coordina'])->withInput();
                                    break;
                            }
                        }
                    }
                    if($formulario->tieneRolTemporal == 'SI'){
                        if(isset($formulario->rolEstructuraTemporal) && $formulario->rolEstructuraTemporal != -1){
                            if(isset($formulario->rolNumeroTemporal)){
                                $persona->rolEstructuraTemporal = $formulario->rolEstructuraTemporal;
                                $persona->rolNumeroTemporal = $formulario->rolNumeroTemporal;
                            }
                            else if($formulario->rolEstructuraTemporal == 'PROMOTOR'){
                                $persona->rolEstructuraTemporal = $formulario->rolEstructuraTemporal;
                            }
                            else{
                                DB::rollBack();
                                switch ($formulario->rolEstructuraTemporal) {
                                    case 'COORDINADOR ESTATAL':
                                        return back()->withErrors(['rolNumeroTemporal' => 'Debe especificar que entidad coordina'])->withInput();
                                        break;
                                    case 'COORDINADOR DE DISTRITO LOCAL':
                                        return back()->withErrors(['rolNumeroTemporal' => 'Debe especificar que distrito coordina'])->withInput();
                                        break;
                                    case 'COORDINADOR DE SECCIÓN':
                                        return back()->withErrors(['rolNumeroTemporal' => 'Debe especificar que sección coordina'])->withInput();
                                        break;
                                }
                            }
                        }
                    }

                    if(isset($formulario->fechaNacimiento)){
                        $persona->fecha_nacimiento = $formulario->fechaNacimiento;
                    }
                    if(isset($formulario->facebook)){
                        $persona->nombre_en_facebook = $formulario->facebook;
                    }
                    if(isset($formulario->fechaRegistro)){
                        $persona->fecha_registro = $formulario->fechaRegistro;
                    }
                    if(isset($formulario->etiquetas)){
                        $persona->etiquetas = $formulario->etiquetas;
                    }
                    if(isset($formulario->observaciones)){
                        $persona->observaciones = $formulario->observaciones;
                    }
                    if(isset($formulario->folio)){
                        $persona->folio = $formulario->folio;
                    }
                    if(isset($formulario->promotor) && $formulario->promotor > 0){
                        $persona->persona_id = $formulario->promotor;
                    }
                    $persona->save();

                    //AGREGAR IDENTIFICACION
                    $identificacion = identificacion::where('persona_id', $persona->id)
                    ->first();
                    $identificacion->curp = strtoupper($formulario->curp);
                    $identificacion->clave_elector = strtoupper($formulario->claveElectoral);
                    if($formulario->seccion > 0){
                        $identificacion->seccion_id = $formulario->seccion;
                    }
                    $identificacion->save();


                    //AGREGAR DOMICILIO
                    $domicilio = domicilio::where('identificacion_id', $identificacion->id)->first();
                    $domicilio->calle = strtoupper($formulario->calle);
                    $domicilio->numero_exterior = $formulario->numeroExterior;
                    $domicilio->numero_interior = $formulario->numeroInterior;
                    if($formulario->colonia > 0){
                        $domicilio->colonia_id = $formulario->colonia;
                    }
                    if(isset($coordenadas) && count($coordenadas) > 1){
                        $domicilio->latitud = $coordenadas[0];
                        $domicilio->longitud = $coordenadas[1];
                    }
                    $domicilio->save();

                    $user = auth()->user();
                    $bitacora = new bitacora();
                    $bitacora->accion = 'Se modifico la persona: ' . $persona->id;
                    $bitacora->url = url()->current();
                    $bitacora->ip = $formulario->ip();
                    $bitacora->tipo = 'post';
                    $bitacora->user_id = $user->id;
                    $bitacora->save();
                    DB::commit();
                    session()->forget('validarCamposFormPersona');
                    session()->forget('noEsCargaInicial');
                    session()->flash('mensajeExito', 'La persona se ha modificado con éxito');
                    return redirect()->route('crudSimpatizantes.index');
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
                    return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar al simpatizante'])->withInput();
                }
            }
            else{
                DB::rollBack();
                return back()->withErrors(['curp' => 'El curp ingresado ya esta registrado'])->withInput();
            }

    }
    public function consultar(persona $persona){
        $datos = [
            'persona' => $persona,
            'identificacion' => $persona->identificacion,
            'domicilio' => $persona->identificacion->domicilio,
            'colonia' => $persona->identificacion->domicilio->colonia,
            'municipio' => isset($persona->identificacion->domicilio->colonia) ? $persona->identificacion->domicilio->colonia->seccionColonia[0]->seccion->distritoLocal->municipio->id : null,
            'entidad' => isset($persona->identificacion->seccion) ? $persona->identificacion->seccion->distritoLocal->municipio->distritoFederal->entidad->id : null,
            'distritoFederal' => isset($persona->identificacion->seccion) ? $persona->identificacion->seccion->distritoLocal->municipio->distritoFederal->id : null,
            'distritoLocal' => isset($persona->identificacion->seccion) ? $persona->identificacion->seccion->distritoLocal->id : null,
            'seccion' => isset($persona->identificacion->seccion) ? $persona->identificacion->seccion->id : null,
        ];
        return view('Pages.contactos.consultarSimpatizante', $datos);
    }

    public function numeroSupervisados(){
        try {
            //CONTROLADOR DE NIVELES DE ACCESO
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
            if($user->getRoleNames()->first() == 'SUPER ADMINISTRADOR' || $user->getRoleNames()->first() == 'ADMINISTRADOR'){
                $seccionesParaBuscar = seccion::pluck('id')->toArray();
            }

            $personaQuery = persona::where('deleted_at', null)
            ->join('identificacions', 'personas.id', '=', 'identificacions.persona_id')
            ->leftjoin('seccions', 'seccions.id', '=', 'identificacions.seccion_id');

            if($user->nivel_acceso != 'TODO'){
                $personaQuery->where(function($query) use ($user, $seccionesParaBuscar) {
                        $query->whereIn('seccion_id', $seccionesParaBuscar)
                        ->orWhere('user_id', $user->id);
                });

            }

            $total = $personaQuery->count();

            $sinSupervisar = $personaQuery->where('supervisado', 0)
            ->count();

            return [
                'total' => $total,
                'sinSupervisar' => $sinSupervisar
            ];

        } catch (Exception $e) {
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return null;
        }
    }
    public function buscar(persona $persona){
        return $persona;
    }

    public function ver(persona $persona){
        return [
            'fechaRegistro' => $persona->fecha_registro,
            'folio' => $persona->folio,
            'promotor' => (isset($persona->promotor)) ? $persona->promotor->nombres . ' ' . $persona->promotor->apellido_paterno . ' ' . $persona->promotor->apellido_materno : '',
            'nombreCompleto' => $persona->nombres . ' ' . $persona->apellido_paterno . ' ' . $persona->apellido_materno,
            'genero' => $persona->genero,
            'fechaNacimiento' => $persona->fecha_nacimiento,
            'rangoEdad' => $persona->edadPromedio,
            'escolaridad' => $persona->escolaridad,
            'telefonoCelular' => $persona->telefono_celular,
            'telefonoFijo' => $persona->telefono_fijo,
            'correo' => $persona->correo,
            'facebook' => $persona->nombre_en_facebook,
            'calle' => $persona->identificacion->domicilio->calle,
            'numeroExterior' => $persona->identificacion->domicilio->numero_exterior,
            'numeroInterior' => $persona->identificacion->domicilio->numero_interior,
            'codigoPostal' => $persona->identificacion->domicilio->colonia->codigo_postal,
            'municipio' => (isset($persona->identificacion->seccion)) ? $persona->identificacion->seccion->distritoLocal->municipio->distritoFederal->entidad->nombre : '',
            'colonia' => $persona->identificacion->domicilio->colonia->nombre,
            'latitud' => $persona->identificacion->domicilio->latitud,
            'longitud' => $persona->identificacion->domicilio->longitud,
            'claveElectoral' => $persona->identificacion->clave_elector,
            'curp' => $persona->identificacion->curp,
            'seccion' => (isset($persona->identificacion->seccion)) ? $persona->identificacion->seccion->id : '',
            'distritoLocal' => (isset($persona->identificacion->seccion)) ? $persona->identificacion->seccion->distritoLocal->id : '',
            'municipio' => (isset($persona->identificacion->seccion)) ? $persona->identificacion->seccion->distritoLocal->municipio->nombre : '',
            'distritoFederal' => (isset($persona->identificacion->seccion)) ? $persona->identificacion->seccion->distritoLocal->municipio->distritoFederal->id : '',
            'entidadFederativa' => (isset($persona->identificacion->seccion)) ? $persona->identificacion->seccion->distritoLocal->municipio->distritoFederal->entidad->nombre : '',
            'afiliado' => $persona->afiliado,
            'simpatizante' => $persona->simpatizante,
            'programa' => $persona->programa,
            'rolEstructura' => $persona->rolEstructura,
            'rolNumerico' => $persona->rolNumero,
            'funcionAsignada' => $persona->funcion_en_campania,
            'etiquetas' => $persona->etiquetas,
            'observaciones' => $persona->observaciones,
        ];
    }

    public function verificar (Request $formulario, persona $persona){
        try{
            DB::beginTransaction();
            $user = auth()->user();
            $bitacora = new bitacora();
            if(!$persona->supervisado){
                $persona->supervisado = true;
                $persona->save();
                $bitacora->accion = 'Persona cambio a supervisada : ' . $persona->correo;
            }
            else{
                $persona->supervisado = false;
                $persona->save();
                $bitacora->accion = 'Persona cambio a no supervisada : ' . $persona->correo;
            }
                $bitacora->url = url()->current();
                $bitacora->ip = $formulario->ip();
                $bitacora->tipo = 'post';
                $bitacora->user_id = $user->id;
                $bitacora->save();
                DB::commit();
                session()->flash('mensajeExito', 'Se ha cambiado el estado de la persona exitosamente');
                return redirect()->route('crudSimpatizantes.index');
            }
            catch(Exception $e){
                DB::rollBack();
                Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
                return back()->withErrors(['errorBorrar' => 'Ha ocurrido un error al supervisar una persona']);
            }
    }

    public function borrar(Request $formulario, persona $persona){
        if(!isset($persona->deteled_at)){
            try{
                DB::beginTransaction();
                $persona->deleted_at =  Date("Y-m-d H:i:s");
                $persona->save();

                $user = auth()->user();
                $bitacora = new bitacora();
                $bitacora->accion = 'Borrando la persona : ' . $persona->correo;
                $bitacora->url = url()->current();
                $bitacora->ip = $formulario->ip();
                $bitacora->tipo = 'post';
                $bitacora->user_id = $user->id;
                $bitacora->save();

                DB::commit();
                session()->flash('mensajeExito', 'Una persona fue borrada exitosamente');
                return redirect()->route('crudSimpatizantes.index');
            }
            catch(Exception $e){
                DB::rollBack();
                Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
                return back()->withErrors(['errorBorrar' => 'Ha ocurrido un error al borrar una persona']);
            }
        }
        else{
            return back()->withErrors(['errorBorrar' => 'Ha ocurrido un error al borrar una persona']);
        }
    }

    public function descargar(){
        $fechaActual = Carbon::now()->format('d-F');
        $user = auth()->user();
        if($user->getRoleNames()->first() == 'SUPER ADMINISTRADOR' || $user->getRoleNames()->first() == 'ADMINISTRADOR'){
            return Excel::download(new UsersExport, 'personas-' . $fechaActual . '.xlsx');
        }
        else{
            return Excel::download(new UsersExport, 'personas-' . $fechaActual . '.xlsx');
        }
    }
}
