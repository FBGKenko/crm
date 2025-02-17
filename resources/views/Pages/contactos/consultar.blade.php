@extends('Pages.plantilla')

@section('tittle')
    Consultar Persona
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
<BR>
<div class="card" class="m-3">
    <div class="card-header">
        <h3>
            Consultar Persona
        </h3>
    </div>
    <form id="" action=" {{  str_contains(url()->current(), 'agregar') ? route('contactos.agregar') : route('contactos.modificar', $persona->id) }}" method="post" style="">
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
                                </select>
                                @error('promotor')
                                    <div id="promotorError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-sm-3">
                            <div class="col">
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
                                </select>
                                @error('referenciaOrigen')
                                    <div id="fechaRegistroError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                            <div class="col">
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
                            <div class="col">
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
                                <h4>Genero</h4>
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
                            <div class="col" id="fondoDelegacion">
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
                                </select>
                                @error('municipio')
                                    <div id="municipioError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>


                        </div>
                        <div class="row row-cols-1 row-cols-sm-3">
                            <div class="col">
                                <h4>Entidad federativa</h4>
                                <select class="form-select selectToo" id="entidadFederativa" name="entidadFederativa">
                                    <option value="0">SIN DATO</option>
                                </select>
                                @error('entidadFederativa')
                                    <div id="municipioError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                            <div class="col" id="fondoDelegacion">
                                <h4>País</h4>
                                <select class="form-select selectToo" id="pais" name="municipio" style="width:100%">
                                    <option value="0">SIN DATO</option>
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
                            <div class="col" id="fondoSeccion">
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
                    <div id="datosRelacion" class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                        <h3>Datos de relación</h3>
                        <div class="row row-cols-1 row-cols-sm-3">
                            <div class="col">
                                <h4>Cliente</h4>
                                <select class="form-select selectToo" id="clientes" name="cliente" style="width:100%">
                                    <option value="0" selected>SIN DATO</option>
                                </select>
                                @error('promotor')
                                    <div id="promotorError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                            <div class="col">
                                <h4>Promotor</h4>
                                <select class="form-select selectToo" id="promotorEstructura" name="promotorEstructura" style="width:100%">
                                    <option value="0" selected>SIN DATO</option>
                                </select>
                                @error('promotor')
                                    <div id="promotorError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                            <div class="col">
                                <h4>Colaborador</h4>
                                <select class="form-select selectToo" id="colaborador" name="colaborador" style="width:100%">
                                    <option value="0" selected>SIN DATO</option>
                                </select>
                                @error('promotor')
                                    <div id="promotorError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane container pt-3 fade" id="otrosDatos">
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
    <div class="card-body">
        {{-- FORMULARIO DE AGREGAR USUARIO --}}
        <div class="container">
            <br>
            <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                <h3>Datos de control</h3>
                <div class="row row-cols-1 row-cols-sm-3">
                    <div class="col">
                        <h4>Fecha de registro</h4>
                        <input type="date" class="form-control" id="fechaRegistro" name="fechaRegistro" min="{{date('Y-m-d', strtotime('-100 years'))}}" max="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}" disabled>
                    </div>
                    <div class="col">
                        <h4>Folio</h4>
                        <input type="number" min="0" maxlength="7" class="form-control" id="folio" name="folio" value="{{old('folio')}}" disabled>
                    </div>
                    <div class="col">
                        <h4>Promotor</h4>
                        <select class="form-select selectToo" id="promotores" name="promotor" disabled>
                            <option value="0" selected>Sin dato</option>
                        </select>
                    </div>
                </div>
            </div>
            <br>
            <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                <h3>Datos personales</h3>
                <div class="row row-cols-1 row-cols-sm-3">
                    <div class="col">
                        <h4 class="fw-bold">Apellido paterno (*)</h4>
                        <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="{{old('apellido_paterno')}}"
                        minlength="3" maxlength="255" onkeydown="return /[a-z, ]/i.test(event.key)"
                        onblur="if (this.value == '') {this.value = '';}" onfocus="if (this.value == '') {this.value = '';}" disabled>
                    </div>
                    <div class="col">
                        <h4>Apellido materno</h4>
                        <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="{{old('apellido_materno')}}"
                        minlength="3" maxlength="255" onkeydown="return /[a-z, ]/i.test(event.key)"
                        onblur="if (this.value == '') {this.value = '';}" onfocus="if (this.value == '') {this.value = '';}" disabled>
                    </div>
                    <div class="col">
                        <h4 class="fw-bold">Nombre(s) (*)</h4>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{old('nombre')}}" minlength="3" maxlength="255"
                        onkeydown="return /[a-z, ]/i.test(event.key)"
                        onblur="if (this.value == '') {this.value = '';}"
                        onfocus="if (this.value == '') {this.value = '';}" disabled>
                    </div>
                </div>
                <br>
                <div class="row row-cols-1 row-cols-sm-3">
                    <div class="col">
                        <h4>Genero</h4>
                        <select name="genero" class="form-control" disabled>
                            <option {{old('genero') == 'SIN ESPECIFICAR' ? 'selected' : ''}} value="SIN ESPECIFICAR">SIN ESPECIFICAR</option>
                            <option {{old('genero') == 'HOMBRE' ? 'selected' : ''}} value="HOMBRE">HOMBRE</option>
                            <option {{old('genero') == 'MUJER' ? 'selected' : ''}} value="MUJER">MUJER</option>
                        </select>
                    </div>
                    <div class="col">
                        <h4>Fecha de Nacimiento</h4>
                        <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" value="{{old('fechaNacimiento')}}"
                        min="{{date('Y-m-d', strtotime('-100 years'))}}" max="{{date('Y-m-d', strtotime('-18 years'))}}" disabled>
                    </div>
                    <div class="col">
                        <h4>Rango de edad</h4>
                        <select id="rangoEdad" class="form-select" name="rangoEdad" disabled>
                            <option {{old('rangoEdad') == '23' ? 'selected' : ''}} value="23">18-28</option>
                            <option {{old('rangoEdad') == '34' ? 'selected' : ''}} value="34">29-39</option>
                            <option {{old('rangoEdad') == '45' ? 'selected' : ''}} value="45">40-49</option>
                            <option {{old('rangoEdad') == '55' ? 'selected' : ''}} value="55">50-69</option>
                            <option {{old('rangoEdad') == '74' ? 'selected' : ''}} value="74">70-adelante</option>
                        </select>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-3">
                    <div class="col">
                        <h4>Escolaridad</h4>
                        <select class="form-select" name="escolaridad" disabled>
                            <option value="0">Sin dato</option>
                            <option {{old('escolaridad') == 'SIN ESTUDIOS' ? 'selected' : ''}}>SIN ESTUDIOS</option>
                            <option {{old('escolaridad') == 'PRIMARIA' ? 'selected' : ''}}>PRIMARIA</option>
                            <option {{old('escolaridad') == 'SECUNDARIA' ? 'selected' : ''}}>SECUNDARIA</option>
                            <option {{old('escolaridad') == 'PREPARATORIA' ? 'selected' : ''}}>PREPARATORIA</option>
                            <option {{old('escolaridad') == 'UNIVERSIDAD' ? 'selected' : ''}}>UNIVERSIDAD</option>
                            <option {{old('escolaridad') == 'MAESTRIA' ? 'selected' : ''}}>MAESTRIA</option>
                            <option {{old('escolaridad') == 'DOCTORADO' ? 'selected' : ''}}>DOCTORADO</option>
                        </select>
                    </div>
                    <div class="col">
                    </div>
                    <div class="col">

                    </div>
                </div>
            </div>
            <br>
            <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                <h3>Datos de contacto</h3>
                <div class="row row-cols-1 row-cols-sm-3">
                    <div class="col">
                        <h4 class="fw-bold">Telefono Celular (*)</h4>
                        <input type="number" class="form-control" id="telefonoCelular" name="telefonoCelular" value="{{old('telefonoCelular')}}"
                        minlength="10" maxlength="12" disabled>
                    </div>
                    <div class="col">
                        <h4>Telefono Fijo</h4>
                        <input type="number" class="form-control" id="telefonoFijo" name="telefonoFijo" value="{{old('telefonoFijo')}}"
                        minlength="10" maxlength="12" disabled>
                    </div>
                    <div class="col">
                        <h4>Correo electronico</h4>
                        <input type="email" style="text-transform: uppercase; color: black"  class="form-control" id="correo" name="correo"
                        value="{{old('correo')}}" minlength="3" maxlength="255" disabled>
                    </div>
                </div>
                <br>
                <div class="row row-cols-1 row-cols-sm-3">
                    <div class="col">
                        <h4>Facebook</h4>
                        <input type="text" class="form-control" id="facebook" name="facebook" value="{{old('facebook')}}" minlength="3"
                        maxlength="255" disabled>
                    </div>
                    <div class="col">

                    </div>
                    <div class="col">

                    </div>
                </div>
            </div>
            <br>
            <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                <h3>Datos de domicilio</h3>
                <div class="row row-cols-1 row-cols-sm-3">
                    <div class="col">
                        <h4>Calle</h4>
                        <input type="text" class="form-control" id="calle" name="calle" value="{{old('calle')}}"
                        onkeydown="return /[a-z, ]/i.test(event.key)"
                        onblur="if (this.value == '') {this.value = '';}"
                        onfocus="if (this.value == '') {this.value = '';}" disabled>
                    </div>
                    <div class="col">
                        <h4>Número Externo</h4>
                        <input type="number" class="form-control" id="numeroExterior" name="numeroExterior" value="{{old('numeroExterior')}}" disabled>
                    </div>
                    <div class="col">
                        <h4>Número Interno</h4>
                        <input type="number" class="form-control" id="numeroInterior" name="numeroInterior" value="{{old('numeroInterior')}}" disabled>
                    </div>
                </div>
                <br>
                <div class="row row-cols-1 row-cols-sm-3">
                    <div class="col">
                        <h4>Colonia</h4>
                        <select class="form-select selectToo" id="colonias" name="colonia" disabled>
                            <option value="0">Sin dato</option>
                        </select>
                    </div>
                    <div class="col">
                        <h4>Código Postal</h4>
                        <input type="number" class="form-control" id="codigoPostal" name="codigoPostal" value="{{old('codigoPostal')}}" disabled>
                    </div>
                    <div class="col">
                        <h4>Municipio o Delegación</h4>
                        <select class="form-select selectToo" id="municipios" name="municipio" disabled>
                            <option value="0">Sin dato</option>
                        </select>
                    </div>


                </div>
                <br>
                <h4>¿Donde vive la persona?</h4>
                <center>
                    <input type="hidden" id="coordenadas" name="coordenadas" value="{{old('coordenadas')}}">
                    <input type="text" class="col-3 d-none" id="cordenada" class="form-control" value="{{old('coordenadas')}}" disabled>
                </center>
                <center>
                    <div id="map" class="mx-auto" style="width:100%;height:400px"></div>
                </center>
            </div>
            <br>
            <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                <h3>Datos de identificación</h3>
                <div class="row row-cols-1 row-cols-sm-3">
                    <div class="col">
                        <h4>Clave Electoral</h4>
                        <input type="text" style="text-transform: uppercase; color: black" class="form-control" id="claveElectoral" name="claveElectoral" value="{{old('claveElectoral')}}"
                        minlength="18" maxlength="18" placeholder="ABCDEF12345678B123" disabled>
                    </div>
                    <div class="col">
                        <h4>CURP</h4>
                        <input type="text" style="text-transform: uppercase; color: black" class="form-control" id="curp" name="curp" value="{{old('curp')}}" minlength="18" maxlength="18"
                        placeholder="ABCD123456HBCDEF12" disabled>
                    </div>
                    <div class="col">
                        <div class="d-flex">
                            <h4>Sección</h4>
                            <a class="ms-3" title="Este dato se encuentra en su identificación INE">
                                <i class="bi bi-exclamation-circle-fill"></i>
                            </a>
                        </div>
                        <select class="form-select selectToo" id="secciones" name="seccion" disabled>
                            <option value="0">Sin dato</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row row-cols-1 row-cols-sm-3">
                    <div class="col">
                        <h4>Entidad Federativa</h4>
                        <select class="form-select selectToo" id="entidades" name="entidadFederativa" disabled>
                            <option value="0">Sin dato</option>
                        </select>
                    </div>
                    <div class="col">
                        <h4>Distrito Federal</h4>
                        <select class="form-select selectToo" id="distritosFederales" name="distritoFederal" disabled>
                            <option value="0">Sin dato</option>
                        </select>
                    </div>
                    <div class="col">
                        <h4>Distrito local</h4>
                        <select class="form-select selectToo" id="distritosLocales" name="distritoLocal" disabled>
                            <option value="0">Sin dato</option>
                        </select>
                    </div>
                </div>
            </div>
            <br>
            <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                <h3>Datos de relación</h3>
                <div class="row row-cols-1 row-cols-sm-3">
                    <div class="col">
                        <h4>Afiliado</h4>
                        <select class="form-select" name="esAfiliado" disabled>
                            <option value="0">Sin dato</option>
                            <option {{old('esAfiliado') == 'NO' ? 'selected' : ''}} value="NO">No</option>
                            <option {{old('esAfiliado') == 'SI' ? 'selected' : ''}} value="SI">Si</option>
                        </select>
                    </div>
                    <div class="col">
                        <h4>Simpatizantes</h4>
                        <select class="form-select" name="esSimpatizante" disabled>
                            <option value="0">Sin dato</option>
                            <option {{old('esSimpatizante') == 'NO' ? 'selected' : ''}} value="NO">No</option>
                            <option {{old('esSimpatizante') == 'SI' ? 'selected' : ''}} value="SI">Si</option>
                            <option {{old('esSimpatizante') == 'TALVEZ' ? 'selected' : ''}} value="TALVEZ">Talvez</option>
                        </select>
                    </div>
                    <div class="col">
                        <h4>Programa</h4>
                        <select class="form-select selectToo" name="programa" disabled>
                            <option value="NINGUNO">Sin dato</option>
                            <option value="PROGRAMA 1">Programa 1</option>
                            <option value="PROGRAMA 2">Programa 2</option>
                        </select>
                    </div>
                </div>
            </div>
            <br>
            <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                <h3>Datos de estructura</h3>
                <div class="row row-cols-1 row-cols-sm-3">
                    <div class="col">
                        <h4>Rol en estructura</h4>
                        <select class="form-select" id="rolEstructura" name="rolEstructura" disabled>
                            <option {{old('rolEstructura') == '-1' ? 'selected' : ''}} value="-1">Sin dato</option>
                            <option {{old('rolEstructura') == 'COORDINADOR ESTATAL' ? 'selected' : ''}} value="COORDINADOR ESTATAL">COORDINADOR ESTATAL</option>
                            <option {{old('rolEstructura') == 'COORDINADOR DE DISTRITO LOCAL' ? 'selected' : ''}} value="COORDINADOR DE DISTRITO LOCAL">COORDINADOR DE DISTRITO LOCAL</option>
                            <option {{old('rolEstructura') == 'COORDINADOR DE SECCIÓN' ? 'selected' : ''}} value="COORDINADOR DE SECCIÓN">COORDINADOR DE SECCIÓN</option>
                            <option {{old('rolEstructura') == 'PROMOTOR' ? 'selected' : ''}} value="PROMOTOR">PROMOTOR</option>
                        </select>
                    </div>
                    <div class="col">
                        <h4 id="rolNumeroEncabezado">Seleccione un rol en estructura</h4>
                        <input type="number" class="form-control" id="rolNumero" name="rolNumero" value="{{old('rolNumero')}}" disabled>
                    </div>
                    <div class="col">
                        <h4>Función asignada</h4>
                        <input type="text" class="form-control" id="funciones" name="funciones" value="{{old('funciones')}}" disabled>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-3">
                    <div class="col">
                        <h4>¿Tiene un rol temporal?</h4>
                        <select class="form-select" id="tieneRolTemporal" name="tieneRolTemporal" disabled>
                            <option {{old('tieneRolTemporal') == 'NO' ? 'selected' : ''}} value="NO">No</option>
                            <option {{old('tieneRolTemporal') == 'SI' ? 'selected' : ''}} value="SI">Si</option>
                        </select>
                    </div>
                    <div class="col">
                        <h4>Rol en estructura temporal</h4>
                        <select class="form-select" id="rolEstructuraTemporal" name="rolEstructuraTemporal" disabled>
                            <option {{old('rolEstructuraTemporal') == '-1' ? 'selected' : ''}} value="-1">Sin dato</option>
                            <option {{old('rolEstructuraTemporal') == 'COORDINADOR ESTATAL' ? 'selected' : ''}} value="COORDINADOR ESTATAL">COORDINADOR ESTATAL</option>
                            <option {{old('rolEstructuraTemporal') == 'COORDINADOR DE DISTRITO LOCAL' ? 'selected' : ''}} value="COORDINADOR DE DISTRITO LOCAL">COORDINADOR DE DISTRITO LOCAL</option>
                            <option {{old('rolEstructuraTemporal') == 'COORDINADOR DE SECCIÓN' ? 'selected' : ''}} value="COORDINADOR DE SECCIÓN">COORDINADOR DE SECCIÓN</option>
                            <option {{old('rolEstructuraTemporal') == 'PROMOTOR' ? 'selected' : ''}} value="PROMOTOR">PROMOTOR</option>
                        </select>
                    </div>
                    <div class="col">
                        <h4 id="rolNumeroEncabezadoTemporal">Seleccione un rol en estructura</h4>
                        <input type="number" class="form-control" id="rolNumeroTemporal" name="rolNumeroTemporal" value="{{old('rolNumeroTemporal')}}" disabled>
                    </div>

                </div>
            </div>
            <br>
            <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                <h3>Otros datos</h3>
                <h4>Etiquetas</h4>
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
                            <textarea class="form-control" rows="5" id="comment" name="observaciones" disabled></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <small>(*) Son campos obligatorios para el formulario</small>
        </div>
    </div>
