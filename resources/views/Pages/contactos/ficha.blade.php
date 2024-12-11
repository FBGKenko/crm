@extends('Pages.plantilla')
@section('tittle', 'Ficha: ' . $persona->apodo)

@section('cuerpo')
<br>
<section class="container-fuild px-4">
    <h2>Ficha: {{$persona->apodo}}, {{$persona->nombres}}  {{$persona->apellidoPaterno}}  {{$persona->apellidoMaterno}}</h2>
    <div class="d-flex flex-column-reverse flex-sm-row">
        <div class="col-sm-8">
            <div class="card m-3">
                {{-- CONJUNTO DE DATOS PRINCIPALES --}}
                <div class="card-header">
                    Datos principales
                </div>
                <div class="card-body">
                    <h6> <span class="fw-bold fs-5">Nombre completo:</span>
                    {{$persona->nombres}}  {{$persona->apellidoPaterno}}  {{$persona->apellidoMaterno}}</h6>
                    <h6> <span class="fw-bold fs-5">Alias:</span>
                    {{$persona->apodo}}</h6>
                    <h6><span class="fw-bold fs-5">Número de Cliente:</span> {{$persona->id}}</h6>
                </div>

            </div>
            {{-- VALORES ROTADORES --}}
            <div class="card m-3">
                <div class="card-header d-flex justify-content-between">
                    <span id="encabezadoRotadores" class="align-self-center">
                        Datos de Control
                    </span>
                    @if (Auth::user()->getRoleNames()->first() != 'CAPTURISTA' || (Auth::user()->getRoleNames()->first() == 'CAPTURISTA' && !$persona->supervisado))
                        <a href="{{route('contactos.vistaModificar', $persona->id)}}" id="enlaceAModificar" class="btn btn-primary">
                            Modificar
                        </a>
                    @endif
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane container pt-3 active" id="contenedorDatosControl">
                        <h6><span class="fw-bold fs-5">Fecha de registro:</span> {{$persona->fecha_registro}}</h6>
                        <h6><span class="fw-bold fs-5">Folio:</span> {{$persona->folio ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Promotor:</span> {{$persona->promotor_id ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Origen:</span> {{$persona->origen ?? 'SIN DATO'}} </h6>
                        <h6><span class="fw-bold fs-5">Referencia de Origen:</span> PENDIENTE</h6>
                        <h6><span class="fw-bold fs-5">Campaña de referencia:</span> PENDIENTE</h6>
                        <h6><span class="fw-bold fs-5">Etiquetas de origen:</span> {{$persona->etiquetaOrigen ?? 'SIN DATO'}} </h6>
                        <h6><span class="fw-bold fs-5">Estatus:</span> {{$persona->estatus}} </h6>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="contenedorDatosPersonales">
                        <h6><span class="fw-bold fs-5">Apodo:</span> {{$persona->apodo ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Nombre(s):</span> {{$persona->nombres ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Primer Apellido:</span> {{$persona->apellido_paterno ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Segundo Apellido:</span> {{$persona->apellido_materno ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Sexo:</span> {{$persona->genero ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Fecha de Nacimiento:</span> {{$persona->fecha_nacimiento ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Rango de Edad:</span> {{$persona->rangoEdad ?? 'SIN DATO'}}</h6>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="contenedorDatosContacto">
                        {{-- <h6><span class="fw-bold fs-5">Celular principal:</span> {{$persona->telefonoCelular1 ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Celular alternativo 1:</span> {{$persona->telefonoCelular2 ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Celular alternativo 2:</span> {{$persona->telefonoCelular3 ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Correo electrónico principal:</span> {{$persona->correo ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Correo electrónico alternativo:</span> {{$persona->correoAlternativo ?? 'SIN DATO'}}</h6> --}}
                        {{-- <h6><span class="fw-bold fs-5">Telefono Fijo:</span> {{$persona->telefono_fijo ?? 'SIN DATO'}}</h6> --}}
                        @foreach ($persona->telefonos as $telefono)
                            @if ($loop->index == 0)
                                <h6><span class="fw-bold fs-5">Telefono Principal:</span> {{$telefono->telefono ?? 'SIN DATO'}}, {{$telefono->etiqueta}}</h6>
                            @else
                                <h6><span class="fw-bold fs-5">Telefono {{$loop->index}}:</span> {{$telefono->telefono ?? 'SIN DATO'}}, {{$telefono->etiqueta}}</h6>
                            @endif

                        @endforeach
                        @foreach ($persona->correos as $correo)
                            @if ($loop->index == 0)
                                <h6><span class="fw-bold fs-5">Correo Principal:</span> {{$correo->correo ?? 'SIN DATO'}}, {{$telefono->etiqueta}}</h6>
                            @else
                                <h6><span class="fw-bold fs-5">Correo {{$loop->index}}:</span> {{$correo->correo ?? 'SIN DATO'}}, {{$telefono->etiqueta}}</h6>
                            @endif

                        @endforeach
                        <h6><span class="fw-bold fs-5">Facebook:</span> {{$persona->nombre_en_facebook ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">X/Twitter:</span> {{$persona->twitter ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Instagram:</span> {{$persona->instagram ?? 'SIN DATO'}}</h6>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="contenedorDatosDomicilio">
                        <h6><span class="fw-bold fs-5">Calle principal:</span> {{$persona->relacionDomicilio[0]->domicilio->calle1 ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Entre calle 1:</span> {{$persona->relacionDomicilio[0]->domicilio->calle2 ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Entre calle 2:</span> {{$persona->relacionDomicilio[0]->domicilio->calle3 ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Número exterior:</span> {{$persona->relacionDomicilio[0]->domicilio->numero_exterior ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Número interior:</span> {{$persona->relacionDomicilio[0]->domicilio->numero_interior ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Colonia:</span> {{$persona->relacionDomicilio[0]->domicilio->colonia_id ? $persona->relacionDomicilio[0]->domicilio->colonia->nombre : 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Código postal:</span> {{$persona->relacionDomicilio[0]->domicilio->colonia_id ? $persona->relacionDomicilio[0]->domicilio->colonia->codigo_postal : 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Ciudad:</span> PENDIENTE</h6>
                        <h6><span class="fw-bold fs-5">Municipio:</span> PENDIENTE</h6>
                        <h6><span class="fw-bold fs-5">País:</span> PENDIENTE</h6>
                        <h6><span class="fw-bold fs-5">Referencias:</span> {{$persona->relacion_domicilio[0]->domicilio->referencia ?? 'SIN DATO'}}</h6>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="contenedorDatosIdentificacion">
                        <h6><span class="fw-bold fs-5">Curp:</span> {{$persona->identificacion->curp ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">RFC:</span> {{$persona->identificacion->rfc ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Lugar de nacimiento:</span> {{$persona->identificacion->lugarNacimiento ?? 'SIN DATO'}}</h6>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="contenedorDatosUbicacion">
                        <h6><span class="fw-bold fs-5">Clave Elector:</span> {{$persona->identificacion->clave_elector ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Sección:</span> {{$persona->identificacion->seccion_id ?? 'SIN DATO'}}</h6>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="contenedorDatosRelacion">
                        <h6><span class="fw-bold fs-5">Cliente de @Empresa:</span> {{$persona->cliente ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Promotor de @Empresa:</span> {{$persona->promotor ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Colaborador de @Empresa:</span> {{$persona->colaborador ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Afiliado de @Empresa:</span> {{$persona->afiliado ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Simpatizante de @Empresa:</span> {{$persona->simpatizante ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Relación Personalizada de @Empresa:</span> {{$persona->programa ?? 'SIN DATO'}}</h6>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="contenedorDatosEstructura">
                        <h6><span class="fw-bold fs-5">Rol Estructura:</span> {{$persona->rolEstructura ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Coordinador de:</span> {{$persona->coordinadorDe ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Función asignada:</span> {{$persona->funcionAsignada ?? 'SIN DATO'}}</h6>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="contenedorDatosRelacionEmpresa">
                        @foreach ($persona->relacionPersonaEmpresa as $relacion)
                            <h6><span class="fw-bold fs-5">{{$relacion->empresa->nombreEmpresa}}:</span> {{$relacion->puesto ?? 'SIN DATO'}}</h6>
                        @endforeach
                    </div>
                    <div class="tab-pane container pt-3 fade" id="contenedorDatosFacturacion">
                        <h6><span class="fw-bold fs-5">Calle principal:</span> {{$persona->relacionDomicilio[1]->domicilio->calle1 ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Entre calle 1:</span> {{$persona->relacionDomicilio[1]->domicilio->calle2 ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Entre calle 2:</span> {{$persona->relacionDomicilio[1]->domicilio->calle3 ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Número exterior:</span> {{$persona->relacionDomicilio[1]->domicilio->numero_exterior ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Número interior:</span> {{$persona->relacionDomicilio[1]->domicilio->numero_interior ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Colonia:</span> {{$persona->relacionDomicilio[1]->domicilio->colonia_id ? $persona->relacionDomicilio[1]->domicilio->colonia->nombre : 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Código postal:</span> {{$persona->relacionDomicilio[1]->domicilio->colonia_id ? $persona->relacionDomicilio[1]->domicilio->colonia->codigo_postal : 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Ciudad:</span> PENDIENTE</h6>
                        <h6><span class="fw-bold fs-5">Municipio:</span> PENDIENTE</h6>
                        <h6><span class="fw-bold fs-5">País:</span> PENDIENTE</h6>
                        <h6><span class="fw-bold fs-5">Referencias:</span> {{$persona->relacion_domicilio[1]->domicilio->referencia ?? 'SIN DATO'}}</h6>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="contenedorOtrosDatos">
                        <h6><span class="fw-bold fs-5">Etiquetas:</span> {{$persona->etiquetas ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Observaciones:</span> {{$persona->observaciones ?? 'SIN DATO'}}</h6>
                    </div>
                </div>
            </div>
        </div>

        <aside class="col-sm-4">
            <div class="card">
                <div class="card-header">
                    CONJUNTO DE DATOS
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills flex-column">
                        {{-- <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Active</a>
                        </li> --}}
                        <li class="nav-item active"> <a class="nav-link active" data-bs-toggle="tab" href="#contenedorDatosControl"> Datos de Control </a> </li>
                        <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#contenedorDatosPersonales"> Datos de Personales </a> </li>
                        <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#contenedorDatosContacto"> Datos de Contacto </a> </li>
                        <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#contenedorDatosDomicilio"> Datos de Domicilio </a> </li>
                        <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#contenedorDatosIdentificacion"> Datos de Identificación </a> </li>
                        <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#contenedorDatosUbicacion"> Datos de Ubicación Electoral </a> </li>
                        <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#contenedorDatosRelacion"> Datos de Relación </a> </li>
                        <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#contenedorDatosEstructura"> Datos de Estructura </a> </li>
                        <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#contenedorDatosRelacionEmpresa"> Datos de Relación con Empresas </a> </li>
                        <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#contenedorDatosFacturacion"> Datos de Facturación </a> </li>
                        <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#contenedorOtrosDatos"> Otros datos </a> </li>
                    </ul>
                </div>
            </div>
        </aside>



        {{-- <div class="d-flex align-items-start">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">Home</button>
                <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Profile</button>
                <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</button>
                <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</button>
            </div>
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">...</div>
                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">...</div>
                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">...</div>
                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">...</div>
            </div>
        </div> --}}






    </div>
</section>
@endsection



@section('scripts')
    <script text="text/javascript">
        $('.nav-item .nav-link').click(function(){
            switch ($(this).attr('href')) {
                case "#contenedorDatosControl":
                    $('#enlaceAModificar').attr('href', "{{route('contactos.vistaModificar', $persona->id)}}?conjunto=datosControl")
                break;
                case "#contenedorDatosPersonales":
                    $('#enlaceAModificar').attr('href', "{{route('contactos.vistaModificar', $persona->id)}}?conjunto=datosPersonales")
                break;
                case "#contenedorOtrosDatos":
                    $('#enlaceAModificar').attr('href', "{{route('contactos.vistaModificar', $persona->id)}}?conjunto=otrosDatos")
                break;
                case "#contenedorDatosContacto":
                    $('#enlaceAModificar').attr('href', "{{route('contactos.vistaModificar', $persona->id)}}?conjunto=datosContacto")
                break;
                case "#contenedorDatosDomicilio":
                    $('#enlaceAModificar').attr('href', "{{route('contactos.vistaModificar', $persona->id)}}?conjunto=datosDomicilio")
                break;
                case "#contenedorDatosIdentificacion":
                    $('#enlaceAModificar').attr('href', "{{route('contactos.vistaModificar', $persona->id)}}?conjunto=datosIdentificacion")
                break;
                case "#contenedorDatosUbicacion":
                    $('#enlaceAModificar').attr('href', "{{route('contactos.vistaModificar', $persona->id)}}?conjunto=datosUbicaciones")
                break;
                case "#contenedorDatosRelacion":
                    $('#enlaceAModificar').attr('href', "{{route('contactos.vistaModificar', $persona->id)}}?conjunto=datosRelacion")
                break;
                case "#contenedorDatosEstructura":
                    $('#enlaceAModificar').attr('href', "{{route('contactos.vistaModificar', $persona->id)}}?conjunto=datosEstructura")
                break;
                case "#contenedorDatosRelacionEmpresa":
                    $('#enlaceAModificar').attr('href', "{{route('contactos.vistaModificar', $persona->id)}}?conjunto=datosRelacionEmpresa")
                break;
                case "#contenedorDatosFacturacion":
                    $('#enlaceAModificar').attr('href', "{{route('contactos.vistaModificar', $persona->id)}}?conjunto=datosFacturacion")
                break;
                default:
                    break;
            }


        });

        $('.nav-link').click(function(){
            switch ($(this).attr('href')) {
                    case "#contenedorDatosControl":
                        $('#encabezadoRotadores').text("Datos de Control");
                        break;
                    case "#contenedorDatosPersonales":
                        $('#encabezadoRotadores').text("Datos de Personales");
                        break;
                    case "#contenedorDatosContacto":
                        $('#encabezadoRotadores').text("Datos de Contacto");
                        break;
                    case "#contenedorDatosDomicilio":
                        $('#encabezadoRotadores').text("Datos de Domicilio");
                        break;
                    case "#contenedorDatosIdentificacion":
                        $('#encabezadoRotadores').text("Datos de Identificación");
                        break;
                    case "#contenedorDatosUbicacion":
                        $('#encabezadoRotadores').text("Datos de Ubicación Electoral");
                        break;
                    case "#contenedorDatosRelacion":
                        $('#encabezadoRotadores').text("Datos de Relación");
                        break;
                    case "#contenedorDatosEstructura":
                        $('#encabezadoRotadores').text("Datos de Estructura");
                        break;
                    case "#contenedorDatosRelacionEmpresa":
                        $('#encabezadoRotadores').text("Datos de Relación con Empresa");
                        break;
                    case "#contenedorDatosFacturacion":
                        $('#encabezadoRotadores').text("Datos de Facturación");
                        break;
                    case "#contenedorOtrosDatos":
                        $('#encabezadoRotadores').text("Otros Datos");
                        break;
                default:
                    break;
            }
        });
    </script>
@endsection
