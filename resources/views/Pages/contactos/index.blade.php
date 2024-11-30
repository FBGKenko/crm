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
        .estatus{
            border-radius: 10px;
            padding: 0.5rem;
            font-weight: bold;
        }
        .estatus-frio{
            background: #25c7f6;
        }
        .estatus-tibio{
            background: #e3c807;
        }
        .estatus-caliente{
            background: #e33207;
            color: white;
        }
    </style>
    @if (session()->has('mensaje'))
        <script>
            alert('{{session("mensaje")}}');
        </script>
    @endif
    <br>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Tabla de Personas</h1>
        {{-- <button class="btn btn-primary" id="btnFiltrar">Filtrar</button> --}}
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




                <table id="tablaUsuarios" class="wrap table table-striped" style="width: 100%;">
                    <thead>
                        <th>Número de Cliente</th>
                        <th>Estatus</th>
                        <th>Apodo</th>
                        <th>Nombre Completo</th>
                        <th>Supervisión</th>
                        <th>Opciones:</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>






                {{-- <table id="tablaUsuarios" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <th>Número de Cliente</th>
                        <th>Estatus</th>
                        <th>Apodo</th>
                        <th>Nombre Completo</th>
                        <th>Supervisión</th>
                        <th>Opciones:</th>
                    </thead>
                    <tbody>
                        @foreach ($personas as $persona)
                            <tr>
                                <td>{{$persona->id}}</td>
                                <td>
                                    <span>{{$persona->estatus}}</span>
                                </td>
                                <td>{{($persona->apodo) ? $persona->apodo : 'SIN REGISTRO'}}</td>
                                <td>{{($persona->nombre_completo) ? $persona->nombre_completo : 'SIN REGISTRO'}}</td>
                                <td>
                                    @if (Auth::user()->getRoleNames()->first() == 'SUPER ADMINISTRADOR' || Auth::user()->getRoleNames()->first() == 'ADMINISTRADOR' || Auth::user()->getRoleNames()->first() == 'SUPERVISOR')
                                        <form action="{{route('contactos.supervisar', $persona->id)}}" method="post">
                                            @csrf
                                            @if ($persona->supervisado)
                                                <button class="btn btn-success fw-bold btnSupervisar">Supervisado</button>
                                            @else
                                                <button class="btn btn-danger fw-bold btnSupervisar">Sin Supervisar</button>
                                            @endif
                                        </form>
                                    @else
                                        @if ($persona->supervisado)
                                            <button class="btn btn-success fw-bold btnSupervisar">Supervisado</button>
                                        @else
                                            <button class="btn btn-danger fw-bold btnSupervisar">Sin Supervisar</button>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item" href="{{route('contactos.fichaTecnica', $persona->id)}}"> Ver </a>
                                            </li>

                                            @if (Auth::user()->getRoleNames()->first() != 'CAPTURISTA' || (Auth::user()->getRoleNames()->first() == 'CAPTURISTA' && !$persona->supervisado))
                                                <li>
                                                    <a class="dropdown-item" href="{{route('contactos.vistaModificar', $persona->id)}}"> Editar </a>
                                                </li>
                                            @endif
                                            @if (Auth::user()->getRoleNames()->first() != 'CAPTURISTA' || (Auth::user()->getRoleNames()->first() == 'CAPTURISTA' && !$persona->supervisado))
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <form action="{{route('contactos.borrar', $persona->id)}}" method="post">
                                                            @csrf
                                                            <span class="botonBorrar">Borrar</span>
                                                        </form>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @error('errorBorrar')
                <div class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                @enderror --}}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="//cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css"></script>
