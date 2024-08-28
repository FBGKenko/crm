@extends('Pages.plantilla')
@section('tittle', 'Ficha: ' . $persona->apodo)

@section('cuerpo')
<br>
<section class="container-fuild px-4">
    <h2>Ficha: {{$persona->apodo}}, {{$persona->nombres}}  {{$persona->apellidoPaterno}}  {{$persona->apellidoMaterno}}</h2>
    <div class="d-flex">
        <div class="col-8">
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
                </div>

            </div>
            {{-- VALORES ROTADORES --}}
            <div class="card m-3">
                <div class="card-header d-flex justify-content-between">
                    <span id="encabezadoRotadores" class="align-self-center">
                        Datos de Control
                    </span>
                    <a href="{{route('contactos.vistaModificar', $persona->id)}}" class="btn btn-primary">
                        Modificar
                    </a>
                </div>
                <div class="card-body">
                    <div id="contenedorDatosControl">
                        <h6><span class="fw-bold fs-5">Fecha de registro:</span> {{$persona->fecha_registro}}</h6>
                        <h6><span class="fw-bold fs-5">Folio:</span> {{$persona->folio ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Promotor:</span> </h6>
                        <h6><span class="fw-bold fs-5">Origen:</span> </h6>
                        <h6><span class="fw-bold fs-5">Referencia de Origen:</span></h6>
                        <h6><span class="fw-bold fs-5">Campaña de referencia:</span></h6>
                        <h6><span class="fw-bold fs-5">Etiquetas de origen:</span></h6>
                        <h6><span class="fw-bold fs-5">Estatus:</span></h6>
                    </div>
                    <div id="contenedorDatosPersonales" style="display: none">
                        <h6><span class="fw-bold fs-5">Apodo:</span> {{$persona->apodo ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Nombre(s):</span> {{$persona->nombres ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Primer Apellido:</span> {{$persona->apellido_paterno ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Segundo Apellido:</span>{{$persona->apellido_materno ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Sexo:</span> {{$persona->genero ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Fecha de Nacimiento:</span> {{$persona->fecha_nacimiento ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Rango de Edad:</span> {{$persona->edadPromedio ?? 'SIN DATO'}}</h6>
                    </div>
                    <div id="contenedorDatosContacto" style="display: none">
                        <h6><span class="fw-bold fs-5">Celular principal:</span> {{$persona->telefonoCelular1 ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Celular alternativo 1:</span> {{$persona->telefonoCelular2 ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Celular alternativo 2:</span> {{$persona->telefonoCelular3 ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Correo electrónico principal:</span> {{$persona->correo ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Correo electrónico alternativo:</span> {{$persona->correoAlternativo ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Telefono Fijo:</span> {{$persona->telefono_fijo ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Facebook:</span> {{$persona->nombre_en_facebook ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">X/Twitter:</span> {{$persona->twitter ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Instagram:</span> {{$persona->instagram ?? 'SIN DATO'}}</h6>
                    </div>
                    <div id="contenedorDatosDomicilio" style="display: none">
                        <h6><span class="fw-bold fs-5">Calle principal:</span> {{$persona->identificacion->domicilio->calle1 ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Entre calle 1:</span> {{$persona->identificacion->domicilio->calle2 ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Entre calle 2:</span> {{$persona->identificacion->domicilio->calle3 ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Número exterior:</span> {{$persona->identificacion->domicilio->numero_exterior ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Número interior:</span> {{$persona->identificacion->domicilio->numero_interior ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Colonia:</span> {{$persona->identificacion->domicilio->colonia_id ? $persona->identificacion->domicilio->colonia->nombre : 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Código postal:</span> {{$persona->identificacion->domicilio->colonia_id ? $persona->identificacion->domicilio->colonia->codigo_postal : 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Ciudad:</span></h6>
                        <h6><span class="fw-bold fs-5">Municipio:</span></h6>
                        <h6><span class="fw-bold fs-5">País:</span> SIN DATO</h6>
                        <h6><span class="fw-bold fs-5">Referencias:</span> {{$persona->identificacion->domicilio->referencia ?? 'SIN DATO'}}</h6>
                    </div>
                    <div id="contenedorDatosIdentificacion" style="display: none">
                        <h6><span class="fw-bold fs-5">Curp:</span> {{$persona->identificacion->curp ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">RFC:</span> {{$persona->identificacion->clave_elector ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Lugar de nacimiento:</span> {{$persona->identificacion->lugarNacimiento ?? 'SIN DATO'}}</h6>
                    </div>
                    <div id="contenedorDatosRelacion" style="display: none">
                        <h6><span class="fw-bold fs-5">Cliente de @Empresa:</span></h6>
                        <h6><span class="fw-bold fs-5">Promotor de @Empresa:</span></h6>
                        <h6><span class="fw-bold fs-5">Colaborador de @Empresa:</span></h6>
                    </div>
                    <div id="contenedorOtrosDatos" style="display: none">
                        <h6><span class="fw-bold fs-5">Etiquetas:</span> {{$persona->etiquetas ?? 'SIN DATO'}}</h6>
                        <h6><span class="fw-bold fs-5">Observaciones:</span> {{$persona->observaciones ?? 'SIN DATO'}}</h6>
                    </div>
                </div>
            </div>
        </div>

        <aside class="col-4">
            <div class="card">
                <div class="card-header">
                    CONJUNTO DE DATOS
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills flex-column">
                        {{-- <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Active</a>
                        </li> --}}
                        <li class="nav-item active"> <a class="nav-link" id="linkDatosControl" href="#"> Datos de Control </a> </li>
                        <li class="nav-item"> <a class="nav-link" id="linkDatosPersonales" href="#"> Datos Personales </a> </li>
                        <li class="nav-item"> <a class="nav-link" id="linkDatosContacto" href="#"> Datos de Contacto </a> </li>
                        <li class="nav-item"> <a class="nav-link" id="linkDatosDomicilio" href="#"> Datos de Domicilio </a> </li>
                        <li class="nav-item"> <a class="nav-link" id="linkDatosIdentificacion" href="#"> Datos de Identificación </a> </li>
                        <li class="nav-item"> <a class="nav-link" id="linkDatosRelacion" href="#"> Datos de Relación </a> </li>
                        <li class="nav-item"> <a class="nav-link" id="linkOtrosDatos" href="#"> Otros datos </a> </li>
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

        $(document).ready(function () {
            $("#linkFichaTecnica").trigger('click');
        });

        $('.nav-link').click(function (e) {
            $('ul.nav').children().each(function (index, element) {
                console.log($(element).children());
                $(element).children().removeClass('active');
            });
            $(this).addClass('active');
            $('#contenedorFichaTecnica').hide();
            $('#contenedorDatosControl').hide();
            $('#contenedorDatosPersonales').hide();
            $('#contenedorDatosContacto').hide();
            $('#contenedorDatosDomicilio').hide();
            $('#contenedorDatosIdentificacion').hide();
            $('#contenedorDatosRelacion').hide();
            $('#contenedorOtrosDatos').hide();
            switch ($(this).attr('id')) {
                case "linkFichaTecnica":
                    $('#encabezadoRotadores').text('Ficha Técnica');
                    $('#contenedorFichaTecnica').show();
                    break;
                case "linkDatosControl":
                    $('#encabezadoRotadores').text('Datos de Control');
                    $('#contenedorDatosControl').show();
                    break;
                case "linkDatosPersonales":
                    $('#encabezadoRotadores').text('Datos Personales');
                    $('#contenedorDatosPersonales').show();
                    break;
                case "linkDatosContacto":
                    $('#encabezadoRotadores').text('Datos de Contacto');
                    $('#contenedorDatosContacto').show();
                    break;
                case "linkDatosDomicilio":
                    $('#encabezadoRotadores').text('Datos de Domicilio');
                    $('#contenedorDatosDomicilio').show();
                    break;
                case "linkDatosIdentificacion":
                    $('#encabezadoRotadores').text('Datos de Identificación');
                    $('#contenedorDatosIdentificacion').show();
                    break;
                case "linkDatosRelacion":
                    $('#encabezadoRotadores').text('Datos de Relación');
                    $('#contenedorDatosRelacion').show();
                    break;
                case "linkOtrosDatos":
                    $('#encabezadoRotadores').text('Otros Datos');
                    $('#contenedorOtrosDatos').show();
                    break;
            }
        });

        function cargarDatos(){

        }

    </script>
@endsection
