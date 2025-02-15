<?php

use App\Http\Controllers\Configuracion\bitacoraController;
use App\Http\Controllers\Configuracion\catalogosController;
use App\Http\Controllers\Configuracion\crudUsuariosController;
use App\Http\Controllers\Configuracion\importarDatosController;
use App\Http\Controllers\Configuracion\permisosController;
use App\Http\Controllers\Configuracion\personalizarController;
use App\Http\Controllers\Contacto\crudPersonasController;
use App\Http\Controllers\Contacto\formularioSimpatizanteController;
use App\Http\Controllers\Contacto\mapaController;
use App\Http\Controllers\Contacto\personaController;
use App\Http\Controllers\Contacto\tablaSimpatizantesController;
use App\Http\Controllers\cotizaciones\CotizacionController;
use App\Http\Controllers\cotizaciones\FacturaController;
use App\Http\Controllers\cotizaciones\InventarioController;
use App\Http\Controllers\crudPromotoresController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\Encuestas\crudEncuestasController;
use App\Http\Controllers\Encuestas\crudResultadosController;
use App\Http\Controllers\Estadistica\estadisticaController;
use App\Http\Controllers\integracionesController;
use App\Http\Controllers\Login\iniciarSesionController;
use App\Http\Controllers\Marketing\crudObjetivoController;
use App\Http\Controllers\Marketing\crudOportunidadesController;
use App\Http\Controllers\perfilUsuarioController;
use App\Models\bitacora;
use App\Models\inventario;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/iniciar-sesion',
[iniciarSesionController::class, 'index'])->name('login');
Route::post('/iniciar-sesion/verificando',
[iniciarSesionController::class, 'validarUsuario'])->name('login.comprobando');
Route::get("/encuestas/contestar-encuesta-{encuesta}",
[crudEncuestasController::class, 'visualizarEncuesta'])->name('encuestas.visualizarEncuesta');
Route::get("/encuestas/cargar-encuesta-{encuesta}",
[crudEncuestasController::class, 'cargarEncuesta'])->name('encuestas.cargarEncuesta');
Route::post("/encuestas/contestar-encuesta-{encuesta}",
[crudEncuestasController::class, 'contestarEncuesta'])->name('encuestas.contestarEncuesta');
Route::get("/gracias",
[crudEncuestasController::class, 'graciasResponder'])->name('encuestas.graciasResponder');
Route::post("/iniciar-sesion/recuperar-clave/",
[iniciarSesionController::class, 'enviarCorreo'])->name('login.enviarCorreo');
Route::get("/iniciar-sesion/recuperar-clave-{token}",
[iniciarSesionController::class, 'vistaRecuperarClave'])->name('login.vistaRecuperarClave');
Route::post("/iniciar-sesion/recuperar-clave-{token}",
[iniciarSesionController::class, 'cambiarClave'])->name('login.cambiarClave');


