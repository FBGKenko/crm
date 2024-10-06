@extends('Pages.plantilla')

@section('tittle')
Lista Empresas
@endsection

@section('cuerpo')
    <style>
        .disabled {
            pointer-events: none; /* Evita que el enlace sea clickeable */
            opacity: 0.5; /* Aplica opacidad para indicar visualmente que está deshabilitado */
            cursor: not-allowed; /* Cambia el cursor a 'no permitido' */
        }
    </style>
    <br>
    <div class="modal fade" id="modalAsignarEmpresa" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form id="formularioAsignarEmpresas" action="" method="post">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Contactos relacionados</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <h5>Agregar persona:</h5>
                        <select id="seleccionarEmpresa" class="form-select">
                            <option value="0" selected disabled>Buscar por nombre</option>
                            @foreach ($listaPersonas as $persona)
                                <option value="{{$persona->id}}">{{$persona->NombreCompleto}}</option>
                            @endforeach
                        </select>
                        <div class="card">
                            <div class="card-header">
                                Contactos
                            </div>
                            <ul class="list-group list-group-flush">
                                <li id="contenedorEmpresas" class="list-group-item">

                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Tabla de Empresas</h1>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-end">
                    {{-- <a href="" target="_blank" class="me-3">
                        <button class="btn btn-primary">Exportar a Excel</button>
                    </a> --}}
                    <a href="{{route('empresas.agregar')}}">
                        <button class="btn btn-primary">Agregar Empresa</button>
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{-- TABLA DE USUARIOS --}}
                <table id="tablaEmpresas" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <th>Identificador</th>
                        <th>Nombre Empresa</th>
                        <th>Representante</th>
                        <th>Opciones:</th>
                    </thead>
                    <tbody>
                        @foreach ($listaEmpresa as $empresa)
                            <tr>
                                <td>{{$empresa->id}}</td>
                                <td>{{($empresa->nombreEmpresa) ? $empresa->nombreEmpresa : 'SIN REGISTRO'}}</td>
                                <td>{{($empresa->representante) ?
                                $empresa->representante->nombres.' '.$empresa->representante->apellido_paterno.' '.$empresa->representante->apellido_materno.', '.$empresa->representante->apodo
                                :
                                'SIN REPRESENTANTE'}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item btnAsignarEmpresa" id="btnModalAsignar_{{$empresa->id}}" href="#"> Asignar </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{route('empresas.modificar', $empresa->id)}}"> Modificar </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <form action="{{route('empresas.borrar', $empresa->id)}}" method="post">
                                                        @csrf
                                                        <span class="enviarFormularioBorrar"> Borrar </span>
                                                    </form>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="//cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css"></script>
<script text="text/javascript">
    var contadorRenglones = 0;
    // FUNCION PARA CARGAR TABLA DE USUARIOS
    $(document).ready(function () {
        var table = $('#tablaEmpresas').DataTable( {
            //scrollX: true,
            lengthChange: true,
            // responsive: true,
            language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
            },
        } );
    });

    $('.enviarFormularioBorrar').click(function (e) {
        $(this).parent().trigger('submit');
    });

    $('.btnAsignarEmpresa').click(function (e) {
        var idEmpresa = $(this).attr('id').split('_')[1];
        $.when(
            $.ajax({
                type: "get",
                url: "/empresas/asignar-contactos-" + idEmpresa,
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    //CARGAR EMPRESAS RELACIONADAS
                    $('#contenedorEmpresas').html("");
                    contadorRenglones = 0;
                    if(response.length == 0){
                        $('#contenedorEmpresas').append(
                            $(`<label id="labelNoPersonasEmpresas">`).addClass('form-label text-secondary').text('No hay personas relacionadas con la empresa')
                        );
                    }
                    response.forEach(persona => {
                        contadorRenglones++;
                        var datosPersona = {
                            nombre: `${persona.personas.nombres} ${persona.personas.apellido_paterno} ${persona.personas.apellido_materno}, ${persona.personas.apodo}`,
                            idPersona: persona.persona_id,
                            puesto: persona.puesto,
                            esColaboradorSi: (persona.esColaborador == "SI") ? true : false,
                            esColaboradorNo: (persona.esColaborador == "NO") ? true : false,
                            esPromotorSi: (persona.esPromotor == "SI") ? true : false,
                            esPromotorNo: (persona.esPromotor == "NO") ? true : false,
                            esClienteSi: (persona.esCliente == "SI") ? true : false,
                            esClienteNo: (persona.esCliente == "NO") ? true : false,
                        };
                        var renglonNuevo = cargarRenglonAsignarPersona(contadorRenglones, datosPersona);
                        $('#contenedorEmpresas').append(renglonNuevo);
                    });
                    $('#formularioAsignarEmpresas').attr('action', "/empresas/asignar-contactos-" + idEmpresa);
                    $('#modalAsignarEmpresa').modal('show');
                },
                error: function( data, textStatus, jqXHR){
                    if (jqXHR.status === 0) {
                        console.log('Not connect: Verify Network.');
                    } else if (jqXHR.status == 404) {
                        console.log('Requested page not found [404]');
                    } else if (jqXHR.status == 500) {
                        console.log('Internal Server Error [500].');
                    } else if (textStatus === 'parsererror') {
                        console.log('Requested JSON parse failed.');
                    } else if (textStatus === 'timeout') {
                        console.log('Time out error.');
                    } else if (textStatus === 'abort') {
                        console.log('Ajax request aborted.');
                    } else {
                        console.log('Uncaught Error: ' + jqXHR.responseText);
                    }
                }
            })
        ).then(
            function( data, textStatus, jqXHR ) {
                // Swal.close();
        });
    });

    function cargarRenglonAsignarPersona(contador, datosPersona){
        return $('<div class="row">').append(
            $('<input type="hidden">').attr({id: "idPersonaEscondido", name: `Relaciones[${contador}][persona_id]`}).val(datosPersona.idPersona),
            $('<div class="col">').text(datosPersona.nombre),
            // $('<div class="col">').append(
            //     $('<h5>').text('¿Es un cliente?'),
            //     $('<div class="d-flex">').append(
            //         $('<div class="form-check me-3">').append(
            //             $('<input type="radio">').addClass("form-check-input")
            //                 .attr({name: `Relaciones[${contador}][esCliente]`, id: `esClienteSi_` + contador})
            //                 .val('SI').prop('checked', datosPersona.esClienteSi),
            //             $('<label class="form-check-label">').text("SI")
            //         ),
            //         $('<div class="form-check">').append(
            //             $('<input type="radio">').addClass("form-check-input")
            //                 .attr({name: `Relaciones[${contador}][esCliente]`, id: `esClienteNo_` + contador})
            //                 .val('NO').prop('checked', datosPersona.esClienteNo),
            //             $('<label class="form-check-label">').text("No")
            //         )
            //     )
            // ),
            // $('<div class="col">').append(
            //     $('<h5>').text('¿Es un promotor?'),
            //     $('<div class="d-flex">').append(
            //         $('<div class="form-check me-3">').append(
            //             $('<input type="radio">').addClass("form-check-input")
            //                 .attr({name: `Relaciones[${contador}][esPromotor]`, id: `esPromotorSi_` + contador})
            //                 .val('SI').prop('checked', datosPersona.esPromotorSi),
            //             $('<label class="form-check-label">').text("SI")
            //         ),
            //         $('<div class="form-check">').append(
            //             $('<input type="radio">').addClass("form-check-input")
            //                 .attr({name: `Relaciones[${contador}][esPromotor]`, id: `esPromotorNo_` + contador})
            //                 .val('NO').prop('checked', datosPersona.esPromotorNo),
            //             $('<label class="form-check-label">').text("No")
            //         )
            //     )
            // ),
            $('<div class="col">').append(
                $('<h5>').text('¿Es un colaborador?'),
                $('<div class="d-flex">').append(
                    $('<div class="form-check me-3">').append(
                        $('<input type="radio">').addClass("form-check-input")
                            .attr({name: `Relaciones[${contador}][esColaborador]`, id: `esColaboradorSi_` + contador})
                            .val('SI').prop('checked', datosPersona.esColaboradorSi),
                        $('<label class="form-check-label">').text("SI")
                    ),
                    $('<div class="form-check">').append(
                        $('<input type="radio">').addClass("form-check-input")
                            .attr({name: `Relaciones[${contador}][esColaborador]`, id: `esColaboradorNo_` + contador})
                            .val('NO').prop('checked', datosPersona.esColaboradorNo),
                        $('<label class="form-check-label">').text("No")
                    )
                )
            ),
            $('<div class="col">').append(
                $('<h5>').text('Cargo:'),
                $('<input type="text" class="form-control">')
                    .attr({id: "cargoPersona_" + contador, name: `Relaciones[${contador}][puesto]`}).val(datosPersona.puesto)
            )
        );
    }

    $('#seleccionarEmpresa').change(function (e){
        contadorRenglones++;
        var datosPersona = {
            nombre: $('#seleccionarEmpresa option:selected').text(),
            idPersona: $(this).val(),
            puesto: null,
            esColaboradorSi: false,
            esColaboradorNo: false,
            esPromotorSi: false,
            esPromotorNo: false,
            esClienteSi: false,
            esClienteNo: false,
        };
        $('#labelNoPersonasEmpresas').remove();
        var renglonNuevo = cargarRenglonAsignarPersona(contadorRenglones, datosPersona);
        $('#contenedorEmpresas').append(renglonNuevo);
        $("#seleccionarEmpresa").val(0);
    });
</script>
@endsection
