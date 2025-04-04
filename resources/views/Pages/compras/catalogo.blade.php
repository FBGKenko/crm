@extends('Pages.plantilla')

@section('tittle')
Catálogo
@endsection

@section('cuerpo')
    <br>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Catálogo</h1>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-end">
                    <a href="" target="_blank" class="me-3">
                        <button class="btn btn-primary">Importar de Excel</button>
                    </a>
                    <a href="" target="_blank" class="me-3">
                        <button class="btn btn-secondary">Exportar a Excel</button>
                    </a>
                    <a href="{{route('catalogo.agregar')}}">
                        <button class="btn btn-success">Agregar Producto</button>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="tablaProductos" class="wrap table table-striped" style="width: 100%;">
                    <thead>
                        <th>Id</th>
                        <th>Categoria</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th class="col-4">Opciones:</th>
                    </thead>
                    <tbody>
                        @foreach ($listaProductos as $producto)
                            <tr>
                                <td>{{$producto->id}}</td>
                                <td>{{$producto->categorias->nombre}}</td>
                                <td>{{$producto->nombreCorto}}</td>
                                <td>{{$producto->descripcion}}</td>
                                <td>
                                    <input type="hidden" value="{{$producto->id}}">
                                    <a href="#" class="btn btn-primary cargarModalVariantes">Variantes</a>
                                    <a href="#" class="btn btn-primary cargarModalPrecios">Precios</a>
                                    <a href="{{route('catalogo.modificar', $producto->id)}}" class="btn btn-secondary">modificar</a>
                                    <a href="#" class="btn btn-danger">Eliminar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalVariantes" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 me-5" id="tituloModalVariantes">Variantes</h1>
                    <button type="button" id="abrirModalAgregarVariante" class="btn btn-success">Agregar</button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="tablaVariante" class="wrap table table-striped" style="width: 100%;">
                        <thead class="table-dark">
                            <th>Id</th>
                            <th>Categoria</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th class="col-4">Opciones</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAgregarVariante" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form id="formularioVariante" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="titulomodalAgregarVariante">Variantes</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row row-cols-2">
                        @csrf
                        <div class="col">
                            <label for="" class="form-label">Producto:</label>
                            <input type="text" id="nombreProducto" class="form-control" disabled>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Codigo:</label>
                            <input type="text" id="codigoVariante" class="form-control">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">SKU:</label>
                            <input type="text" id="skuVariante" class="form-control">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Nombre:</label>
                            <input type="text" id="nombreVariante" class="form-control">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Presentación:</label>
                            <input type="text" id="presentacionVariante" class="form-control">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Cantidad:</label>
                            <input type="text" id="cantidadVariante" class="form-control">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Unidad:</label>
                            <input type="text" id="unidadVariante" class="form-control">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Descripción:</label>
                            <textarea id="descripcionVariante" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btnSubmitAgregarVariante" class="btn btn-success">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script src="//cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css"></script>
<script text="text/javascript">
    var idVarianteSeleccionada = -1;
    var nombreProducto = "";
    $(document).ready(function () {
        $('#tablaProductos').DataTable({
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
        });
    });

    $('.cargarModalVariantes').click(function (){
        var id = $(this).parent().children().first().val();
        idVarianteSeleccionada = id;
        var url = "{{route('catalogo.cargarVariantes', 'idProducto')}}";
        url = url.replace("idProducto", id);
        peticionAjax(url, cargarVariantes)
    });

    function cargarVariantes(response){
        $('#modalVariantes').modal('show');
        nombreProducto = response.titulo;
        $('#tituloModalVariantes').text("Variantes de producto: " + response.titulo);
        console.log(response);
    }

    $('#abrirModalAgregarVariante').click(function (){
        $('#formularioVariante')[0].reset();
        $('#titulomodalAgregarVariante').text("Agregar Variante de " + nombreProducto);
        $('#nombreProducto').val(nombreProducto);
        $('#modalAgregarVariante').modal('show');
    })

    $('#btnSubmitAgregarVariante').click(function(){
        var datos = $('#formularioVariante').serializeArray();
        console.log(datos);
    });
</script>
@endsection
