@extends('Pages.plantilla')

@section('tittle')
    Agregar empresa
@endsection

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
<br>

<div class="card" class="m-3">
    <div class="card-header d-flex justify-content-between">
        <h3>Agregar empresa</h3>
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
                @error('errorValidacion')
                    <div class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                @enderror
                <br>
                <div class="tab-content">
                    <div class="p-4 mb-4 border rounded-3 bg-secondary bg-opacity-10">
                        <h3>Datos de la empresa</h3>
                        <div class="row row-cols-1 row-cols-sm-3">
                            <div class="col">
                                <h4>Representante de la empresa</h4>
                                <select class="form-select selectToo" id="personas" name="persona_id">
                                    <option value="0" selected>SIN DATO</option>
                                    @foreach ($listaPersonas as $persona)
                                        <option value="{{$persona->id}}">{{$persona->apodo}}, {{$persona->nombres}} {{$persona->apellido_paterno}} {{$persona->apellido_materno}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <h4>Nombre de la empresa</h4>
                                <input type="text" id="nombreEmpresa" name="nombreEmpresa" class="form-control" value="{{old('nombreEmpresa')}}">
                            </div>
                            <div class="col">
                                <h4>Pagina web</h4>
                                <input type="text" id="paginaWeb" name="paginaWeb" class="form-control" value="{{old('paginaWeb')}}">
                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-sm-3">
                            <div class="col">
                                <h4>Telefono celular principal</h4>
                                <input type="text" id="telefono1" name="telefono1" class="form-control" value="{{old('telefono1')}}">
                            </div>
                            <div class="col">
                                <h4>Telefono celular alternativo</h4>
                                <input type="text" id="telefono2" name="telefono2" class="form-control" value="{{old('telefono2')}}">
                            </div>
                            <div class="col">
                                <h4>Telefono fijo</h4>
                                <input type="text" id="telefono3" name="telefono3" class="form-control" value="{{old('telefono3')}}">
                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-sm-3">
                            <div class="col">
                                <h4>Correo electrónico principal</h4>
                                <input type="email" id="correo1" name="correo1" class="form-control" value="{{old('correo1')}}">
                            </div>
                            <div class="col">
                                <h4>Correo electrónico alternativo 1</h4>
                                <input type="email" id="correo2" name="correo2" class="form-control" value="{{old('correo2')}}">
                            </div>
                            <div class="col">
                                <h4>Correo electrónico alternativo 2</h4>
                                <input type="email" id="correo3" name="correo3" class="form-control" value="{{old('correo3')}}">
                            </div>
                        </div>
                    </div>
                    <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                        <h3>Domicilio de la empresa</h3>
                        <div class="row row-cols-1 row-cols-sm-3">
                            <div class="col">
                                <h4>Calle principal</h4>
                                <input type="text" id="calle1" name="calle1" class="form-control" value="{{old('calle1')}}">
                            </div>
                            <div class="col">
                                <h4>Calle colindante 1</h4>
                                <input type="text" id="calle2" name="calle2" class="form-control" value="{{old('calle2')}}">
                            </div>
                            <div class="col">
                                <h4>Calle colindante 2</h4>
                                <input type="text" id="calle3" name="calle3" class="form-control" value="{{old('calle3')}}">
                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-sm-3">
                            <div class="col">
                                <h4>Número exterior</h4>
                                <input type="text" id="numero_exterior" name="numero_exterior" class="form-control" value="{{old('numero_exterior')}}">
                            </div>
                            <div class="col">
                                <h4>Número interior</h4>
                                <input type="text" id="numero_interior" name="numero_interior" class="form-control" value="{{old('numero_interior')}}">
                            </div>
                            <div class="col">
                                <h4>Colonia</h4>
                                <select id="colonias" name="colonia_id" class="form-select selectToo" style="width: 100%">
                                    <option value="0">SIN DATO</option>
                                    @foreach ($listaColonias as $colonia)
                                        <option value="{{$colonia->id}}">{{$colonia->nombre}}, {{$colonia->seccionColonia[0]->seccion->distritoLocal->municipio->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <h4>Referencia de oficina</h4>
                            <textarea id="referencia" name="referencia" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                </div>
            <br>
            <div>
                <center>
                    <button id="BotonAgregarPersona" class="btn btn-primary">
                        Agregar
                    </button>
                    <!-- <button class="btn btn-danger" type="button" class="cerrarFormulario">Limpiar</button> -->
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
    </script>
@endsection
