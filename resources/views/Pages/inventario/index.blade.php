@extends('Pages.plantilla')
@section('tittle', 'Tabla de Inventario')
@section('cuerpo')
    <style>
        .disabled {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
    <!-- Modal -->
    <div class="modal fade" id="modalAumentarDisminuir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form id="formularioExistencia" action="" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="tituloModalExistencia">Aumentar/Disminuir Existencia</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="idProducto" name="idProducto" value="{{old('idProducto')}}">
                        <div class="row row-cols-1 row-cols-sm-2 mb-3">
                            <div class="col-4">
                                <label class="form-label">Código Producto</label>
                                <input type="text" id="codigo" class="form-control" disabled>
                            </div>
                            <div class="col">
                                <label class="form-label">Nombre Producto</label>
                                <input type="text" id="nombreProducto" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-sm-3">
                            <div class="col-3">
                                <label class="form-label">Añadir Cantidad</label>
                                <input type="number" id="cantidad" name="cantidad" class="form-control" value="{{old('cantidad')}}">
                            </div>
                            <div class="col-3">
                                <label class="form-label">Existencia Afectada</label>
                                <input type="number" id="existenciaDinamica" class="form-control" disabled>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Unidad Medida</label>
                                <input type="text" id="unidadMedida" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btnFormularioExistencia" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Tabla de Inventario</h1>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-end">
                    {{-- <a href="" target="_blank" class="me-3">
                        <button class="btn btn-primary">Exportar a Excel</button>
                    </a> --}}
                    <a href="{{route('inventario.vistaCrear')}}">
                        <button class="btn btn-primary">Agregar Producto</button>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="tablaInventario" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <th>Código</th>
                        <th>Nombre Producto</th>
                        <th>Existencia</th>
                        <th>Unidad Medida</th>
                        <th>Control Existencia</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="//cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css"></script>
<script text="text/javascript">
    var tabla;
    var modalExistencia;
    var productoParaCambiarExistencias = 0;
    var existenciaDinamica = 0;
    var existenciaNueva = 0;

    $(document).ready(function () {
        modalExistencia = $('#modalAumentarDisminuir');
        tabla = $('#tablaInventario').DataTable({
            searching: true,
            paging: true,
            pageLength: 10,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
            },
            scrollX: true,
            scrollY: '450px',
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "{{route('inventario.cargarTabla')}}",
                error: function(xhr, error, thrown){
                    if(thrown == 'Unauthorized'){
                        alertaCargando();
                        location.reload();
                    }
                }
            },
            columns: [
                { data: 'codigo' },
                { data: 'nombreProducto' },
                { data: 'existencia' },
                { data: 'unidadMedida' },
                { data: null,
                    render: function(data, type, row){
                        return $('<div>').append(
                            $('<input type="hidden">').attr({ name: "idInventario", value: data.id }),
                            $('<button class="btn btn-primary botonModalEntradasSalidas">').text('Entradas/Salidas')
                        ).prop('outerHTML');
                    }
                },
                { data: null,
                    render: function(data, type, row){
                        return $('<form method="post">').attr('action', '{{route("inventario.eliminarProducto")}}').append(
                            $('<input type="hidden">').attr({ name: "_token", value: "{{csrf_token()}}" }),
                            $('<input type="hidden">').attr({ name: "idInventario", value: data.id }),
                            $('<button class="btn btn-danger">').text('Borrar')
                        ).prop('outerHTML');
                    }
                },
            ],
            columnDefs: [
                { className: 'text-center', targets: '_all' }
            ],
            //columna dependencia ajustar a 300px heihgt
            // rowCallback: function(row, data, index){
            //     $('td:eq(3)', row).css('min-width', '150px');
            //     $('td:eq(3)', row).css('overflow-wrap', 'break-word');
            //     $('td:eq(3)', row).css('white-space', 'normal');
            //     $('td:eq(4)', row).css('overflow-wrap', 'break-word');
            //     $('td:eq(4)', row).css('white-space', 'normal');
            //     $('td:eq(5)', row).css('overflow-wrap', 'break-word');
            //     $('td:eq(5)', row).css('white-space', 'normal');
            //     $('td:eq(6)', row).css('overflow-wrap', 'break-word');
            //     $('td:eq(6)', row).css('white-space', 'normal');
            // }
        });
        $('#tablaInventario tbody').on('click', '.botonModalEntradasSalidas', function() {
            var idInventario = $(this).siblings('input[name="idInventario"]').val();
            abrirModal(idInventario);
        });
        $('.botonModalEntradasSalidas').on('click', abrirModal);






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
    function abrirModal(idInventario){
        alertaCargando();
        url = "{{url('/')}}/inventario/buscar-" + idInventario;
        peticionAjax(url, cargarModalAjax, "GET")
    }
    function cargarModalAjax(response){
        $("#idProducto").val(response.id);
        $("#codigo").val(response.codigo);
        $("#nombreProducto").val(response.nombreProducto);
        $("#cantidad").val(0);
        existenciaDinamica = response.existencia;
        existenciaNueva = existenciaDinamica;
        $("#existenciaDinamica").val(response.existencia);
        $("#unidadMedida").val(response.unidadMedida);
        modalExistencia.modal('show');
        alertaCargando(false);
    }
    $('#cantidad').change(calcularExistenciaDinamica);
    function calcularExistenciaDinamica(){
        existenciaNueva = parseInt(existenciaDinamica) + parseInt($("#cantidad").val());
        $("#existenciaDinamica").val(existenciaNueva);
    }
    $('#btnFormularioExistencia').click(function (){
        if(existenciaNueva < 0){
            swal.fire({
                title: "Error",
                text: "No puedes tener inventario negativo",
                icon: "error"
            });
            return;
        }
        var datos = $('#formularioExistencia').serializeArray();
        peticionAjax("{{route('inventario.cambiarExistencia')}}", guardarExistenciaNueva, "POST", datos);
    })
    function guardarExistenciaNueva(response){
        if(response[0]){
            modalExistencia.modal('hide');
            swal.fire({
                title: 'Éxito',
                text: response[1],
                icon: 'success',
            });
            tabla.draw();
        }
        else{
            swal.fire({
                title: 'Error',
                text: response[1],
                icon: 'error',
            });
        }
    }
</script>
@endsection
