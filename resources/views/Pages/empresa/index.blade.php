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
                                                <a class="dropdown-item" href="#"> Asignar </a>
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

</script>
@endsection
