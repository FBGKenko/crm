@extends('Pages.plantilla')

@section('tittle')
Tabla de Simpatizantes
@endsection

@section('cuerpo')
    <style>
        .disabled {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
    @if (session()->has('mensaje'))
        <script>
            alert('{{session("mensaje")}}');
        </script>
    @endif
    <br>
    <div class="modal fade" id="modalAsignarEmpresa" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <form id="formularioAsignarEmpresas" action="" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Asignar relacion con empresa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5>Empresas:</h5>
                        <select id="seleccionarEmpresa" class="form-select">
                            <option value="0" selected disabled>Seleccione una empresa</option>
                        </select>
                        <div class="card">
                            <div class="card-header">
                                Empresas
                            </div>
                            <ul class="list-group list-group-flush">
                                <li id="contenedorEmpresas" class="list-group-item">
                                    <div class="row">
                                        <div class="col">NombreEmpresa</div>
                                        <div class="col">
                                            <h5 for="">¿Es un cliente?</h5>
                                            <div class="d-flex">
                                                <div class="form-check me-3">
                                                    <input class="form-check-input" type="radio" name="esCliente_1" id="esCliente_1">
                                                    <label class="form-check-label">
                                                        Si
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="esCliente_1" id="esCliente_1">
                                                    <label class="form-check-label">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h5 for="">¿Es un promotor?</h5>
                                            <div class="d-flex">
                                                <div class="form-check me-3">
                                                    <input class="form-check-input" type="radio" name="esPromotor_1" id="esPromotor_1">
                                                    <label class="form-check-label">
                                                        Si
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="esPromotor_1" id="esPromotor_1">
                                                    <label class="form-check-label">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h5 for="">¿Es un colaborador?</h5>
                                            <div class="d-flex">
                                                <div class="form-check me-3">
                                                    <input class="form-check-input" type="radio" name="esColaborador_1" id="esColaborador_1">
                                                    <label class="form-check-label">
                                                        Si
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="esColaborador_1" id="esColaborador_1">
                                                    <label class="form-check-label">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Tabla de Personas</h1>
        <button class="btn btn-primary" id="btnFiltrar">Filtrar</button>
        <div id="contenedorFiltros" class="container border border-1" style="display: none;">
            <h5>filtros pendientes</h5>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-end">
                    {{-- <a href="" target="_blank" class="me-3">
                        <button class="btn btn-primary">Exportar a Excel</button>
                    </a> --}}
                    <a href="{{route('contactos.agregar')}}">
                        <button class="btn btn-primary">Agregar Persona</button>
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{-- TABLA DE USUARIOS --}}
                <table id="tablaUsuarios" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <th>Consecutivo</th>
                        <th>Estatus</th>
                        <th>Apodo</th>
                        <th>Nombre Completo</th>
                        <th>Telefono</th>
                        <th>Opciones:</th>
                    </thead>
                    <tbody>
                        @foreach ($personas as $persona)
                            <tr>
                                <td>{{$persona->id}}</td>
                                <td>
                                    @if ($persona->supervisado)
                                        <div class="bg-success bg-gradient text-white fw-bold rounded p-3 py-1">Supervisado</div>
                                    @else
                                        <div class="bg-danger bg-gradient text-white fw-bold rounded p-3 py-1"> No Supervisado </div>
                                    @endif
                                </td>
                                <td>{{($persona->apodo) ? $persona->apodo : 'SIN REGISTRO'}}</td>
                                <td>{{($persona->nombre_completo) ? $persona->nombre_completo : 'SIN REGISTRO'}}</td>
                                <td>{{($persona->telefonoCelular1) ? $persona->telefonoCelular1 : 'SIN REGISTRO'}}</td>
                                <td>


                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item btnAsignarEmpresa" id="btnModalAsignar_{{$persona->id}}" href="#"> Asignar </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{route('contactos.fichaTecnica', $persona->id)}}"> Ver </a>
                                            </li>
                                            {{-- <li>
                                                <a class="dropdown-item" href="#">
                                                    @if ($persona->supervisado)
                                                        <form action="{{route('contactos.supervisar', $persona->id)}}" id="formularioSupervisar" method="post">
                                                            @csrf
                                                            <button id="botonSubmitSupervisar" class="btn btn-danger">Cancelar Supervisado</button>
                                                        </form>
                                                    @else
                                                        <form action="{{route('contactos.supervisar', $persona->id)}}" id="formularioSupervisar" method="post">
                                                            @csrf
                                                            <button id="botonSubmitSupervisar" class="btn btn-success">Supervisar</button>
                                                        </form>
                                                    @endif
                                                </a>
                                            </li> --}}
                                            <li>
                                                <a class="dropdown-item" href="{{route('contactos.vistaModificar', $persona->id)}}"> Editar </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <form action="{{route('contactos.borrar', $persona->id)}}" method="post">
                                                        @csrf
                                                        <span class="botonBorrar">Borrar</span>
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
                @error('errorBorrar')
                <div class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                @enderror
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="//cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css"></script>
<script text="text/javascript">
    @if (session()->has('personaModificarDenegada'))
        Swal.fire({
            'title':"Error",
            'text':"{{session('personaModificarDenegada')}}",
            'icon':"error"
        });
    @endif
    // FUNCION PARA CARGAR TABLA DE USUARIOS
    $(document).ready(function () {
        var table = $('#tablaUsuarios').DataTable( {
            //scrollX: true,
            lengthChange: true,
            // responsive: true,
            language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
            },
        } );

        // table.buttons().container()
        // .appendTo( '#example_wrapper .col-md-6:eq(0)' );
        // Swal.fire({
        //     title: 'Cargando...',
        //     allowOutsideClick: false,
        //     showConfirmButton: false,
        //     html: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
        // });
        // $.when(
        //         $.ajax({
        //             type: "get",
        //             url: "{{route('crudSimpatizantes.numeroSupervisados')}}",
        //             data: [],
        //             contentType: "application/x-www-form-urlencoded",
        //             success: function (response) {
        //                 $('#conteoSinSupervisar').text(response.sinSupervisar);
        //                 $('#conteoTotal').text(response.total);
        //             },
        //             error: function( data, textStatus, jqXHR){
        //                 if (jqXHR.status === 0) {
        //                     console.log('Not connect: Verify Network.');
        //                 } else if (jqXHR.status == 404) {
        //                     console.log('Requested page not found [404]');
        //                 } else if (jqXHR.status == 500) {
        //                     console.log('Internal Server Error [500].');
        //                 } else if (textStatus === 'parsererror') {
        //                     console.log('Requested JSON parse failed.');
        //                 } else if (textStatus === 'timeout') {
        //                     console.log('Time out error.');
        //                 } else if (textStatus === 'abort') {
        //                     console.log('Ajax request aborted.');
        //                 } else {
        //                     console.log('Uncaught Error: ' + jqXHR.responseText);
        //                 }
        //             }
        //         })
        // ).then(
        //     function( data, textStatus, jqXHR ) {
        //         // Swal.close();
        // });


    });

    $('.botonBorrar').click(function (e) {
        $(this).parent().trigger('submit');
    });
    $('#btnFiltrar').click(function (e) {
        $(this).next().slideToggle();
    });
    $('.btnAsignarEmpresa').click(function (e) {
        var idPersona = $(this).attr('id').split('_')[1];
        $.when(
            $.ajax({
                type: "get",
                url: "/contactos/asignar-empresas-" + idPersona,
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    //CARGAR EMPRESAS RELACIONADAS
                    $('#formularioAsignarEmpresas').attr('action', "/contactos/asignar-empresas-" + idPersona);
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
</script>
@endsection