Route::prefix('/')->middleware('auth')->group(function (){
    Route::post('cerrando-sesion', [iniciarSesionController::class, 'cerrarSesion'])->name('logout');
    Route::prefix('permisos')->controller(permisosController::class)->group(function () {
        Route::get('/', 'index')->name('permisos.index');
    });
    Route::prefix('gestor-usuarios')->controller(crudUsuariosController::class)->group(function () {
        Route::get('/', 'index')
        ->name('crudUsuario.index')->middleware(['can:crudUsuarios.index']);
        Route::get('/usuarios', 'todosUsuarios')
        ->name('crudUsuario.todos')->middleware(['can:crudUsuarios.index']);
        Route::get('/obtener-{usuario}', 'obtenerUsuario')
        ->name('crudUsuario.obtener')->middleware(['can:crudUsuarios.edit']);
        Route::post('/crear-usuario', 'crearUsuario')
        ->name('crudUsuario.crear')->middleware(['can:crudUsuarios.create']);
        Route::post('/editar-usuario-{usuario}', 'editarUsuario')
        ->name('crudUsuario.editar')->middleware(['can:crudUsuarios.edit']);
        Route::post('/borrar-usuario-{usuario}', 'borrarUsuario')
        ->name('crudUsuario.borrar')->middleware(['can:crudUsuarios.delete']);
        Route::get('/cargar-municipios', 'cargarMunicipios');
        Route::get('/filtrar-municipios-{entidad}', 'filtrarMunicipios')
        ->name('crudUsuario.filtrarMunicipios');
    });
    Route::prefix('contactos')->controller(personaController::class)->group(function() {
        Route::get('/', 'index')->name('contactos.index');
        Route::get('/cargar-tabla', 'cargarTabla')->name('contactos.cargarTabla');
        Route::get('/agregar', 'vistaAgregar')->name('contactos.vistaAgregar');
        Route::post('/agregar', 'agregar')->name('contactos.agregar');
        Route::get('/modificar-{persona}', 'vistaModificar')->name('contactos.vistaModificar');
        Route::post('/modificar-{persona}', 'modificar')->name('contactos.modificar');
        Route::get('/ver-{persona}', 'vistaVer')->name('contactos.vistaVer');
        Route::post('/borrar-{persona}', 'borrar')->name('contactos.borrar');
        Route::post('/supervisar-{persona}', 'supervisar')->name('contactos.supervisar');
        Route::get('/ficha-{persona}', 'fichaTecnica')->name('contactos.fichaTecnica');
        Route::get('/asignar-empresas-{persona}', 'cargarEmpresasAsignadas')->name('contactos.cargarEmpresasAsignadas');
        Route::post('/asignar-empresas-{persona}', 'gardarEmpresasAsignadas')->name('contactos.gardarEmpresasAsignadas');
    });
    Route::prefix('empresas')->controller(EmpresaController::class)->group(function() {
        Route::get('/', 'index')->name('empresas.index');
        Route::get('/cargar-tabla', 'cargarTabla')->name('empresas.cargarTabla');
        Route::get('/agregar', 'vistaAgregar')->name('empresas.vistaAgregar');
        Route::post('/agregar', 'crear')->name('empresas.agregar');
        Route::get('/modificar-{empresa}', 'vistaModificar')->name('empresas.vistaModificar');
        Route::post('/modificar-{empresa}', 'modificar')->name('empresas.modificar');
        Route::post('/borrar-{empresa}', 'borrar')->name('empresas.borrar');
        Route::get('/asignar-contactos-{empresa}', 'cargarContactosAsignados')->name('empresas.cargarContactosAsignados');
        Route::post('/asignar-contactos-{empresa}', 'guardarContactosAsignados')->name('empresas.guardarContactosAsignados');
        Route::get('/relacionados-{empresa}', 'verListaPersonas')->name('empresas.verListaPersonas');
    });
    Route::prefix('perfil')->controller(perfilUsuarioController::class)->group(function () {
        Route::get('/gestionar-grupos/{usuario}', 'index')->name('perfil.index');
        Route::get('/buscarRelaciones', 'buscarRelaciones')->name('perfil.buscarRelaciones');
        Route::post('/gestionar-grupos/{usuario}', 'manejarPerfil')->name('perfil.manejarPerfil');
        Route::post('/relacionar-usuarios-con-grupos', 'relacionarGruposConUsuarios')->name('perfil.relacionarGruposConUsuarios');
        Route::post('/borrar-relacion/{usuario}', 'borrarRelacion')->name('perfil.borrarRelacion');
    });
    Route::prefix('simpatizantes')->group(function() {
        Route::controller(tablaSimpatizantesController::class)->group(function() {
            Route::get('/', 'index')
            ->name('crudSimpatizantes.index')->middleware(['can:crudSimpatizantes.index']);
            Route::get('/descargar', 'descargar')
            ->name('crudSimpatizantes.descargar')->middleware(['can:crudSimpatizantes.exportar']);
            Route::get('/inicializar', 'inicializar')
            ->name('crudSimpatizantes.inicializar')->middleware(['can:crudSimpatizantes.index']);
            Route::get('/numeros-supervisado', 'numeroSupervisados')
            ->name('crudSimpatizantes.numeroSupervisados')->middleware(['can:crudSimpatizantes.index']);
            Route::get('/ver-{persona}', 'ver')
            ->name('crudSimpatizantes.ver')->middleware(['can:crudSimpatizantes.consultar']);//DESUSO
            Route::post('/supervisar-{persona}', 'verificar')
            ->name('crudSimpatizantes.verificar')->middleware(['can:crudSimpatizantes.verificar', 'nivelAcceso']); //ESTOS NECESITA LA VALIDACION
            Route::post('/borrar-{persona}', 'borrar')
            ->name('crudSimpatizantes.borrar')->middleware(['can:crudSimpatizantes.borrar', 'nivelAcceso']); //ESTOS NECESITA LA VALIDACION
        });
        Route::controller(formularioSimpatizanteController::class)->group(function() {
            Route::get('/filtrarColonias-{colonia}', 'filtrarColonias')
            ->name('crudSimpatizantes.filtrarColonias')->middleware(['can:agregarSimpatizante.index']);
            Route::get('/filtrarSecciones-{seccion}', 'filtrarSecciones')
            ->name('crudSimpatizantes.filtrarSecciones')->middleware(['can:agregarSimpatizante.index']);
            Route::get('/agregar', 'index')
            ->name('agregarSimpatizante.index')->middleware(['can:agregarSimpatizante.index']);
            Route::get('/agregar/inicializar', 'inicializar')
            ->name('agregarSimpatizante.inicializar')->middleware(['can:agregarSimpatizante.index']);
            Route::post('/agregar/agregando', 'agregandoSimpatizante')
            ->name('agregarSimpatizante.agregandoSimpatizante')->middleware(['can:agregarSimpatizante.index']);
        });
        // Route::controller(crudPersonasController::class)->group(function() {
        //     Route::get('/modificar-{persona}', 'index')
        //     ->name('crudPersonas.index')->middleware(['can:crudSimpatizantes.modificar', 'nivelAcceso']); //ESTOS NECESITA LA VALIDACION
        //     Route::get('/modificar/cargarPersona-{persona}', 'cargarPersona')
        //     ->name('crudPersonas.cargarPersona')->middleware(['can:crudSimpatizantes.modificar', 'nivelAcceso']); //ESTOS NECESITA LA VALIDACION
        //     Route::post('/modificar/modificarPersona-{persona}', 'modificarPersona')
        //     ->name('crudPersonas.modificarPersona')->middleware(['can:crudSimpatizantes.modificar', 'nivelAcceso']); //ESTOS NECESITA LA VALIDACION
        //     Route::get('/consultar-{persona}', 'consultar')
        //     ->name('crudPersonas.consultar')->middleware(['can:crudSimpatizantes.consultar', 'nivelAcceso']); //ESTOS NECESITA LA VALIDACION
        // });
    });
    Route::prefix('estadistica')->controller(estadisticaController::class)->group(function(){
        Route::get('/', 'index')
        ->name('estadistica.index')->middleware(['can:estadistica.index']);
        Route::get('/inicializar', 'inicializar')
        ->name('estadistica.inicializar')->middleware(['can:estadistica.index']);
        Route::get('/filtrar', 'filtrar')
        ->name('estadistica.filtrar')->middleware(['can:estadistica.index']);
        Route::post('/cargarMeta', 'cargarMeta')
        ->name('estadistica.cargarMeta')->middleware(['can:estadistica.cambiarMeta']);

    });
    Route::prefix('encuestas')->group(function(){
        Route::controller(crudEncuestasController::class)->group(function(){
            Route::get("/", 'index')->name('encuestas.index');
            Route::get("/inicializar", 'cargarEncuestas')->name('encuestas.cargar');
            Route::get("/cargar-secciones", 'cargarSecciones')->name('encuestas.cargarSecciones');
            Route::post("/agregar", 'agregar')->name('encuestas.agregar');
            Route::get("/ver-{encuesta}", 'ver')->name('encuestas.ver');
            Route::post("/configurar-{encuesta}", 'configurar')->name('encuestas.configurar');
            Route::post("/modificar-{encuesta}", 'editar')->name('encuestas.modificar');
            Route::post("/iniciar-periodo-{encuesta}", 'iniciarEncuesta')->name('encuestas.iniciarEncuesta');
            Route::post("/finalizar-periodo-{encuesta}", 'detenerEncuesta')->name('encuestas.finalizarEncuesta');
            Route::post("/borrar-{encuesta}", 'borrar')->name('encuestas.borrar');
            Route::post("/duplicar-{encuesta}", 'clonar')->name('encuestas.clonar');
        });
        Route::prefix('resultados')->controller(crudResultadosController::class)->group(function(){
            Route::get("/", 'index')->name('respuestas.index');
            Route::get("/inicializar", 'inicializar')->name('respuestas.inicializar');
            Route::get("/cargar-resultado-{respuesta}", 'cargarResultado')->name('respuestas.cargarResultado');
            Route::get("/tabla-respuestas", 'paginacion')->name('respuestas.paginacion');
            Route::get('/exportar-{encuesta}', "exportarResultados")->name('respuestas.exportarResultados');
            Route::post('/vincular-{respuesta}-{persona}', "vincularPersona")->name('respuestas.vincularPersona');
        });
    });
    Route::prefix('objetivos')->controller(crudObjetivoController::class)->group(function(){
        Route::get('/', 'index')
        ->name('objetivos.index');
        Route::get('/inicializar', 'inicializar')
        ->name('objetivos.inicializar');
        Route::get('/cargar-tabla', 'cargarTabla')
        ->name('objetivos.cargarTabla');
        Route::post('/cambiar-estatus-{objetivo}', 'cambiarEstatus')
        ->name('objetivos.cambiarEstatus');
        Route::post('/agregar-objetivo', 'agregar')
        ->name('objetivos.agregar');
        Route::get('/cargar-objetivo-{objetivo}', 'cargar')
        ->name('objetivos.cargarObjetivo');
        Route::post('/modificar-objetivo-{objetivo}', 'editar')
        ->name('objetivos.modificar');
        Route::post('/borrar-{objetivo}', 'borrar')
        ->name('objetivos.borrar');
    });
    Route::prefix('inventario')->controller(InventarioController::class)->group(function (){
        route::get('/', 'index')->name('inventario.index');
        route::get('/buscar-{inventario}', 'obtenerProducto')->name('inventario.obtenerProducto');
        route::get('/inicializar', 'cargarTabla')->name('inventario.cargarTabla');
        route::get('/agregar', 'vistaCrear')->name('inventario.vistaCrear');
        route::post('/agregar', 'crear')->name('inventario.crear');
        route::post('/cambiar-existencia', 'cambiarExistencia')->name('inventario.cambiarExistencia');
        route::post('/eliminar-producto', 'eliminarProducto')->name('inventario.eliminarProducto');
    });
    Route::prefix('cotizaciones')->controller(CotizacionController::class)->group(function (){
        route::get('/', 'index')->name('cotizacion.index');
    });
    Route::prefix('factura')->controller(FacturaController::class)->group(function (){
        route::get('/', 'index')->name('factura.index');
    });
    Route::prefix('configuracion')->group(function(){
        route::prefix('personalizar')->controller(personalizarController::class)->group(function (){
            route::get('/', 'index')->name('personalizar.index');
            route::post('/cambiar', 'configurar')->name('personalizar.cambiar');
        });
        route::prefix('catalogos')->controller(catalogosController::class)->group(function () {
            route::get('/', 'index')->name('catalogos.index');
        });
        route::prefix('importar')->controller(importarDatosController::class)->group(function () {
            route::get('/', 'index')->name('importar.index');
        });
    });
    Route::prefix('integraciones')->controller(integracionesController::class)->group(function(){
        route::get('/', 'index')->name('integracion.index');
        route::post('/crear', 'crear')->name('integracion.crear');
        route::post('/editar-{integracion}', 'editar')->name('integracion.editar');
        route::post('/borrar-{integracion}', 'borrar')->name('integracion.borrar');
    });
    Route::get('/mapa', [mapaController::class, 'index'])->middleware(['can:mapa.index']);
    Route::get('/bitacora', [bitacoraController::class, 'index'])->name('bitacora.index')->middleware(['can:bitacora.index']);
    Route::get("/crudOportunidades", [crudOportunidadesController::class, 'index'])->name("oportunidades.index");
    Route::get("/crudOportunidades/inicializar", [crudOportunidadesController::class, 'inicializar'])->name("oportunidades.inicializar");
    Route::get("/crudOportunidades/exportarParaPromotor", [crudOportunidadesController::class, 'exportarParaPromotor'])->name("oportunidades.exportarParaPromotor");
    Route::get("/crudOportunidades/cargarTabla", [crudOportunidadesController::class, 'cargarOportunidades'])->name("oportunidades.cargarOportunidades");
    Route::get("/crudOportunidades/cargar-seguimientos-{oportunidad}", [crudOportunidadesController::class, 'obtenerSeguimiento'])->name("oportunidades.obtenerSeguimiento");
    Route::post("/crudOportunidades/crearOportunidad", [crudOportunidadesController::class, 'agregar'])->name("oportunidades.agregar");
    Route::post("/crudOportunidades/cambiarOportunidad", [crudOportunidadesController::class, 'cambiarEstado'])->name("oportunidades.cambiarEstatus");
    Route::post("/crudOportunidades/agregar-actividad-{oportunidad}", [crudOportunidadesController::class, 'agregarActividad'])->name("oportunidades.agregarActividad");
});