</div>


    </script>
@endsection

@section('scripts')
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

    function filtrarSecciones(){
        let seccion = $('#secciones').val();
        $.when(
        $.ajax({
                type: "get",
                url: `{{url('/')}}/simpatizantes/filtrarSecciones-${seccion}`,
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    $('#entidades').val(response.entidad);
                    $('#entidades').trigger('change');
                    $('#distritosFederales').val(response.distritoFederal);
                    $('#distritosFederales').trigger('change');
                    $('#distritosLocales').val(response.distritoLocal);
                    $('#distritosLocales').trigger('change');
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
    $('#secciones').change(filtrarSecciones);


    // FUNCION PARA CARGAR TABLA DE USUARIOS
    $(document).ready(function () {
        Swal.fire({
            title: 'Cargando...',
            allowOutsideClick: false,
            showConfirmButton: false,
            html: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
        });
        $.when(
            $.ajax({
                type: "get",
                url: "{{route('agregarSimpatizante.inicializar')}}",
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    $.each(response.colonias, function (i, valor) {
                        $('#colonias').append(
                            $('<option>').val(valor.id).text(valor.nombre)
                        );
                    });
                    $.each(response.municipios, function (i, valor) {
                        $('#municipios').append(
                            $('<option>').val(valor.id).text(valor.nombre)
                        );
                    });
                    $.each(response.secciones, function (i, valor) {
                        $('#secciones').append(
                            $('<option>').text(valor.id)
                        );
                    });
                    $.each(response.entidades, function (i, valor) {
                        $('#entidades').append(
                            $('<option>').val(valor.id).text(valor.nombre)
                        );
                    });
                    $.each(response.distritosFederales, function (i, valor) {
                        $('#distritosFederales').append(
                            $('<option>').text(valor.id)
                        );
                    });
                    $.each(response.distritosLocales, function (i, valor) {
                        $('#distritosLocales').append(
                            $('<option>').text(valor.id)
                        );
                    });
                    $.each(response.promotores, function (i, valor) {
                        $('#promotores').append(
                            $('<option>').val(valor.id).text(`${valor.nombres} ${valor.apellido_paterno}`)
                        );
                    });

                    @if(old('colonia'))
                        $('#colonias').val({{old('colonia')}});
                    @endif
                    @if(old('municipio'))
                        $('#municipios').val({{old('municipio')}});
                    @endif
                    @if(old('seccion'))
                        $('#secciones').val({{old('seccion')}});
                    @endif
                    @if(old('entidadFederativa'))
                        $('#entidades').val({{old('entidadFederativa')}});
                    @endif
                    @if(old('distritoFederal'))
                        $('#distritosFederales').val({{old('distritoFederal')}});
                    @endif
                    @if(old('distritoLocal'))
                        $('#distritosLocales').val({{old('distritoLocal')}});
                    @endif
                    @if(old('promotor'))
                        $('#promotores').val({{old('promotor')}});
                    @endif
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
            @if (explode('/', url()->current()) [count(explode('/', url()->current())) - 1] != 'agregar' && session('noEsCargaInicial') == false)
                $.when(
                    $.ajax({
                        type: "get",
                        url: "{{url('/')}}/simpatizantes/modificar/cargarPersona-{{$persona->id}}",
                        data: [],
                        contentType: "application/x-www-form-urlencoded",
                        success: function (response) {
                            if(response.persona.created_at != null){
                                $('input[name="fechaRegistro"]').val(response.persona.created_at.substring(0, 10));
                            }
                            $('input[name="folio"]').val(response.persona.folio);
                            $('select[name="promotor"]').val(response.persona.persona_id != null ? response.persona.persona_id : 0);
                            $('select[name="promotor"]').trigger('change');
                            $('input[name="apellido_paterno"]').val(response.persona.apellido_paterno);
                            $('input[name="apellido_materno"]').val(response.persona.apellido_materno);
                            $('input[name="nombre"]').val(response.persona.nombres);
                            $('select[name="genero"]').val(response.persona.genero);
                            if(response.persona.fecha_nacimiento != null){
                                $('input[name="fechaNacimiento"]').val(response.persona.fecha_nacimiento.substring(0, 10));
                                $('#fechaNacimiento').trigger('change');
                            }
                            $('select[name="escolaridad"]').val(response.persona.escolaridad);
                            $('input[name="telefonoCelular"]').val(response.persona.telefono_celular);
                            $('input[name="telefonoFijo"]').val(response.persona.telefono_fijo);
                            $('input[name="correo"]').val(response.persona.correo);
                            $('input[name="facebook"]').val(response.persona.nombre_en_facebook);
                            $('input[name="calle"]').val(response.domicilio.calle);
                            $('input[name="numeroExterior"]').val(response.domicilio.numero_exterior);
                            $('input[name="numeroInterior"]').val(response.domicilio.numero_interior);
                            $('input[name="codigoPostal"]').val(response.colonia != null ? response.colonia.codigo_postal : 0);
                            $('select[name="municipio"]').val(response.municipio != null ? response.municipio : 0);
                            $('select[name="municipio"]').trigger('change');

                            $('select[name="colonia"]').val(response.domicilio.colonia_id != null ?   response.domicilio.colonia_id : 0);
                            if(response.domicilio.colonia_id != null){
                                //COMO HACER PARA QUE FUNCIONE DESPUES
                                // $('select[name="colonia"]').trigger('change');
                            }
                            $('input[name="claveElectoral"]').val(response.identificacion.clave_elector);
                            $('input[name="curp"]').val(response.identificacion.curp);
                            $('select[name="seccion"]').val(  response.identificacion.seccion_id != null ?   response.identificacion.seccion_id : 0);
                            if(response.identificacion.seccion_id != null){
                                $('select[name="seccion"]').trigger('change');
                            }
                            $('select[name="distritoLocal"]').val(  response.distritoLocal != null ?   response.distritoLocal : 0);
                            $('select[name="distritoLocal"]').trigger('change');
                            $('select[name="distritoFederal"]').val(  response.distritoFederal != null ?   response.distritoFederal : 0);
                            $('select[name="distritoFederal"]').trigger('change');
                            $('select[name="entidadFederativa"]').val(  response.entidad != null ?   response.entidad : 0);
                            $('select[name="entidadFederativa"]').trigger('change');
                            $('select[name="esAfiliado"]').val(response.persona.afiliado);
                            $('select[name="esSimpatizante"]').val(  response.persona.simpatizante != null ?   response.persona.simpatizante : 0);
                            $('select[name="programa"]').val(  response.persona.programa != null ?   response.persona.programa : 0);
                            $('select[name="programa"]').trigger('change');
                            if(response.persona.rolEstructuraTemporal != null){
                                $('#tieneRolTemporal').val('SI');
                                $('#tieneRolTemporal').trigger('change');
                            }
                            $('select[name="rolEstructura"]').val(response.persona.rolEstructura != null ?   response.persona.rolEstructura : 0);
                            $('select[name="rolEstructura"]').trigger('change');
                            $('input[name="rolNumero"]').val(response.persona.rolNumero);
                            $('select[name="rolEstructuraTemporal"]').val(response.persona.rolEstructuraTemporal != null ?   response.persona.rolEstructuraTemporal : 0);
                            $('select[name="rolEstructuraTemporal"]').trigger('change');
                            $('input[name="rolNumeroTemporal"]').val(response.persona.rolNumeroTemporal);
                            $('input[name="funciones"]').val(response.persona.funcion_en_campania);
                            $('textarea[name="observaciones"]').val(response.persona.observaciones);
                            if(response.domicilio.latitud != null){
                                $('input[name="coordenadas"]').val(`${response.domicilio.latitud},${response.domicilio.longitud}`);
                                placeMarker({lat: response.domicilio.latitud, lng: response.domicilio.longitud});
                            }

                            let etiquedasPreprocesar = (response.persona.etiquetas != null) ? response.persona.etiquetas.split(',') : [];
                            $.each(etiquedasPreprocesar, function (i, valor) {
                                createTag(valor);
                            });
                            $('#fechaRegistro').focus();
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
                        @if (old('rolEstructura'))
                            $('#rolEstructura').trigger("change");
                        @endif
                        @if (old('rolEstructuraTemporal'))
                            $('#tieneRolTemporal').trigger('change');
                            $('#rolEstructuraTemporal').trigger("change");
                        @endif
                        @if(old('coordenadas'))
                            placeMarker({lat: {{explode(',', old('coordenadas'))[0]}}, lng: {{explode(',', old('coordenadas'))[1]}}});
                            $('input[name="coordenadas"]').val(`{{explode(',', old('coordenadas'))[0]}},{{explode(',', old('coordenadas'))[1]}}`);

                        @endif
                        @if(old('observaciones'))
                            $('#comment').html("{{old('observaciones')}}");
                        @endif
                        $('.selectToo').select2({
                            language: {

                                noResults: function() {

                                return "No hay resultado";
                                },
                                searching: function() {

                                return "Buscando..";
                                }
                            }
                        });
                        Swal.close();
                    });
            @else
                $('.selectToo').select2({
                    language: {

                        noResults: function() {

                        return "No hay resultado";
                        },
                        searching: function() {

                        return "Buscando..";
                        }
                    }
                });
                Swal.close();
            @endif

        });
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
        @if (old('rolEstructura'))
            $('#rolEstructura').trigger("change");
        @endif
        @if (old('rolEstructuraTemporal'))
            $('#tieneRolTemporal').trigger('change');
            $('#rolEstructuraTemporal').trigger("change");
        @endif
        @if(old('coordenadas'))
            placeMarker({lat: {{explode(',', old('coordenadas'))[0]}}, lng: {{explode(',', old('coordenadas'))[1]}}});
            $('input[name="coordenadas"]').val(`{{explode(',', old('coordenadas'))[0]}},{{explode(',', old('coordenadas'))[1]}}`);

        @endif
        @if(old('observaciones'))
            $('#comment').html("{{old('observaciones')}}");
        @endif
    });


    $('#rolEstructuraTemporal').change(function (e) {
        // $('#rolNumeroTemporal').prop('disabled', false);
        switch ($(this).val()) {
            case 'COORDINADOR ESTATAL':
                $('#rolNumeroEncabezadoTemporal').text('¿En qué Entidad?');
                break;
                case 'COORDINADOR DE DISTRITO LOCAL':
                $('#rolNumeroEncabezadoTemporal').text('¿En qué Distrito?');
                break;
                case 'COORDINADOR DE SECCIÓN':
                $('#rolNumeroEncabezadoTemporal').text('¿En qué Sección?');
                break;
                case 'PROMOTOR':
                $('#rolNumeroEncabezadoTemporal').text('¿En qué Sección?');
                break;
            default:
                $('#rolNumeroEncabezadoTemporal').text('Seleccione un rol en estructura');
                // $('#rolNumeroTemporal').prop('disabled', true);
                break;
        }
    });
    $('#rolEstructura').change(function (e) {
        // $('#rolNumero').prop('disabled', false);
        switch ($(this).val()) {
            case 'COORDINADOR ESTATAL':
                $('#rolNumeroEncabezado').text('¿En qué Entidad?');
                break;
                case 'COORDINADOR DE DISTRITO LOCAL':
                $('#rolNumeroEncabezado').text('¿En qué Distrito?');
                break;
                case 'COORDINADOR DE SECCIÓN':
                $('#rolNumeroEncabezado').text('¿En qué Sección?');
                break;
                case 'PROMOTOR':
                $('#rolNumeroEncabezado').text('¿En qué Sección?');
                break;
            default:
                $('#rolNumeroEncabezado').text('Seleccione un rol en estructura');
                // $('#rolNumero').prop('disabled', true);
                break;
        }
    });

    // FUNCION CERRAR FORMULARIO
    $('.cerrarFormulario').click(function (e) {
        $('#formularioAgregarSimpatizante')[0].reset();
    });

    $('#curp').on('input', soloMayusculas);
    $('#claveElectoral').on('input', soloMayusculas);
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

        // tagInput.value = '';
        // tagInput.focus();
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
    });

    $('#tieneRolTemporal').change(function (e) {
        let opcion = $('#tieneRolTemporal').val();
        if(opcion == 'SI'){
            // $('#rolEstructuraTemporal').prop('disabled', false);
            // $('#rolNumeroTemporal').prop('disabled', false);
            $('#rolEstructuraTemporal').trigger('change');
        }
        else{
            // $('#rolEstructuraTemporal').prop('disabled', true);
            // $('#rolNumeroTemporal').prop('disabled', true);
        }
    });
    </script>
@endsection
