<?php

namespace App\Http\Controllers\Contacto;

use App\Http\Controllers\Controller;
use App\Models\bitacora;
use App\Models\ciudad;
use App\Models\colonia;
use App\Models\correo;
use App\Models\distritoFederal;
use App\Models\distritoLocal;
use App\Models\domicilio;
use App\Models\empresa;
use App\Models\entidad;
use App\Models\estatus;
use App\Models\identificacion;
use App\Models\localidad;
use App\Models\municipio;
use App\Models\persona;
use App\Models\personaDomicilio;
use App\Models\relacionPerfilUsuario;
use App\Models\RelacionPersonaEmpresa;
use App\Models\seccion;
use App\Models\telefono;
use App\Models\tipoFuncionPersonalizada;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class personaController extends Controller
{
    function index(){
        $query = persona::where('deleted_at', null)
        ->select(
            'personas.id',
            'estatus',
            'apodo',
            DB::raw('IF(apellido_paterno != "", CONCAT(nombres, " ", apellido_paterno), nombres) as nombre_completo'),
            'supervisado',
        );

        $usuarioActual = auth()->user();
        if($usuarioActual->getRoleNames()->first() != 'SUPER ADMINISTRADOR' && $usuarioActual->getRoleNames()->first() != 'ADMINISTRADOR'){
            if($usuarioActual->nivel_acceso != "TODOS" && $usuarioActual->niveles != ""){
                $nivelesEncontrados = explode(',', $usuarioActual->niveles);
                $query->whereHas('identificacion', function ($consulta) use ($nivelesEncontrados){
                    $consulta->whereIn('seccion_id', $nivelesEncontrados);
                });
            }

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
            $query->orWhere(function($consulta) use ($modelosAsociados) {
                foreach ($modelosAsociados as $modelo) {
                    $consulta->orWhere('personas.id', $modelo->idAsociado);
                }
            });


        }


        $personas = $query->get();
        return view('Pages.contactos.index', compact('personas'));
    }

    function cargarTabla(Request $formulario){

        $draw = ($formulario->get('draw') != null) ? $formulario->get('draw') : 1;
        $start = ($formulario->get('start') != null) ? $formulario->get('start') : 0;
        $length = ($formulario->get('length') != null) ? $formulario->get('length') : 10;
        $filter = $formulario->get('search');
        $search = (isset($filter['value']))? $filter['value'] : false;

        $query = persona::where('deleted_at', null)
        ->select(
            'personas.id',
            'estatus',
            'apodo',
            DB::raw('IF(apellido_paterno != "", CONCAT(nombres, " ", apellido_paterno), nombres) as nombre_completo'),
            'supervisado',
        );
        $usuarioActual = auth()->user();
        if($usuarioActual->getRoleNames()->first() != 'SUPER ADMINISTRADOR' && $usuarioActual->getRoleNames()->first() != 'ADMINISTRADOR'){
            if($usuarioActual->nivel_acceso != "TODOS" && $usuarioActual->niveles != ""){
                $nivelesEncontrados = explode(',', $usuarioActual->niveles);
                $query->whereHas('identificacion', function ($consulta) use ($nivelesEncontrados){
                    $consulta->whereIn('seccion_id', $nivelesEncontrados);
                });
            }

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
            $query->orWhere(function($consulta) use ($modelosAsociados) {
                foreach ($modelosAsociados as $modelo) {
                    $consulta->orWhere('personas.id', $modelo->idAsociado);
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
        $datos = $query->orderBy('id', 'desc')
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


    function cargarEmpresasAsignadas(Request $request, $idPersona){
        return RelacionPersonaEmpresa::where('persona_id', $idPersona)->get();
    }

    function gardarEmpresasAsignadas(Request $request, $idPersona){

    }

    function vistaAgregar(){
        $listaPromotores = persona::where('deleted_at', null)->where('promotor','SI')->get();
        $listaPersonas = persona::where('deleted_at', null)->get();
        $listaEstatus = estatus::get();
        $listaColonias = colonia::with('seccionColonia.seccion.distritoLocal.municipio')->get();
        $listaEstados = entidad::get();
        $listaMunicipios = municipio::get();
        $listaSecciones = seccion::get();
        $listaEmpresas = empresa::where('deleted_at', null)->get();
        $listaDistritoLocal = distritoLocal::get();
        $listaDistritoFederal = distritoFederal::get();
        $listaFuncionesPersonalida = tipoFuncionPersonalizada::get();
        return view('Pages.contactos.formulario', compact('listaPersonas', 'listaPromotores', 'listaColonias', 'listaMunicipios', 'listaEstatus',
            'listaEstados', 'listaSecciones', 'listaEmpresas', 'listaDistritoLocal', 'listaDistritoFederal', 'listaFuncionesPersonalida'));
    }

    function agregar(Request $request){
        try{
            DB::beginTransaction();
            $temporalRolesNumericos = $request->datosEstructura["rolEstructura"] != -1 ? $request->datosEstructura["rolNumero"] : [];
            $cordinadorDe = "";
            foreach ($temporalRolesNumericos as $depende) {
                $cordinadorDe .= ','.$depende;
            }
            $datos = [
                'user_id' => Auth::id(),
                'fecha_registro' => $request->datosControl["fecha_registro"],
                'folio' => $request->datosControl["folio"],
                'promotor_id' => $request->datosControl["promotor"] > 0 ? $request->datosControl["promotor"] : null,
                'origen' => $request->datosControl["origen"],
                'referenciaOrigen' => $request->datosControl["referenciaOrigen"] > 0 ? $request->datosControl["referenciaOrigen"] : null,
                'etiquetasOrigen' => trim($request->datosOtrosDatos["etiquetas"]),
                'estatus' => $request->datosControl["estatus"] > 0 ? $request->datosControl["estatus"] : "PENDIENTE",
                'apodo' => trim(strtoupper($request->datosPersonales["apodo"])),
                'apellido_paterno' => trim(strtoupper($request->datosPersonales["apellido_paterno"])),
                'apellido_materno' => trim(strtoupper($request->datosPersonales["apellido_materno"])),
                'nombres' => trim(strtoupper($request->datosPersonales["nombres"])),
                'genero' => $request->datosPersonales["genero"],
                'fecha_nacimiento' => $request->datosPersonales["fecha_nacimiento"],
                'rangoEdad' => $request->datosPersonales["rangoEdad"],
                'nombre_en_facebook' => trim($request->datosContacto["nombre_en_facebook"]),
                'twitter' => trim($request->datosContacto["twitter"]),
                'instagram' => trim($request->datosContacto["instagram"]),
                'afiliado' => $request->datosRelaciones["esAfiliado"],
                'simpatizante' => $request->datosRelaciones["esSimpatizante"],
                'programa' => $request->datosRelaciones["programa"],
                'cliente' => $request->datosRelaciones["cliente"],
                'promotor' => $request->datosRelaciones["promotorEstructura"],
                'colaborador' => $request->datosRelaciones["colaborador"],
                'etiquetas' => $request->datosOtrosDatos["etiquetas"],
                'observaciones' => trim(strtoupper($request->datosOtrosDatos["observaciones"])),
                'rolEstructura' => $request->datosEstructura["rolEstructura"],
                'coordinadorDe' => substr($cordinadorDe, 1),
                'funcionAsignada' => $request->datosEstructura["funcionAsignada"],
            ];
            $persona = persona::crear($datos);
            if(isset($request->datosContacto["telefonos"])){
                foreach ($request->datosContacto["telefonos"] as $telefono) {
                    telefono::create([
                        'telefono' => $telefono["telefono"],
                        'etiqueta' => $telefono["descripcion"],
                        'persona_id' => $persona->id,
                    ]);
                }
            }
            if(isset($request->datosContacto["correos"])){
                foreach ($request->datosContacto["correos"] as $correo) {
                    correo::create([
                        'correo' => $correo["correo"],
                        'etiqueta' => $correo["descripcion"],
                        'persona_id' => $persona->id,
                    ]);
                }
            }
            $datos = [
                'persona_id' => $persona->id,
                'curp' => trim(strtoupper($request->datosIdentificacion["curp"])),
                'rfc' => trim(strtoupper($request->datosIdentificacion["rfc"])),
                // 'ine',
                'lugarNacimiento' => $request->datosIdentificacion["lugarNacimiento"],
                'clave_elector' => trim(strtoupper($request->datosUbicacion["clave_elector"])),
                'seccion_id' => $request->datosUbicacion["seccion"] > 0 ? $request->datosUbicacion["seccion"] : null,
            ];
            $identificacion = identificacion::create($datos);

            $coordeandas = explode(',',$request->datosDomicilio["coordenadas"]);
            $datosDomicilio = [
                'calle1' => $request->datosDomicilio["calle1"],
                'calle2' => $request->datosDomicilio["calle2"],
                'calle3' => $request->datosDomicilio["calle3"],
                'numero_exterior' => $request->datosDomicilio["numero_exterior"],
                'numero_interior' => $request->datosDomicilio["numero_interior"],
                'latitud' => count($coordeandas) > 1 ? $coordeandas[0] : null,
                'longitud' => count($coordeandas) > 1 ? $coordeandas[1] : null,
                'colonia_id' => $request->datosDomicilio["colonia"] > 0 ? $request->datosDomicilio["colonia"] : null,
                'referencia' => $request->datosDomicilio["referencia"],
            ];
            $domicilio = domicilio::create($datosDomicilio);
            personaDomicilio::create([
                'tipo' => 'PRINCIPAL',
                'persona_id' => $persona->id,
                'domicilio_id' => $domicilio->id,
            ]);

            if($request->reutilizarDomicilio != "true"){
                //$coordeandas = explode(',',$request->datosFacturacion["coordenadas"]);
                $datosDomicilio = [
                    'calle1' => $request->datosFacturacion["calle1"],
                    'calle2' => $request->datosFacturacion["calle2"],
                    'calle3' => $request->datosFacturacion["calle3"],
                    'numero_exterior' => $request->datosFacturacion["numero_exterior"],
                    'numero_interior' => $request->datosFacturacion["numero_interior"],
                    //'latitud' => count($coordeandas) > 1 ? $coordeandas[0] : null,
                    //'longitud' => count($coordeandas) > 1 ? $coordeandas[1] : null,
                    'colonia_id' => $request->datosFacturacion["colonia"] > 0 ? $request->datosFacturacion["colonia"] : null,
                    'referencia' => $request->datosFacturacion["referencia"],
                ];
            }
            $domicilio = domicilio::create($datosDomicilio);
            personaDomicilio::create([
                'tipo' => 'FACTURACION',
                'persona_id' => $persona->id,
                'domicilio_id' => $domicilio->id,
            ]);
            if($request->datosRelacionEmpresa){
                foreach ($request->datosRelacionEmpresa as $relacionEmpresa) {
                    if($relacionEmpresa["empresa_id"] > 0){
                        $datos = [
                            'persona_id' => $persona->id,
                            'empresa_id' => $relacionEmpresa["empresa_id"],
                            'puesto' => $relacionEmpresa["cargo"]
                        ];
                        RelacionPersonaEmpresa::create($datos);
                    }
                }
            }
            bitacora::crearRegistro('Se ha agregado la persona con éxito', $request->ip(), 'ÉXITO');
            DB::commit();
            return [
                'titulo' => 'Éxito',
                'texto' => 'Se ha agregado la persona con éxito',
                'icono' => 'success',
                'exito' => true
            ];
        }
        catch(Exception $e){
            DB::rollBack();
            bitacora::crearRegistro($e->getLine(). ' :: ' .$e->getMessage(), $request->ip(), 'ERROR');
            return [
                'titulo' => 'Error',
                'texto' => 'Ocurrió un error al intentar agregar una persona',
                'icono' => 'error',
                'exito' => false
            ];
        }
    }

    function vistaModificar(persona $persona, Request $request){
        $registro = persona::with(
                'relacionDomicilio.domicilio.colonia'
            )
        ->find($persona->id);
        $listaPromotores = persona::where('deleted_at', null)->where('promotor','SI')->get();
        $listaPersonas = persona::where('deleted_at', null)->get();
        $listaEstatus = estatus::get(['id', 'concepto']);
        $listaColonias = colonia::with('seccionColonia.seccion.distritoLocal.municipio')->get();
        $listaEstados = entidad::get(['id', 'nombre']);
        $listaMunicipios = municipio::get(['id', 'nombre']);
        $listaSecciones = seccion::get(['id']);
        $listaEmpresas = empresa::where('deleted_at', null)->get();
        $listaMunicipios = municipio::get(['id', 'nombre']);
        $relacionesEmpresa = RelacionPersonaEmpresa::where('persona_id', $persona->id)->get();
        $listaDistritoLocal = distritoLocal::get(['id']);
        $listaDistritoFederal = distritoFederal::get(['id']);
        $listaFuncionesPersonalida = tipoFuncionPersonalizada::get();
        $conjunto = $request->conjunto;
        return view('Pages.contactos.formulario', [
            'listaPersonas' => $listaPersonas, 'persona' => $persona, 'telefonos' => $registro->telefonos, 'correos' => $registro->correos,
            'identificacion' => $registro->identificacion, 'relacionDomicilio' => $registro->relacionDomicilio, 'listaEmpresas' => $listaEmpresas,
            'listaPromotores' => $listaPromotores, 'listaColonias' => $listaColonias, 'listaMunicipios' => $listaMunicipios,
            'listaEstatus' => $listaEstatus, 'listaEstados' => $listaEstados, 'listaSecciones' => $listaSecciones,
            'relacionesEmpresa' => $relacionesEmpresa, 'listaDistritoLocal' => $listaDistritoLocal, 'listaDistritoFederal' => $listaDistritoFederal,
            'conjunto' => $conjunto, 'listaFuncionesPersonalida' => $listaFuncionesPersonalida
        ]);
    }

    function modificar(persona $persona, Request $request){
        try{
            DB::beginTransaction();
            $temporalRolesNumericos = $request->datosEstructura["rolEstructura"] != -1 ? $request->datosEstructura["rolNumero"] : [];
            $cordinadorDe = "";
            foreach ($temporalRolesNumericos as $depende) {
                $cordinadorDe .= ','.$depende;
            }
            $datos = [
                'user_id' => Auth::id(),
                'fecha_registro' => $request->datosControl["fecha_registro"],
                'folio' => $request->datosControl["folio"],
                'promotor_id' => $request->datosControl["promotor"] > 0 ? $request->datosControl["promotor"] : null,
                'origen' => $request->datosControl["origen"],
                'referenciaOrigen' => $request->datosControl["referenciaOrigen"] > 0 ? $request->datosControl["referenciaOrigen"] : null,
                'estatus' => $request->datosControl["estatus"] > 0 ? $request->datosControl["estatus"] : "PENDIENTE",
                'apodo' => trim(strtoupper($request->datosPersonales["apodo"])),
                'apellido_paterno' => trim(strtoupper($request->datosPersonales["apellido_paterno"])),
                'apellido_materno' => trim(strtoupper($request->datosPersonales["apellido_materno"])),
                'nombres' => trim(strtoupper($request->datosPersonales["nombres"])),
                'genero' => $request->datosPersonales["genero"],
                'fecha_nacimiento' => $request->datosPersonales["fecha_nacimiento"],
                'rangoEdad' => $request->datosPersonales["rangoEdad"],
                'nombre_en_facebook' => trim($request->datosContacto["nombre_en_facebook"]),
                'twitter' => trim($request->datosContacto["twitter"]),
                'instagram' => trim($request->datosContacto["instagram"]),
                'afiliado' => $request->datosRelaciones["esAfiliado"],
                'simpatizante' => $request->datosRelaciones["esSimpatizante"],
                'programa' => $request->datosRelaciones["programa"],
                'cliente' => $request->datosRelaciones["cliente"],
                'promotor' => $request->datosRelaciones["promotorEstructura"],
                'colaborador' => $request->datosRelaciones["colaborador"],
                'etiquetas' => $request->datosOtrosDatos["etiquetas"],
                'observaciones' => trim(strtoupper($request->datosOtrosDatos["observaciones"])),
                'rolEstructura' => $request->datosEstructura["rolEstructura"],
                'coordinadorDe' => substr($cordinadorDe, 1),
                'funcionAsignada' => $request->datosEstructura["funcionAsignada"],
            ];
            $persona->update($datos);
            $telefonosExistentes = telefono::where('persona_id', $persona->id)->get();
            foreach ($telefonosExistentes as $telefono) {
                $borrar = true;
                if(isset($request->datosContacto["telefonos"])){
                    foreach ($request->datosContacto["telefonos"] as $nuevo) {
                        if($telefono->telefono == $nuevo["telefono"]){
                            $borrar = false;
                        }
                    }
                }
                if($borrar){
                    $telefono->delete();
                }
            }
            if(isset($request->datosContacto["telefonos"])){
                Log::info($request->datosContacto);
                foreach ($request->datosContacto["telefonos"] as $telefono) {
                    $telefonoBuscado = telefono::where('telefono', $telefono["telefono"])->where('persona_id', $persona->id)->first();
                    if(!isset($telefonoBuscado)){
                        telefono::create([
                            'telefono' => $telefono["telefono"],
                            'etiqueta' => $telefono["descripcion"],
                            'persona_id' => $persona->id,
                        ]);
                    }
                }
            }
            $correosExistentes = correo::where('persona_id', $persona->id)->get();
            foreach ($correosExistentes as $correo) {
                $borrar = true;
                if(isset($request->datosContacto["correos"])){
                    foreach ($request->datosContacto["correos"] as $nuevo) {
                        if($correo->correo == $nuevo["correo"]){
                            $borrar = false;
                        }
                    }
                }
                if($borrar){
                    $correo->delete();
                }
            }
            if(isset($request->datosContacto["correos"])){
                foreach ($request->datosContacto["correos"] as $correo) {
                    $correoBuscado = correo::where('correo', $correo["correo"])->where('persona_id', $persona->id)->first();
                    if(!isset($correoBuscado)){
                        correo::create([
                            'correo' => $correo["correo"],
                            'etiqueta' => $correo["descripcion"],
                            'persona_id' => $persona->id,
                        ]);
                    }
                }
            }

            $datos = [
                'persona_id' => $persona->id,
                'curp' => trim(strtoupper($request->datosIdentificacion["curp"])),
                'rfc' => trim(strtoupper($request->datosIdentificacion["rfc"])),
                // 'ine',
                'lugarNacimiento' => $request->datosIdentificacion["lugarNacimiento"],
                'clave_elector' => trim(strtoupper($request->datosUbicacion["clave_elector"])),
                'seccion_id' => $request->datosUbicacion["seccion"] > 0 ? $request->datosUbicacion["seccion"] : null,
            ];
            $identificacion = identificacion::where('persona_id', $persona->id)->update($datos);

            $coordeandas = explode(',',$request->datosDomicilio["coordenadas"]);
            $datosDomicilio = [
                'calle1' => $request->datosDomicilio["calle1"],
                'calle2' => $request->datosDomicilio["calle2"],
                'calle3' => $request->datosDomicilio["calle3"],
                'numero_exterior' => $request->datosDomicilio["numero_exterior"],
                'numero_interior' => $request->datosDomicilio["numero_interior"],
                'latitud' => count($coordeandas) > 1 ? $coordeandas[0] : null,
                'longitud' => count($coordeandas) > 1 ? $coordeandas[1] : null,
                'colonia_id' => $request->datosDomicilio["colonia"] > 0 ? $request->datosDomicilio["colonia"] : null,
                'referencia' => $request->datosDomicilio["referencia"],
            ];
            $idDomicilio = personaDomicilio::where('persona_id', $persona->id)->where('tipo', 'PRINCIPAL')->first();
            $domicilio = domicilio::where('id', $idDomicilio->domicilio_id)->first();
            $domicilio->update($datosDomicilio);

            if($request->reutilizarDomicilio != "true"){
                //$coordeandas = explode(',',$request->datosFacturacion["coordenadas"]);
                $datosDomicilio = [
                    'calle1' => $request->datosFacturacion["calle1"],
                    'calle2' => $request->datosFacturacion["calle2"],
                    'calle3' => $request->datosFacturacion["calle3"],
                    'numero_exterior' => $request->datosFacturacion["numero_exterior"],
                    'numero_interior' => $request->datosFacturacion["numero_interior"],
                    //'latitud' => count($coordeandas) > 1 ? $coordeandas[0] : null,
                    //'longitud' => count($coordeandas) > 1 ? $coordeandas[1] : null,
                    'colonia_id' => $request->datosFacturacion["colonia"] > 0 ? $request->datosFacturacion["colonia"] : null,
                    'referencia' => $request->datosFacturacion["referencia"],
                ];
            }
            $idDomicilio = personaDomicilio::where('persona_id', $persona->id)->where('tipo', 'FACTURACION')->first();
            $domicilio = domicilio::where('id', $idDomicilio->domicilio_id)->first();
            $domicilio->update($datosDomicilio);

            $relacionEmpresasExistentes = RelacionPersonaEmpresa::where('persona_id', $persona->id)->get();
            foreach ($relacionEmpresasExistentes as $relacion) {
                $borrar = true;
                if(isset($request->datosRelacionEmpresa)){
                    foreach ($request->datosRelacionEmpresa as $nuevo) {
                        if($relacion->empresa_id == $nuevo["empresa_id"]){
                            $borrar = false;
                        }
                    }
                }
                if($borrar){
                    $relacion->delete();
                }
            }

            if($request->datosRelacionEmpresa){
                foreach ($request->datosRelacionEmpresa as $relacionEmpresa) {
                    $telefonoBuscado = RelacionPersonaEmpresa::where('empresa_id', $relacionEmpresa["empresa_id"])->where('persona_id', $persona->id)->first();
                    if($relacionEmpresa["empresa_id"] > 0 && !isset($telefonoBuscado)){
                        $datos = [
                            'persona_id' => $persona->id,
                            'empresa_id' => $relacionEmpresa["empresa_id"],
                            'puesto' => $relacionEmpresa["cargo"]
                        ];
                        RelacionPersonaEmpresa::create($datos);
                    }
                }
            }

            bitacora::crearRegistro('Se ha modificado la persona con éxito', $request->ip(), 'ÉXITO');
            DB::commit();
            return [
                'titulo' => 'Éxito',
                'texto' => 'Se ha modificado la persona con éxito',
                'icono' => 'success',
                'exito' => true
            ];
        }
        catch(Exception $e){
            DB::rollBack();
            bitacora::crearRegistro($e->getLine(). ' :: ' .$e->getMessage(), $request->ip(), 'ERROR');
            return [
                'titulo' => 'Error',
                'texto' => 'Ocurrió un error al intentar modificar una persona',
                'icono' => 'error',
                'exito' => false
            ];
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
                session()->flash('mensajeExito', 'Se ha vuelto no supervizado la persona con éxito');
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
        $registro = persona::with([
            'telefonos',
            'correos',
            'identificacion',
            'relacionPersonaEmpresa.empresa',
            'relacionDomicilio.domicilio.colonia'
        ])
        ->where('personas.id', $persona->id)
        ->first();
        return view('Pages.contactos.ficha', [
            'persona' => $registro,
        ]);
    }
}

