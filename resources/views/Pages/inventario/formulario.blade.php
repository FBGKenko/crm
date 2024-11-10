@extends('Pages.plantilla')
@section('tittle', 'Agregar Producto')
@section('cuerpo')
<style>
    h4{
        font-weight: 400;
    }
    :root {
        --purple: #0d6efd;
        --off-white: #f8f8f8;
        --off-black: #444444;
        --shadow: 0 0 30px #cccccc;
        --xs: 0.2rem;
        --sm: 0.5rem;
        --md: 0.8rem;
        --lg: 1rem;
        --xlg: 1.5rem;
        --xxlg: 2rem;
        --transition: 0.3s linear all;
    }
    .tag {
        background-color: var(--purple);
        border-radius: 10px;
        color: var(--off-white);
        font-size: var(--md);
        margin-bottom: var(--md);
        margin-right: var(--md);
        padding: var(--sm) var(--md);
    }
    .remove-tag {
        cursor: pointer;
        margin-left: 5px;
    }
</style>

<div class="card" class="m-3">
    <div class="card-header d-flex justify-content-between">
        <h3>Agregar Producto</h3>
        <div>
            <button id="BotonAgregarPersona" class="btn btn-primary">
                Agregar
            </button>
        </div>
    </div>
    <div class="card-body">
        <form id="formularioAgregarSimpatizante" action="{{$urlFormulario}}" method="post">
            @csrf
            <div class="container">
                <br>
                <div class="tab-content">
                    <div class="p-4 mb-4 border rounded-3 bg-secondary bg-opacity-10">
                        <h3>Descripción del Producto</h3>
                        <div class="row row-cols-1 row-cols-sm-3">
                            <div class="col">
                                <label class="form-label">Código Producto</label>
                                <input type="text" id="codigo" name="codigo" class="form-control" value="{{old('codigo')}}">
                            </div>
                            <div class="col">
                                <label class="form-label">Nombre Producto</label>
                                <input type="text" id="nombreProducto" name="nombreProducto" class="form-control" value="{{old('nombreProducto')}}">
                            </div>
                            <div class="col">
                                <label class="form-label">Costo Producto</label>
                                <input type="text" id="costo" name="costo" class="form-control" value="{{old('costo')}}">
                            </div>
                            <div class="col">
                                <label class="form-label">Precio Producto</label>
                                <input type="text" id="precio" name="precio" class="form-control" value="{{old('precio')}}">
                            </div>
                        </div>
                    </div>
                    <div class="p-4 mb-4 border rounded-3 bg-secondary bg-opacity-10">
                        <h3>Datos de Existencia</h3>
                        <div class="row row-cols-1 row-cols-sm-3">
                            <div class="col">
                                <label class="form-label">Existencia</label>
                                <input type="number" id="existencia" name="existencia" class="form-control" value="{{old('existencia')}}">
                            </div>
                            <div class="col">
                                <label class="form-label">Unidad Medida</label>
                                <input type="text" id="unidadMedida" name="unidadMedida" class="form-control" value="{{old('unidadMedida')}}">
                            </div>
                        </div>
                    </div>
                </div>
            <br>
            <div>
                <center>
                    <button class="btn btn-primary">
                        Agregar
                    </button>
                </center>
            </div>
        </div>
    </form>
</div>
@endsection



@section('scripts')
    <script text="text/javascript">
    $(document).ready(function () {
        @if(!str_contains($urlFormulario, "agregar"))
            cargarFormulario();
        @endif
    });
        function cargarFormulario(){
            var valores = @json($empresa);
            console.log(valores);
            $('#personas').val(valores.persona_id);
            $('#nombreEmpresa').val(valores.nombreEmpresa);
            $('#paginaWeb').val(valores.paginaWeb);
            $('#telefono1').val(valores.telefono1);
            $('#telefono2').val(valores.telefono2);
            $('#telefono3').val(valores.telefono3);
            $('#correo1').val(valores.correo1);
            $('#correo2').val(valores.correo2);
            $('#correo3').val(valores.correo3);
            $('#calle1').val(valores.domicilio.calle1);
            $('#calle2').val(valores.domicilio.calle2);
            $('#calle3').val(valores.domicilio.calle3);
            $('#numero_exterior').val(valores.domicilio.numero_exterior);
            $('#numero_interior').val(valores.domicilio.numero_interior);
            $('#colonias').val(valores.domicilio.colonia_id);
            $('#referencia').val(valores.domicilio.referencia);
        }

        $('#BotonAgregarPersona').click(function () {
            $('#formularioAgregarSimpatizante').trigger('submit');
        });
        $('#formularioAgregarSimpatizante').submit(function() {
            alertaCargando();
        });
    </script>
@endsection