<script text="text/javascript">
    var rolUsuarioActual = @json(Auth::user()->getRoleNames()->first());
    @if (session()->has('personaModificarDenegada'))
        Swal.fire({
            'title':"Error",
            'text':"{{session('personaModificarDenegada')}}",
            'icon':"error"
        });
    @endif
    // FUNCION PARA CARGAR TABLA DE USUARIOS
    $(document).ready(function () {
        $('#tablaUsuarios').DataTable({
            searching: true,
            paging: true,
            pageLength: 10,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
            },
            order: [],
            scrollX: true,
            scrollY: '450px',
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "{{route('contactos.cargarTabla')}}",
                data: function (d) {
                    // d.FechaInicio = $('#FechaInicio').val();
                    // d.FechaFinal = $('#FechaFinal').val();
                    // d.TieneDenuncia = $('#TieneDenuncia').val();
                    // d.Riesgo = $('#Riesgo').val();
                    // d.Dependencias = $('#Dependencias').val();
                },
                error: function(xhr, error, thrown){
                    if(thrown == 'Unauthorized'){
                        alertaCargando();
                        location.reload();
                    }
                }
            },
            columns: [
                { data: 'id' },
                { data: null,
                    render: function(data, type, row){
                        var clases = "estatus ";
                        switch (data.estatus) {
                            case 'FRIO':
                                clases += "estatus-frio";
                            break;
                            case 'TIBIO':
                                clases += "estatus-tibio";
                            break;
                            case 'CALIENTE':
                                clases += "estatus-caliente";
                            break;
                        }
                        return  $('<span>').text(data.estatus).addClass(clases).prop('outerHTML');
                    }
                },
                { data: null,
                    render: function(data, type, row){
                        return  (data.apodo && data.apodo != '') ? data.apodo : 'SIN REGISTRO';
                    }
                },
                { data: null,
                    render: function(data, type, row){
                        return  (data.nombre_completo && data.nombre_completo != '') ? data.nombre_completo : 'SIN REGISTRO';
                    }
                },
                { data: null,
                    render: function(data, type, row){
                        var botonSupervisar = "";
                        if(data.supervisado)
                            botonSupervisar = $('<button>').addClass('btn btn-success fw-bold btnSupervisar').text('Supervisado');
                        else
                            botonSupervisar = $('<button>').addClass('btn btn-danger fw-bold btnSupervisar').text('Sin Supervisar');
                        @if (Auth::user()->getRoleNames()->first() == 'SUPER ADMINISTRADOR' || Auth::user()->getRoleNames()->first() == 'ADMINISTRADOR' || Auth::user()->getRoleNames()->first() == 'SUPERVISOR')
                            return $('<form method="post">').attr('action','{{url("/")}}/contactos/supervisar-' + data.id).append(
                                $('<input type="hidden">').attr({name: '_token', value: '{{csrf_token()}}'}),
                                botonSupervisar
                            ).prop('outerHTML');
                        @else
                            return botonSupervisar.prop('outerHTML');
                        @endif
                    }
                },
                { data: null,
                    render: function(data, type, row){
                        var botonEditar = "";
                        var botonBorrar = "";

                        if(rolUsuarioActual != 'CAPTURISTA' || (rolUsuarioActual == 'CAPTURISTA' && !data.supervisado)){
                            botonEditar = $('<li>').append($('<a class="dropdown-item">').attr('href', '{{url("/")}}/contactos/modificar-' + data.id).text('Editar'));
                        }
                        if(rolUsuarioActual != 'CAPTURISTA' || (rolUsuarioActual == 'CAPTURISTA' && !data.supervisado)){
                            botonBorrar = $('<li>').append(
                                $('<a class="dropdown-item">').append(
                                    $('<form method="post">').attr('action', '{{url("/")}}/contactos/borrar-' + data.id).append(
                                        $('<input type="hidden">').attr({name: '_token', value: '{{csrf_token()}}'}),
                                        $('<span class="botonBorrar">').text('Borrar')
                                    )
                                )
                            );
                        }
                        return $('<div class="dropdown">').append(
                            $('<button type="button">').addClass('btn btn-secondary dropdown-toggle').attr({id: 'dropdownMenuButton1', 'data-bs-toggle': 'dropdown', 'aria-expanded': 'false'}).text('Acciones'),
                            $('<ul aria-labelledby="dropdownMenuButton1">').addClass('dropdown-menu').append(
                                $('<li>').append($('<a class="dropdown-item">').attr('href', '{{url("/")}}/contactos/ficha-' + data.id).text('Ver')),
                                botonEditar,
                                botonBorrar,
                            )
                        ).prop('outerHTML');
                    }
                },
            ],
            columnDefs: [
                { className: 'text-center', targets: '_all' }
            ],
            rowCallback: function(row, data, index){


            }
        });

        $('#tablaUsuarios tbody').on('click', 'span.botonBorrar', function (e) {
            Swal.fire({
                title: '¿Estás seguro de eliminar el registro?',
                text: "No podrás revertir esto!",
                icon: 'warning',
                showDenyButton: true,
                denyButtonText: 'Eliminar',
                denyButtonColor: "#28b779",
                confirmButtonColor: '#d33',
                confirmButtonText: 'Cancelar!'
            }).then((result) => {
                if(result.isDenied)
                    $(this).parent().trigger('submit');
            });
        });






        // var table = $('#tablaUsuarios').DataTable( {
        //     scrollX: true,
        //     lengthChange: true,
        //     scrollY: '50vh',
        //     language: {
        //     url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
        //     },
        // } );

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


    $('#btnFiltrar').click(function (e) {
        $(this).next().slideToggle();
    });
    // $('.btnAsignarEmpresa').click(function (e) {

    //     var idPersona = $(this).attr('id').split('_')[1];
    //     $.when(
    //         $.ajax({
    //             type: "get",
    //             url: "/contactos/asignar-empresas-" + idPersona,
    //             data: [],
    //             contentType: "application/x-www-form-urlencoded",
    //             success: function (response) {
    //                 //CARGAR EMPRESAS RELACIONADAS
    //                 $('#formularioAsignarEmpresas').attr('action', "/contactos/asignar-empresas-" + idPersona);
    //                 $('#modalAsignarEmpresa').modal('show');
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
    //     ).then(
    //         function( data, textStatus, jqXHR ) {
    //             // Swal.close();
    //     });

    // });
</script>
@endsection
