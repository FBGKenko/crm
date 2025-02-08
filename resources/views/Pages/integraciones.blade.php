
@extends('Pages.plantilla')
@section('tittle', 'Usuarios del Sistema')
@section('cuerpo')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Integraciones</h1>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-success" id="btnAbrirModalIntegracion">Agregar integración</button>
                </div>
            </div>
            <div class="card-body">
                <table id="tablaIntegraciones" class="table table-striped table-bordered " style="width:100%">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($sitios) == 0)
                            <tr>
                                <td colspan="2">No hay integraciones registradas</td>
                            </tr>
                        @endif
                        @foreach ($sitios as $sitio)
                            <tr>
                                <td>{{$sitio->nombre}}</td>
                                <td class="d-flex">
                                    <a href="{{$sitio->url}}" target="_blank" class="btn btn-secondary me-3">Acceder</a>
                                    <form action="{{route('integracion.borrar', $sitio->id)}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="idSitio" value="{{$sitio->id}}">
                                        <button type="button" class="btn btn-danger cargarBotonBorrar">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  <div class="modal fade" id="modalAgregarIntegracion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tituloModalAgregarIntegracion">Agregar Pagina de Integración</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route("integracion.crear")}}" id="formularioAgregarIntegracion">
                    @csrf
                    <label for="">Nombre de sitio de integración:</label>
                    <input type="text" class="form-control" name="nombreSitioIntegracion" id="nombreSitioIntegracion">
                    <label for="">Url:</label>
                    <input type="text" class="form-control" name="urlSitio" id="urlSitio">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" id="btnAgregarIntegracion" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
  </div>
@endsection

@section('scripts')
    <script src="/js/generador-contrasenias.js" text="text/javascript"></script>
    <script text="text/javascript">
        var sitioABorrar = null;
        $(document).ready(function () {
            $('.cargarBotonBorrar').click(preguntarBorrarIntegracion);
        });

        $('#btnAbrirModalIntegracion').click(function (e) {
            $('#nombreSitioIntegracion').val('');
            $('#urlSitio').val('');
            $('#modalAgregarIntegracion').modal('show');
        });

        $("#btnAgregarIntegracion").click(function (e) {
            if($('#nombreSitioIntegracion').val() == '' || $('#urlSitio').val() == ''){
                swal.fire({
                    title: "Error",
                    text: "Debes llenar todos los campos",
                    icon: "error",
                });
                return;
            }
            alertaCargando();
            data = $('#formularioAgregarIntegracion').serializeArray();
            peticionAjax($('#formularioAgregarIntegracion').attr('action'), integracionAgregada, 'POST', data);
        });

        function integracionAgregada(data){
            alertaCargando(false);
            console.log(data);
            if(data.resultado){
                swal.fire({
                    title: "Integración agregada",
                    text: "La integración se ha agregado correctamente",
                    icon: "success",
                });
                if($('#tablaIntegraciones tbody tr').length == 1 && $('#tablaIntegraciones tbody tr td').text() == 'No hay integraciones registradas'){
                    $('#tablaIntegraciones tbody tr').remove();
                }
                $('#modalAgregarIntegracion').modal('hide');
                $('#tablaIntegraciones tbody').append(
                    $('<tr>').append(
                        $('<td>').text(data.sitio.nombre),
                        $('<td class="d-flex">').append(
                            $('<a>').attr('href', data.sitio.url).attr('target', '_blank').addClass('btn btn-secondary me-3').text('Acceder'),
                            $('<form>').attr('action', '{{url("/")}}/integraciones/borrar-' + data.sitio.id).attr('method', 'POST').append(
                                $('<input>').attr('type', 'hidden').attr('name', '_token').attr('value', '{{csrf_token()}}'),
                                $('<input>').attr('type', 'hidden').attr('name', 'idSitio').attr('value', data.sitio.id),
                                $('<button type="button">').addClass('btn btn-danger').text('Eliminar').on('click', preguntarBorrarIntegracion)
                            )
                        )
                    )
                )
            }
            else{
                swal.fire({
                    title: "Error",
                    text: "Ha ocurrido un error al agregar la integración",
                    icon: "error",
                });
            }
        }

        function preguntarBorrarIntegracion(){
            var idSitio = $(this).prev().val();
            var data = $(this).parent().serializeArray();
            sitioABorrar = $(this);
            swal.fire({
                title: "¿Estás seguro?",
                text: "¿Deseas eliminar la integración?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'No',
            }).then((result) => {
                if(result.isConfirmed){
                    alertaCargando();
                    peticionAjax('{{url("/")}}/integraciones/borrar-' + idSitio, integracionBorrada, 'POST', data);
                }
                else if(result.isDenied){
                    sitioABorrar = null;
                }
            });
        }

        function integracionBorrada(data){
            alertaCargando(false);
            if(data.resultado){
                swal.fire({
                    title: "Integración eliminada",
                    text: "La integración se ha eliminado correctamente",
                    icon: "success",
                });
                sitioABorrar.parent().parent().parent().remove();
                if($('#tablaIntegraciones tbody tr').length == 0){
                    $('#tablaIntegraciones tbody').append(
                        $('<tr>').append(
                            $('<td>').attr('colspan', 2).text('No hay integraciones registradas')
                        )
                    )
                }
            }
            else{
                swal.fire({
                    title: "Error",
                    text: "Ha ocurrido un error al eliminar la integración",
                    icon: "error",
                });
            }
        }
    </script>
@endsection
