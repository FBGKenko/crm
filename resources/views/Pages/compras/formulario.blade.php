@extends('Pages.plantilla')

@section('tittle')
{{$titulo}}
@endsection

@section('cuerpo')
    <br>
    <form id="formularioPrincipal" action="" method="post">
        @csrf
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between">
                <h2 class="mt-4">{{$titulo}}</h2>
                <div class="align-self-end">
                    <a href="#" id="btnGuardarFormulario" class="btn btn-success">Guardar</a>
                </div>
            </div>
            {{-- CONTENEDOR CATEGORIA --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Categoria y Presentación</h4>
                </div>
                <div class="card-body row">
                    <div class="d-flex col-6">
                        <div class="col me-3">
                            <label for="" class="form-label">Categoria</label>
                            <select name="producto[categoria_id]" id="selectCategoria" class="form-select">
                                <option value="0" selected disabled>Seleccione una opcion</option>
                                @foreach ($listaCategorias as $categoria)
                                    <option value="{{$categoria->id}}" @selected($categoria->id == old('producto[categoria_id]', isset($producto->categoria_id) ? $producto->categoria_id : null))>{{$categoria->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col align-self-end">
                            <a href="#" id="btnCrearCategoria" class="btn btn-primary">Crear Categoria</a>
                        </div>
                    </div>
                    <div class="col-6">
                        <label for="" class="form-label">Presentación</label>
                        <select name="producto[presentacion]" id="selectPresentacion" class="form-select">
                            <option value="0" selected disabled>Seleccione una opcion</option>
                            <option value="M" @selected(old('producto[presentacion]', isset($producto->presentacion) ? $producto->presentacion : null))>CHICO</option>
                            <option value="L" @selected(old('producto[presentacion]', isset($producto->presentacion) ? $producto->presentacion : null))>MEDIANO</option>
                            <option value="G" @selected(old('producto[presentacion]', isset($producto->presentacion) ? $producto->presentacion : null))>GRANDE</option>
                            <option value="B" @selected(old('producto[presentacion]', isset($producto->presentacion) ? $producto->presentacion : null))>EXTRA GRANDE</option>
                            <option value="X" @selected(old('producto[presentacion]', isset($producto->presentacion) ? $producto->presentacion : null))>OTRO</option>
                        </select>
                    </div>
                </div>
            </div>
            {{-- CONTENEDOR PRODUCTO --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Producto</h4>
                </div>
                <div class="card-body row row-cols-3">
                    <div class="col mb-3">
                        <label for="" class="form-label">Código</label>
                        <input type="text" name="producto[codigo]" id="codigoProducto" class="form-control" maxlength="255" value="{{old('producto[codigo]', isset($producto->codigo) ? $producto->codigo : '')}}">
                        {{-- <input type="hidden" name="producto[codigo]" id="codigoProductoHidden" class="form-control" maxlength="255" value="{{old('producto[codigo]', isset($producto->codigo) ? $producto->codigo : '')}}"> --}}
                    </div>
                    <div class="col mb-3">
                        <label for="" class="form-label">Nombre Producto (*)</label>
                        <input type="text" name="producto[nombreCorto]" class="form-control" maxlength="255" value="{{old('producto[nombreCorto]', isset($producto->nombreCorto) ? $producto->nombreCorto : '')}}">
                    </div>
                    <div class="col mb-3">
                        <label for="" class="form-label">Identificar Tienda Nube</label>
                        <input type="text" name="producto[identificadorUrl]" class="form-control" maxlength="255" value="{{old('producto[identificadorUrl]', isset($producto->identificadorUrl) ? $producto->identificadorUrl : '')}}">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="" class="form-label">Descripción</label>
                        <textarea name="producto[descripcion]" rows="4" class="form-control">{{old('producto[descripcion]', isset($producto->descripcion) ? $producto->descripcion : '')}}</textarea>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="" class="form-label">Descripción Tienda Nube</label>
                        <textarea name="producto[descripcionWeb]" rows="4" class="form-control">{{old('producto[descripcionWeb]', isset($producto->descripcionWeb) ? $producto->descripcionWeb : '')}}</textarea>
                    </div>
                    <div class="col mb-3">
                        <label for="" class="form-label">Url Video de Uso</label>
                        <input type="text" name="producto[videoUsoUrl]" class="form-control" value="{{old('producto[videoUsoUrl]', isset($producto->videoUsoUrl) ? $producto->videoUsoUrl : '')}}">
                    </div>
                    <div class="col mb-3">
                        <label for="" class="form-label">Url Ficha Técnica</label>
                        <input type="text" name="producto[fichaTecnicaUrl]" class="form-control" value="{{old('producto[fichaTecnicaUrl]', isset($producto->fichaTecnicaUrl) ? $producto->fichaTecnicaUrl : '')}}">
                    </div>
                    <div class="col mb-3">
                        <label for="" class="form-label">Nombre Web</label>
                        <input type="text" name="producto[nombreWeb]" class="form-control" maxlength="255" value="{{old('producto[nombreWeb]', isset($producto->nombreWeb) ? $producto->nombreWeb : '')}}">
                    </div>
                    {{-- <div class="col mb-3">
                        <label for="" class="form-label">Código Tienda Camarena</label>
                        <input type="text" name="producto[claveCamarena]" class="form-control" maxlength="255">
                    </div> --}}
                </div>
            </div>
            {{-- CONTENEDOR PRECIOS --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <h4>Precios</h4>
                    <button type="button" id="btnAgregarPrecio" class="btn btn-primary">Agregar</button>
                </div>
                <div class="card-body row row-cols-3">
                    <table id="tablaPrecios" class="table">
                        <thead class="table-dark">
                            <th>Nombre</th>
                            <th>Monto</th>
                            <th>opciones</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            {{-- CONTENEDOR VARIANTES --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <h4>variantes</h4>
                    <button id="btnModalAgregarVariante" type="button" class="btn btn-primary">Agregar Variantes</button>
                </div>
                <div class="card-body row row-cols-3">
                    <div id="contenedorTablaVariantes" class="d-none">
                        <table id="tablaVariante" class="table">
                            <thead class="table-dark">
                                <th>Nombre</th>
                                <th>Monto</th>
                                <th>opciones</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <small class="mt-3">(*) Son campos obligatorios para el formulario</small>
        </div>

    </form>
    <div class="modal fade" id="modalAgregarCategoria" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Agregar Variante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formularioGuardarCategoria" action="" method="post">
                        @csrf
                        <label for="" class="form-label">Nombre nueva Categoria</label>
                        <input id="categoria" name="categoria" type="text" class="form-control">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnGuardarCategoria" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal fade" id="modalAgregarVariante" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Agregar Variante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formularioAgragarVariante" action="" method="post">
                        @csrf
                        <label for="" class="form-label">Código</label>
                        <input type="text" name="codigo" class="form-control">
                        <label for="" class="form-label">Sku</label>
                        <input type="text" name="sku" class="form-control">
                        <label for="" class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control">
                        <label for="" class="form-label">Presentación</label>
                        <input type="text" name="presentacion" class="form-control">
                        <label for="" class="form-label">Cantidad</label>
                        <input type="number" name="cantidad" class="form-control">
                        <label for="" class="form-label">Unidad</label>
                        <select name="unidad" class="form-select">
                            <option value="0" selected disabled>Seleccione una opcion</option>
                            <option>LITRO</option>
                            <option>PIEZA</option>
                            <option>PAQUETE</option>
                        </select>
                        <div class="col">
                            <label for="" class="form-label">Descripción</label>
                            <textarea name="descripcion" rows="4" class="form-control"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnGuardarVariante" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div> --}}
    {{-- <div class="modal fade" id="modalImagenes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title me-3" id="staticBackdropLabel">Imagenes</h5>
                    <button type="button" id="btnAgregarPrecioModal" class="btn btn-primary">Agregar precio</button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="tablaPrecios" class="">
                        <thead class="table-dark">
                            <th>Precio</th>
                            <th>Descripción</th>
                            <th>opciones</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('scripts')
<script src="//cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css"></script>
<script text="text/javascript">

    var arrayVariantes = [];
    var idVariante = 1;
    var idVarianteSeleccionada = 0;
    var banderaSinVariantes = true;
    var contadorPrecios = 1;
    $(document).ready(function () {

    });
    $("#btnCrearCategoria").on('click', abrirModalCategoria);
    $("#btnCrearVariante").on('click', abrirModalVariante);

    $('#btnGuardarCategoria').click(function(){
        var datosFormulario = $('#formularioGuardarCategoria').serializeArray();
        $.ajax({
            type: "post",
            url: "{{route('catalogo.agregarCategoria')}}",
            data: datosFormulario,
            success: function (response) {
                console.log(response);
                if(response.resultado){
                    Swal.fire({
                        'title': "Éxito",
                        'text': response.mensaje,
                        'icon': "success",
                    })
                    $('#selectCategoria').append(
                        $('<option>').attr("value", response.idCategoria).text(response.nombreCategoria)
                    );
                    $("#modalAgregarCategoria").modal('hide');
                    $('#selectCategoria').val(response.idCategoria);
                }else{
                    Swal.fire({
                        'title': "Error",
                        'text': response.mensaje,
                        'icon': "error",
                    })
                }
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
    });
    $('#btnGuardarVariante').click(function(){
        var datosFormulario = $('#formularioAgragarVariante').serializeArray();
        arrayVariantes.push({
            id: idVariante,
            codigo: datosFormulario[1].value,
            sku: datosFormulario[2].value,
            nombre: datosFormulario[3].value,
            presentacion: datosFormulario[4].value,
            cantidad: datosFormulario[5].value,
            unidad: datosFormulario[6].value,
            descripcion: datosFormulario[7].value,
            precios: []
        });
        $('#tablaVariantes tbody').append(
            $('<td>').text(datosFormulario[1].value),
            $('<td>').text(datosFormulario[3].value),
            $('<td>').text(datosFormulario[4].value),
            $('<td>').text(`${datosFormulario[5].value} ${datosFormulario[6].value}`),
            $('<td>').append(
                $('<input type="hidden" class="idVarianteEscondido">').val(idVariante),
                $('<btn typer="button" class="btn btn-secondary modalMultimedia">').text('Multimedia').click(abrirModalMultimedia)
            ),
        )
        $('#formularioAgragarVariante')[0].reset();
        $("#modalAgregarVariante").modal('hide');
        idVariante++;
    });
    $('#btnGuardarFormulario').click(function(){
        var datosForm = $('#formularioPrincipal').serializeArray();

        var resultado = validarFormulario(datosForm);
        if(!resultado.resultado){
            Swal.fire({
                title: "Error",
                text: resultado.mensaje,
                icon: "error"
            })
            return;
        }
        alertaCargando();
        $.ajax({
            type: "post",
            url: "{{$ruta}}",
            data: datosForm,
            success: function (response) {
                Swal.fire({
                    title: "Éxito",
                    text: response.mensaje,
                    icon: "success"
                }).then((resultado) => {
                    location.href = "{{route('catalogo.index')}}"
                })
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
    });
    function abrirModalCategoria(){
        $("#modalAgregarCategoria").modal('show');
    }
    function abrirModalVariante(){
        $("#modalAgregarVariante").modal('show');
    }
    function validarFormulario(datos){
        var respuesta = {
            resultado: true,
            mensaje: "",
        }

        if(datos[3].value.trim() == ""){
            respuesta.resultado = false
            respuesta.mensaje += "El campo Nombre del Producto es requerido.\n"
        }
        return respuesta;
    }

    $('#btnAgregarPrecio').click(function () {
        $('#tablaPrecios tbody').append(
            $('<tr>').append(
                $('<td>').append($('<input>').attr({name: `producto[precios][${contadorPrecios}][nombre]`, class: 'form-control'})),
                $('<td>').append($('<input type="number">').attr({name: `producto[precios][${contadorPrecios}][monto]`, class: 'form-control'})),
                $('<td>').append($('<button type="button">').attr({class: 'btn btn-danger'}).text('Borrar').click(borrarPrecio)),
            )
        )
        contadorPrecios++;
    })

    $('#btnModalAgregarVariante').click(function(){

    })

    function borrarPrecio(){

    }
</script>
@endsection
