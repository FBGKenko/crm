@extends('Pages.plantilla')

@section('tittle')
    {{
        str_contains(url()->current(), 'agregar') ?
        'Agregar Persona' : 'Modificar Persona'
    }}
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
    <div class="card-header">
        <h3>
            {{
                str_contains(url()->current(), 'agregar') ?
                'Agregar Persona' : 'Modificar Persona'
            }}
        </h3>
    </div>
    <div class="card-body">
        {{-- FORMULARIO DE AGREGAR USUARIO --}}
        <form id="formularioAgregarSimpatizante" action=" {{  str_contains(url()->current(), 'agregar') ? route('contactos.agregar') : route('contactos.modificar', $persona->id) }}" method="post" style="">
            @csrf
            <div class="container">
                @error('errorValidacion')
                    <div class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                @enderror
                <br>
                {{-- CABECERAS --}}
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                      <a class="nav-link active" data-bs-toggle="tab" href="#datosControl">DATOS DE CONTROL</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" data-bs-toggle="tab" href="#datosPersonales">DATOS PERSONALES</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" data-bs-toggle="tab" href="#datosContacto">DATOS DE CONTACTO</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#datosDomicilio">DATOS DE DOMICILIO</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#datosIdentificacion">DATOS DE IDENTIFICACIÓN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#datosRelacion">DATOS DE RELACIÓN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#otrosDatos">OTROS DATOS</a>
                    </li>
                </ul>

                {{-- CONTENEDORES --}}
                <div class="tab-content">
                    <div class="tab-pane container pt-3 active" id="datosControl">
                        {{-- CONTENEDOR DATOS DE CONTROL --}}
                        <div id="datosControl" class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Datos de control </h3>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col">
                                    <h4>Fecha de registro</h4>
                                    <input type="date" class="form-control" id="fechaRegistro" name="fechaRegistro" min="{{date('Y-m-d', strtotime('-100 years'))}}" max="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}">
                                    @error('fechaRegistro')
                                        <div id="fechaRegistroError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Folio</h4>
                                    <input type="number" min="0" maxlength="7" class="form-control" id="folio" name="folio" value="{{old('folio')}}">
                                    @error('folio')
                                        <div id="folioError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Promotor</h4>
                                    <select class="form-select selectToo" id="promotores" name="promotor">
                                        <option value="0" selected>SIN DATO</option>
                                        @foreach ($listaPromotores as $promotor)
                                                <option value="{{$promotor->id}}">{{$promotor->nombres}} {{$promotor->apellido_paterno}} {{$promotor->apellido_materno}}, {{$promotor->apodo}}</option>
                                        @endforeach
                                    </select>
                                    @error('promotor')
                                        <div id="promotorError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col d-none">
                                    <h4>Origen</h4>
                                    <select id="origen" name="origen" class="form-select selectToo" aria-label="Tipo de Registro">
                                        <option value="0">SIN DATO</option>
                                    </select>
                                    @error('origen')
                                        <div class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Referencia de Origen</h4>
                                    <select id="referenciaOrigen" name="referenciaOrigen" class="form-select selectToo" aria-label="Tipo de Registro">
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaPersonas as $persona)
                                                <option value="{{$persona->id}}">{{$persona->nombres}} {{$persona->apellido_paterno}} {{$persona->apellido_materno}}, {{$persona->apodo}}</option>
                                        @endforeach

                                    </select>
                                    @error('referenciaOrigen')
                                        <div id="fechaRegistroError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col d-none">
                                    <h4>Campaña de referencia</h4>
                                    <select id="referenciaCampania" name="referenciaCampania" class="form-select selectToo" aria-label="Tipo de Registro">
                                        <option value="0">SIN DATO</option>

                                    </select>
                                    @error('referenciaCampania')
                                        <div id="fechaRegistroError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col d-none">
                                    <h4>Etiquetas de origen</h4>
                                    <select id="etiquetasOrigen" name="etiquetasOrigen" class="form-select selectToo" aria-label="Tipo de Registro">
                                        <option value="0">SIN DATO</option>
                                    </select>
                                    @error('etiquetasOrigen')
                                        <div id="fechaRegistroError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Estatus</h4>
                                    <select id="estatus" name="estatus" class="form-select selectToo" aria-label="Tipo de Registro">
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaEstatus as $estatus)
                                            <option>{{$estatus->concepto}}</option>
                                        @endforeach
                                    </select>
                                    @error('estatus')
                                        <div id="fechaRegistroError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="datosPersonales">
                        {{-- CONTENEDOR DATOS PERSONALES --}}
                        <div id="datosPersonales" class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Datos personales</h3>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col">
                                    <h4 class="fw-bold">Apodo</h4>
                                    <input type="text" class="form-control" id="apodo" name="apodo" value="{{old('apodo')}}"
                                    minlength="3" maxlength="255"
                                    onblur="if (this.value == '') {this.value = '';}" onfocus="if (this.value == '') {this.value = '';}">
                                    @error('apodo')
                                        <div id="apellidoPaternoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4 >Nombre(s)</h4>
                                    <input type="text" class="form-control" id="nombres" name="nombres" value="{{old('nombres')}}"
                                    minlength="3" maxlength="255"
                                    onblur="if (this.value == '') {this.value = '';}" onfocus="if (this.value == '') {this.value = '';}">
                                    @error('nombres')
                                        <div id="apellidoPaternoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Apellido paterno</h4>
                                    <input type="text" class="form-control" id="apellidoPaterno" name="apellidoPaterno" value="{{old('apellidoPaterno')}}"
                                    minlength="3" maxlength="255"
                                    onblur="if (this.value == '') {this.value = '';}" onfocus="if (this.value == '') {this.value = '';}">
                                    @error('apellidoPaterno')
                                        <div id="apellidoMaternoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col">
                                    <h4>Apellido materno</h4>
                                    <input type="text" class="form-control" id="apellidoMaterno" name="apellidoMaterno" value="{{old('apellidoMaterno')}}" minlength="3" maxlength="255"

                                    onblur="if (this.value == '') {this.value = '';}"
                                    onfocus="if (this.value == '') {this.value = '';}">
                                    @error('apellidoMaterno')
                                        <div id="nombresError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Sexo</h4>
                                    <select name="genero" id="genero" class="form-select">
                                        <option {{old('genero') == 'SIN ESPECIFICAR' ? 'selected' : ''}} value="SIN ESPECIFICAR">SIN ESPECIFICAR</option>
                                        <option {{old('genero') == 'HOMBRE' ? 'selected' : ''}} value="HOMBRE">HOMBRE</option>
                                        <option {{old('genero') == 'MUJER' ? 'selected' : ''}} value="MUJER">MUJER</option>
                                    </select>
                                    @error('genero')
                                        <div id="generoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Fecha de Nacimiento</h4>
                                    <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" value="{{old('fechaNacimiento')}}"
                                    min="{{date('Y-m-d', strtotime('-100 years'))}}" max="{{date('Y-m-d', strtotime('-18 years'))}}">
                                    @error('fechaNacimiento')
                                        <div id="fechaNacimientoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                            <br>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col">
                                    <h4>Rango de edad</h4>
                                    <select id="rangoEdad" class="form-select" name="rangoEdad">
                                        <option {{old('rangoEdad') == '0' ? 'selected' : ''}} value="23">NO ESPECIFICÓ</option>
                                        <option {{old('rangoEdad') == '23' ? 'selected' : ''}} value="23">18-28</option>
                                        <option {{old('rangoEdad') == '34' ? 'selected' : ''}} value="34">29-39</option>
                                        <option {{old('rangoEdad') == '45' ? 'selected' : ''}} value="45">40-49</option>
                                        <option {{old('rangoEdad') == '55' ? 'selected' : ''}} value="55">50-69</option>
                                        <option {{old('rangoEdad') == '74' ? 'selected' : ''}} value="74">70-adelante</option>
                                    </select>
                                    @error('rangoEdad')
                                        <div id="rangoEdadError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">

                                </div>
                                <div class="col">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="datosContacto">
                        {{-- CONTENEDOR DATOS DE CONTACTO --}}
                        <div id="datosContacto" class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Datos de contacto</h3>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col">
                                    <h4>Telefono Celular 1</h4>
                                    <input type="number" class="form-control" id="telefonoCelular1" name="telefonoCelular1" value="{{old('telefonoCelular1')}}"
                                    minlength="10" maxlength="12">
                                    @error('telefonoCelular1')
                                        <div id="telefonoCelularError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Telefono Celular 2</h4>
                                    <input type="number" class="form-control" id="telefonoCelular2" name="telefonoCelular2" value="{{old('telefonoCelular2')}}"
                                    minlength="10" maxlength="12">
                                    @error('telefonoCelular2')
                                        <div id="telefonoCelularError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Telefono Celular 3</h4>
                                    <input type="number" class="form-control" id="telefonoCelular3" name="telefonoCelular3" value="{{old('telefonoCelular3')}}"
                                    minlength="10" maxlength="12">
                                    @error('telefonoCelular3')
                                        <div id="telefonoCelularError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col">
                                    <h4>Correo electrónico</h4>
                                    <input type="text" class="form-control" id="correo" name="correo" value="{{old('correo')}}" minlength="3"
                                    maxlength="255">
                                    @error('correo')
                                        <div id="facebookError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Correo electrónico alternativo</h4>
                                    <input type="text" class="form-control" id="correoAlternativo" name="correoAlternativo" value="{{old('correoAlternativo')}}" minlength="3"
                                    maxlength="255">
                                    @error('correoAlternativo')
                                        <div id="facebookError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Telefono Fijo</h4>
                                    <input type="text" class="form-control" id="telefonoFijo" name="telefonoFijo" value="{{old('telefonoFijo')}}" minlength="3"
                                    maxlength="255">
                                    @error('telefonoFijo')
                                        <div id="facebookError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col">
                                    <h4>Facebook</h4>
                                    <input type="text" class="form-control" id="nombreFacebook" name="nombreFacebook" value="{{old('nombreFacebook')}}" minlength="3"
                                    maxlength="255">
                                    @error('nombreFacebook')
                                        <div id="facebookError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>X/Twitter</h4>
                                    <input type="text" class="form-control" id="twitter" name="twitter" value="{{old('twitter')}}" minlength="3"
                                    maxlength="255">
                                    @error('twitter')
                                        <div id="facebookError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Instagram</h4>
                                    <input type="text" class="form-control" id="instagram" name="instagram" value="{{old('instagram')}}" minlength="3"
                                    maxlength="255">
                                    @error('instagram')
                                        <div id="facebookError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="datosDomicilio">
                        {{-- CONTENEDOR DATOS DE DOMICILIO --}}
                        <div id="datosDomicilio" class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Datos de domicilio</h3>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col">
                                    <h4>Calle principal</h4>
                                    <input type="text" class="form-control" id="calle1" name="calle1" value="{{old('calle1')}}"

                                    onblur="if (this.value == '') {this.value = '';}"
                                    onfocus="if (this.value == '') {this.value = '';}">
                                    @error('calle1')
                                        <div id="calleError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Entre calle 1</h4>
                                    <input type="text" class="form-control" id="calle2" name="calle2" value="{{old('calle2')}}"

                                    onblur="if (this.value == '') {this.value = '';}"
                                    onfocus="if (this.value == '') {this.value = '';}">
                                    @error('calle2')
                                        <div id="calleError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Entre calle 2</h4>
                                    <input type="text" class="form-control" id="calle3" name="calle3" value="{{old('calle3')}}"

                                    onblur="if (this.value == '') {this.value = '';}"
                                    onfocus="if (this.value == '') {this.value = '';}">
                                    @error('calle3')
                                        <div id="calleError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col">
                                    <h4>Número Externo</h4>
                                    <input type="number" class="form-control" id="numeroExterior" name="numeroExterior" value="{{old('numeroExterior')}}">
                                    @error('numeroExterior')
                                        <div id="numeroExteriorError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Número Interno</h4>
                                    <input type="text" class="form-control" id="numeroInterior" name="numeroInterior" value="{{old('numeroInterior')}}">
                                    @error('numeroInterior')
                                        <div id="numeroInteriorError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col" id="fondoColonia">
                                    <h4>Colonia</h4>
                                    <select class="form-select selectToo" id="colonias" name="colonia" style="width: 100%">
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaColonias as $colonia)
                                            <option value="{{$colonia->id}}">{{$colonia->nombre}}, {{$colonia->seccionColonia[0]->seccion->distritoLocal->municipio->nombre}}</option>
                                        @endforeach
                                    </select>
                                    @error('colonia')
                                        <div id="coloniaError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                            <br>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col">
                                    <h4>Código Postal</h4>
                                    <input type="number" class="form-control" id="codigoPostal" name="codigoPostal" value="{{old('codigoPostal')}}">
                                    @error('codigoPostal')
                                    <div id="codigoPostalError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col d-none" id="fondoDelegacion">
                                    <h4>Ciudad o localidad</h4>
                                    <select class="form-select selectToo" id="ciudad" name="ciudad">
                                        <option value="0">SIN DATO</option>

                                    </select>
                                    @error('ciudad')
                                        <div id="municipioError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col" id="fondoDelegacion">
                                    <h4>Municipio o Delegación</h4>
                                    <select class="form-select selectToo" id="municipios" name="municipio">
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaMunicipios as $municipio)
                                        <option value="{{$municipio->id}}">{{$municipio->nombre}}</option>
                                    @endforeach
                                    </select>
                                    @error('municipio')
                                        <div id="municipioError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>


                            </div>
                            <div class="row row-cols-1 row-cols-sm-3">
                                {{-- <div class="col">
                                    <h4>Entidad federativa</h4>
                                    <select class="form-select selectToo" id="entidadFederativa" name="entidadFederativa">
                                        <option value="0">SIN DATO</option>
                                    </select>
                                    @error('entidadFederativa')
                                        <div id="municipioError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div> --}}
                                <div class="col d-none" id="fondoDelegacion">
                                    <h4>País</h4>
                                    <select class="form-select selectToo" id="pais" name="municipio" style="width:100%">
                                        <option value="0">SIN DATO</option>
                                        <option value="1">MÉXICO</option>
                                    </select>
                                    @error('municipio')
                                        <div id="municipioError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col" id="fondoDelegacion">
                                    <h4>Referencias</h4>
                                    <input type="text" class="form-control" id="referencia" name="referencia" value="{{old('referencia')}}">
                                    @error('referencia')
                                    <div id="codigoPostalError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>


                            </div>
                            <br>
                            <h4>¿Donde vive la persona? (Dar double click para crear una marca)</h4>
                            <center>
                                <input type="hidden" id="coordenadas" name="coordenadas" value="{{old('coordenadas')}}">
                                <input type="text" class="col-3 d-none" id="cordenada" class="form-control" value="{{old('coordenadas')}}" disabled>
                            </center>
                            <center>
                                <div id="map" class="mx-auto" style="width:100%;height:400px"></div>
                                @error('coordenadas')
                                        <div class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </center>
                        </div>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="datosIdentificacion">
                        {{-- CONTENEDOR DATOS DE IDENTIFICACION --}}
                        <div id="datosIdentificacion" class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Datos de identificación</h3>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col">
                                    <h4>CURP</h4>
                                    <input type="text" style="text-transform: uppercase; color: black" class="form-control" id="curp" name="curp" value="{{old('curp')}}" minlength="18" maxlength="18"
                                    placeholder="ABCD123456HBCDEF12">
                                    @error('curp')
                                        <div id="curpError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>RFC</h4>
                                    <input type="text" style="text-transform: uppercase; color: black" class="form-control" id="rfc" name="claveElectoral" value="{{old('claveElectoral')}}"
                                    minlength="18" maxlength="18" placeholder="ABCDEF12345678BH1">
                                    @error('claveElectoral')
                                        <div id="claveElectoralError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col d-none" id="fondoSeccion">
                                    <h4>Lugar de nacimiento</h4>
                                    <select class="form-select selectToo" id="lugarNacimiento" name="lugarNacimiento" style="width:100%">
                                        <option value="0">SIN DATO</option>
                                    </select>
                                    @error('lugarNacimiento')
                                        <div id="seccionError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="datosRelacion">
                        {{-- CONTENEDOR DATOS DE RELACION --}}
                        <div id="datosRelacion" class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Datos de relación</h3>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col">
                                    <h4>Cliente</h4>
                                    <select class="form-select selectToo" id="clientes" name="cliente" style="width:100%">
                                        <option value="0" selected>SIN DATO</option>
                                        <option>SI</option>
                                        <option>NO</option>
                                    </select>
                                    @error('promotor')
                                        <div id="promotorError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Promotor</h4>
                                    <select class="form-select selectToo" id="promotorEstructura" name="promotorEstructura" style="width:100%">
                                        <option value="0" selected>SIN DATO</option>
                                        <option>SI</option>
                                        <option>NO</option>
                                    </select>
                                    @error('promotor')
                                        <div id="promotorError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Colaborador</h4>
                                    <select class="form-select selectToo" id="colaborador" name="colaborador" style="width:100%">
                                        <option value="0" selected>SIN DATO</option>
                                        <option>SI</option>
                                        <option>NO</option>
                                    </select>
                                    @error('promotor')
                                        <div id="promotorError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="otrosDatos">
                        {{-- CONTENEDOR OTROS DATOS --}}
                        <div id="otrosDatos" class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Otros datos</h3>
                            <h4>Etiquetas</h4>
                            <div class="row justify-content-between">
                                <div class="col-10">
                                    <input type="text" id="inputEtiquetaCrear" class="form-control" placeholder="',' para agregar etiqueta">
                                </div>
                                <div class="col-auto">
                                    <button type="button" id="agregarEtiquetaCrear" class="btn btn-primary">Agregar</button>
                                </div>
                            </div>
                            <div class="mt-3 contenedorEtiquetasCrear">
                                <!-- <span class="tag">oh my God <span class="remove-tag">&#10006;</span></span>
                                <span class="tag">second tag <span class="remove-tag">&#10006;</span></span>
                                <span class="tag">tag3 <span class="remove-tag">&#10006;</span></span> -->
                            </div>
                            <br>
                            <div class="row row-cols-1">
                                <div class="col">
                                    <h4>Observaciones</h4>
                                    <div class="form-group">
                                        <textarea class="form-control" rows="5" id="comment" name="observaciones"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <small class="mt-3">(*) Son campos obligatorios para el formulario</small>
            </div>
            <br>
            <div>
                <center>
                    <button id="BotonAgregarPersona" class="btn btn-primary" hidden></button>
                    <a id="BotonValidador" onclick="validar()" class="btn btn-primary" >
                        {{
                            (explode('/', url()->current()) [count(explode('/', url()->current())) - 1] == 'agregar') ?
                            'Agregar Persona' : 'Modificar Persona'
                        }}
                    </a>
                    <!-- <button class="btn btn-danger" type="button" class="cerrarFormulario">Limpiar</button> -->
                </center>
            </div>
        </div>
    </form>
</div>
@endsection



@section('scripts')
@if (session()->has('validarCamposFormPersona'))
    <script>
        Swal.fire({
            'title':"Error",
            'text':"{{session('validarCamposFormPersona')}}",
            'icon':"error"
        });
    </script>
@endif
    {{-- PASAR LIBRERIAS A PLANTILLA --}}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDg60SDcmNRPnG1tzZNBBGFx02cW2VkWWQ&callback=initMap&v=weekly" defer></script>
<script src="{{url('/')}}/js/validacionesFormulario.js" text="text/javascript"></script>
<script text="text/javascript">
    var marker;
    var marker2;
    const myLatLng = { lat: 24.123954, lng: - 110.311664 };
    var map;
    function placeMarker(location) {
            if (marker == undefined) {
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: "Ubicación",
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 15,
                        fillColor: "#F00",
                        fillOpacity: 0.4,
                        strokeWeight: 0.4,
                    },
                    animation: google.maps.Animation.DROP,
                });
                marker2 = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: "Ubicación",
                    animation: google.maps.Animation.DROP,
                });
            }
            else {
                marker.setPosition(location);
                marker2.setPosition(location);
            }
            map.setCenter(location);
    }
    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            disableDoubleClickZoom: true,
            zoom: 17,
            center: myLatLng,
            title: "Ubicación",
        });

        google.maps.event.addListener(map, 'dblclick', function (event) {
            placeMarker(event.latLng);
            document.getElementById("cordenada").value = event.latLng.lat() + ", " + event.latLng.lng();
            document.getElementById("coordenadas").value = event.latLng.lat() + "," + event.latLng.lng();
        });
    }
    window.initMap = initMap;
    function buscarUbicacion(nombre) {
        // Clave de API de Google Maps (reemplaza 'TU_API_KEY' con tu propia clave)
        var apiKey = 'AIzaSyDg60SDcmNRPnG1tzZNBBGFx02cW2VkWWQ';

        // URL de la API de Geocodificación de Google Maps
        var url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + encodeURIComponent(nombre) + '&key=' + apiKey;

        // Realizar la solicitud HTTP GET utilizando Fetch API
        fetch(url)
        .then(response => response.json())
        .then(data => {
            // Verificar si la respuesta tiene resultados
            if (data.results.length > 0) {
                // Obtener las coordenadas de la primera ubicación encontrada
                var ubicacion = data.results[0].geometry.location;
                var latitud = ubicacion.lat;
                var longitud = ubicacion.lng;

                // Aquí puedes usar latitud y longitud como desees
                document.getElementById("cordenada").value = latitud + ", " + longitud;
                document.getElementById("coordenadas").value = latitud + "," + longitud;
                placeMarker({lat: latitud, lng: longitud});
            }
        })
        .catch(error => {
            console.error('Error al buscar la ubicación:', error);
        });
    }
    //FIN MAPA

    function filtrarColonia(){
        let colonia = $('#colonias').val();
        $.when(
        $.ajax({
                type: "get",
                url: `{{url('/')}}/simpatizantes/filtrarColonias-${colonia}`,
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    $('#municipios').val(response.municipio);
                    $('#municipios').trigger('change');
                    $('#codigoPostal').val(response.codigoPostal);
                    buscarUbicacion(`${response.nombreColonia}, ${response.codigoPostal}, ${response.nombreMunicipio}, B.C.S, México`);
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
        });
    }

    $('#colonias').change(filtrarColonia);

    function contarRepeticiones(arreglo) {
        // Utilizamos reduce para acumular el conteo de repeticiones
        let resumen = arreglo.reduce((conteo, objeto) => {
            // Si el nombre ya existe en el conteo, incrementar el contador
            if (conteo[objeto.nombre]) {
                conteo[objeto.nombre]++;
            } else {
                // Si no existe, inicializar el contador en 1
                conteo[objeto.nombre] = 1;
            }
            return conteo;
        }, {});

        const nombresRepetidos = Object.keys(resumen).filter(nombre => resumen[nombre] > 1);

        // Crear un objeto con los nombres repetidos y su conteo
        const resultado = {};
        nombresRepetidos.forEach(nombre => {
        resultado[nombre] = resumen[nombre];
        });

        return resultado;
    }

    // FUNCION PARA CARGAR TABLA DE USUARIOS
    $(document).ready(function () {
        // Swal.fire({
        //     title: 'Cargando...',
        //     allowOutsideClick: false,
        //     showConfirmButton: false,
        //     html: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
        // });
        @if (old('fechaRegistro'))
            $('#fechaRegistro').val("{{old('fechaRegistro')}}");
        @endif
        @if (old('etiquetas'))
            let etiquetasCrudas = @json(old('etiquetas'));
            let etiquedasPreprocesar = (etiquetasCrudas != null) ? etiquetasCrudas.split(',') : [];
            $.each(etiquedasPreprocesar, function (i, valor) {
                createTag(valor);
            });
        @endif
        @if(old('coordenadas'))
            placeMarker({lat: {{explode(',', old('coordenadas'))[0]}}, lng: {{explode(',', old('coordenadas'))[1]}}});
            $('input[name="coordenadas"]').val(`{{explode(',', old('coordenadas'))[0]}},{{explode(',', old('coordenadas'))[1]}}`);

        @endif
        @if(old('observaciones'))
            $('#comment').html("{{old('observaciones')}}");
        @endif
    });



    $('#curp').on('input', soloMayusculas);
    $('#rfc').on('input', soloMayusculas);
    function soloMayusculas(){
        $(this).val($(this).val().toUpperCase());
    }

    const button = document.querySelector('#agregarEtiquetaCrear');
    const tagInput = document.querySelector('#inputEtiquetaCrear');

    const tagContainer = document.querySelector('.contenedorEtiquetasCrear');
    let tags = [];

    const createTag = (tagValue) => {
        const value = tagValue.trim();

        if (value === '' || tags.includes(value)) return;

        const tag = document.createElement('span');
        tag.setAttribute('class', 'tag');

        const valor = document.createElement('span');
        valor.setAttribute('class', 'valor');
        valor.innerHTML = value;
        tag.appendChild(valor);

        const close = document.createElement('span');
        close.setAttribute('class', 'remove-tag');
        close.innerHTML = '&#10006;';
        close.onclick = handleRemoveTag;

        tag.appendChild(close);
        tagContainer.appendChild(tag);
        tags.push(tag);

        tagInput.value = '';
        tagInput.focus();
    };

    const handleRemoveTag = (e) => {
        const indexEtiqueta = tags.findIndex(function(elemento, i){
            if(elemento.childNodes[0].innerHTML == e.target.parentElement.childNodes[0].innerHTML){
                return true;
            }
        });
        e.target.parentElement.remove()
        if(indexEtiqueta > -1){
            tags.splice(indexEtiqueta, 1);
        }
    };
    tagInput.addEventListener('keyup', (e) => {
        const { key } = e;
        if (key === ',' || key === 'Enter') {
            e.preventDefault();
            createTag(tagInput.value.substring(0, tagInput.value.length - 1));
        }
    });
    button.addEventListener('click', (e) => {
        createTag(tagInput.value);
    });


    $('#formularioAgregarSimpatizante').submit(function (e) {

        let txtCelular = $("#telefonoCelular").val();
        let telefonoFijo = $("#telefonoFijo").val();
        let nombres = $("#nombre").val();
        let apellidoPaterno = $("#apellido_paterno").val();
        let correo = $("#correo").val();
        let calle = $("#calle").val();
        let numeroExterior = $("#numeroExterior").val();
        let colonia = $("#colonias").val();
        let codigoPostal = $("#codigoPostal").val();
        let municipio = $("#municipios").val();
        if(true){
            if($('#inputEtiquetaCrear').is(':focus')){
                return false;
            }
            else{
                if($('#formularioAgregarSimpatizante #etiquetas').length == 0){
                    let etiquetas = "";
                    $.each(tags, function (i, value) {
                        etiquetas += `${value.childNodes[0].innerHTML},`;
                        if(etiquetas.length > 0 && tags.length - 1 == i){
                            etiquetas = etiquetas.slice(0, -1);
                        }
                    });
                    $('#formularioAgregarSimpatizante').append(
                        $('<input>').attr('name', 'etiquetas').attr('id', 'etiquetas').attr('type', 'hidden')
                        .val(etiquetas)
                    );

                }
            }
        }
        else{
            Swal.close();
            Swal.fire({
                'title':"Error",
                'text':"Verifique los datos de contacto ingresados.",
                'icon':"error"
            });
            return false;
        }
    });
    $('#fechaNacimiento').change(function (e) {
        var fechaNacimiento = new Date($('#fechaNacimiento').val());
        var hoy = new Date();

        var edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
        var mes = hoy.getMonth() - fechaNacimiento.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
            edad--;
        }
        if(18 <= edad && edad <= 28){
            $('#rangoEdad').val(23);
        }
        else if(29 <= edad && edad <= 39){
            $('#rangoEdad').val(34);
        }
        else if(40 <= edad && edad <= 49){
            $('#rangoEdad').val(45);
        }
        else if(50 <= edad && edad <= 69){
            $('#rangoEdad').val(55);
        }
        else if(70 <= edad){
            $('#rangoEdad').val(74);
        }
        else{
            $('#rangoEdad').val(0);
        }
    });

    @if (str_contains(url()->current(), 'modificar') && !session()->has('mensajeError'))
        cargarFormulario();
        function cargarFormulario(){
            $("#fechaRegistro").val('{{$persona->fecha_registro}}');
            $("#folio").val('{{$persona->folio}}');
            $("#promotores").val('{{($persona->persona_id != null) ? $persona->persona_id : 0}}');
            $("#origen").val('{{$persona->origen}}');
            $("#referenciaOrigen").val('{{($persona->referenciaOrigen != null) ? $persona->referenciaOrigen : 0}}');
            $("#estatus").val('{{($persona->estatus != null) ? $persona->estatus : 0}}');
            $("#apodo").val('{{$persona->apodo}}');
            $("#nombres").val('{{$persona->nombres}}');
            $("#apellidoPaterno").val('{{$persona->apellido_paterno}}');
            $("#apellidoMaterno").val('{{$persona->apellido_materno}}');
            $('#genero').val('{{($persona->genero != null) ? $persona->genero : 0}}');
            $("#fechaNacimiento").val('{{$persona->fecha_nacimiento}}');
            $("#rangoEdad").val('{{$persona->edadPromedio}}');
            $("#telefonoCelular1").val('{{$persona->telefonoCelular1}}');
            $("#telefonoCelular2").val('{{$persona->telefonoCelular2}}');
            $("#telefonoCelular3").val('{{$persona->telefonoCelular3}}');
            $("#correo").val('{{$persona->correo}}');
            $("#correoAlternativo").val('{{$persona->correoAlternativo}}');
            $("#telefonoFijo").val('{{$persona->telefono_fijo}}');
            $("#nombreFacebook").val('{{$persona->nombre_en_facebook}}');
            $("#twitter").val('{{$persona->twitter}}');
            $("#instagram").val('{{$persona->instagram}}');
            $("#calle1").val('{{$persona->identificacion->domicilio->calle1}}');
            $("#calle2").val('{{$persona->identificacion->domicilio->calle2}}');
            $("#calle3").val('{{$persona->identificacion->domicilio->calle3}}');
            $("#numeroExterior").val('{{$persona->identificacion->domicilio->numero_exterior}}');
            $("#numeroInterior").val('{{$persona->identificacion->domicilio->numero_interior}}');
            $("#colonias").val('{{($persona->identificacion->domicilio->colonia_id != 0) ? $persona->identificacion->domicilio->colonia_id : 0}}');
            $("#colonias").trigger('change');
            // $("#codigoPostal").val('');
            // $("#ciudad").val();
            // $("#municipios").val();
            // $("#entidadFederativa").val();
            // $("#pais").val();
            $("#referencia").val('{{$persona->identificacion->domicilio->referencia}}');
            $("#coordenada").val('{{$persona->identificacion->domicilio->latitud}}, {{$persona->identificacion->domicilio->longitud}}');
            $("#cordenada").val('{{$persona->identificacion->domicilio->latitud}}, {{$persona->identificacion->domicilio->longitud}}');
            $("#curp").val('{{$persona->identificacion->curp}}');
            $("#rfc").val('{{$persona->identificacion->clave_elector}}');
            $("#lugarNacimiento").val('{{$persona->identificacion->lugarNacimiento}}');
            $("#clientes").val("{{($persona->cliente != null) ? $persona->cliente : 0}}");
            $("#promotorEstructura").val("{{($persona->promotor != null) ? $persona->promotor : 0}}");
            $("#colaborador").val("{{($persona->colaborador != null) ? $persona->colaborador : 0}}");
            $("#comment").val();
            let etiquedasPreprocesar = ("{{$persona->etiquetas}}" != null) ? "{{$persona->etiquetas}}".split(',') : [];
            $.each(etiquedasPreprocesar, function (i, valor) {
                createTag(valor);
            });
            //casos especiales
            // <input type="hidden" id="coordenadas" name="coordenadas"
            // <div class="mt-3 contenedorEtiquetasCrear"
        }
    @endif
    </script>
@endsection
