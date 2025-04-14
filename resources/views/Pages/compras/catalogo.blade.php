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
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Presentación</th>
                            <th>Cantidad</th>
                            <th>Unidad</th>
                            <th class="col-4">Opciones</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
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
                        <input type="hidden" id="idProducto" name="variante[producto_id]">
                        <div class="col">
                            <label for="" class="form-label">Producto:</label>
                            <input type="text" id="nombreProducto" class="form-control" disabled>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Codigo:</label>
                            <input type="text" id="codigoVariante" name="variante[codigo]" class="form-control">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">SKU:</label>
                            <input type="text" id="skuVariante" name="variante[sku]" class="form-control">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Nombre:</label>
                            <input type="text" id="nombreVariante" name="variante[nombre]" class="form-control">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Presentación:</label>
                            <input type="text" id="presentacionVariante" name="variante[presentacion]" class="form-control">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Cantidad:</label>
                            <input type="number" id="cantidadVariante" name="variante[cantidad]" class="form-control">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Unidad:</label>
                            <input type="text" id="unidadVariante" name="variante[unidad]" class="form-control">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Descripción:</label>
                            <textarea id="descripcionVariante" name="variante[descripcion]" rows="5" class="form-control"></textarea>
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
    <div class="modal fade" id="modalPrecios" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 me-5" id="tituloModalPrecios">Variantes</h1>
                    <button type="button" id="btnAgregarPrecio" class="btn btn-success">Agregar Precio</button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row row-cols-2">

                    <table id="tablaPrecios" class="wrap table table-striped" style="width: 100%;">
                        <thead class="table-dark">
                            <th>Id</th>
                            <th>Categoria</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnSubmitModalPrecios" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="//cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css"></script>
<script text="text/javascript">
    var idProductoSeleccionado = -1;
    var nombreProducto = "";
    var arrayPrecios = [];
    var numeroMaximoVariantes = 0;
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
        idProductoSeleccionado = $(this).parent().children().first().val();
        var url = "{{route('catalogo.cargarVariantes', 'idProducto')}}";
        url = url.replace("idProducto", idProductoSeleccionado);
        peticionAjax(url, cargarVariantes)
    });

    function cargarVariantes(response){
        $('#modalVariantes').modal('show');
        nombreProducto = response.titulo;
        $('#tituloModalVariantes').text("Variantes de producto: " + response.titulo);
        cargarTablaVariantes(response.variantes)
    }

    $('#abrirModalAgregarVariante').click(function (){
        $('#formularioVariante')[0].reset();
        $('#titulomodalAgregarVariante').text("Agregar Variante de " + nombreProducto);
        $('#nombreProducto').val(nombreProducto);
        $('#idProducto').val(idProductoSeleccionado);
        $('#modalAgregarVariante').modal('show');
    })

    $('#btnSubmitAgregarVariante').click(function(){
        var datos = $('#formularioVariante').serializeArray();
        var urlAgregarVariante = "{{route('catalogo.crearVariante', 'idProducto')}}"
        urlAgregarVariante = urlAgregarVariante.replace('idProducto', idProductoSeleccionado)
        console.log(datos);
        peticionAjax(urlAgregarVariante, varianteCreado, "POST", datos)
    });

    function varianteCreado(response){
        console.log(response);
        if(response.respuesta){
            swal.fire({
                'title': 'Éxito',
                'text': response.mensaje,
                'icon': 'success'
            })
            cargarTablaVariantes(response.variante)
            $('#modalAgregarVariante').modal('hide');
        }
    }

    function cargarTablaVariantes(array){
        array.forEach(variante => {
            $('#tablaVariante tbody').append(
                $('<tr>').append(
                    $('<td>').text(variante.codigo),
                    $('<td>').text(variante.nombre),
                    $('<td>').text(variante.presentacion),
                    $('<td>').text(variante.cantidad),
                    $('<td>').text(variante.unidad),
                    $('<td>').append(
                        $('<input type="hidden">').val(variante.id),
                        $('<button type="buttom" class="btn btn-primary">Modificar</button>'),
                        $('<button type="buttom" class="btn btn-danger">Borrar</button>'),
                    ),
                )
            )
        });
    }

    $('.cargarModalPrecios').click(function(){
        idProductoSeleccionado = $(this).parent().children().first().val();
        var url = "{{route('catalogo.cargarPrecios', 'idProducto')}}";
        url = url.replace("idProducto", idProductoSeleccionado);
        peticionAjax(url, cargarPrecios)
    })

    function cargarPrecios(response){
        console.log(response);
        numeroMaximoVariantes = response.nombreVariantes.length;
        $('#tituloModalPrecios').text('Precios y Variantes del Producto: ' + response.nombreProducto)
        $('#tablaPrecios').html(
            $('<thead>').addClass('table-dark').append(
                $('<tr>').append(
                    $('<th>').text('Precios')
                )
            )
        );
        $('#tablaPrecios').append(
            $('<tbody>')
        );
        response.nombreVariantes.forEach(function (variante){
            $('#tablaPrecios thead tr').append(
                $('<th>').text("Variante: " + variante)
            )
        })

        // response.nombrePrecios.forEach(function (precio)){
        //     $()
        // }



        // tablaPrecios
        $('#modalPrecios').modal('show')
    }

    $('#btnAgregarPrecio').click(function (){
        $('#tablaPrecios tbody').append(
            $('<tr>').append(
                $('<td>').append(
                    $('<input type="text">').addClass('form-control')
                )
            )
        )
        for (let i = 0; i < numeroMaximoVariantes; i++) {
            $('#tablaPrecios tbody tr').last().append(
                $('<td>').append(
                    $('<input type="number">').addClass('form-control')
                )
            )
        }

    });

</script>
@endsection
