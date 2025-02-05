@extends('Pages.plantilla')
@section('tittle') {{ str_contains(url()->current(), 'agregar') ? 'Agregar Persona' : 'Modificar Persona' }} @endsection
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

    .contenedorScrolleable{
        max-height: 200px;
        overflow-y: auto;
    }
</style>
<br>

<div class="card" class="m-3">
    <div class="card-header">
        <div class="d-flex justify-content-start">
            <h3 class="me-4"> {{ str_contains(url()->current(), 'agregar') ? 'Agregar Persona' : 'Modificar Persona' }} </h3>
            <div class="ms-3">
                @if (!str_contains(url()->current(), 'agregar') && (Auth::user()->getRoleNames()->first() == 'SUPER ADMINISTRADOR'
                    || Auth::user()->getRoleNames()->first() == 'ADMINISTRADOR' || Auth::user()->getRoleNames()->first() == 'SUPERVISOR'))
                    <form action="{{route('contactos.supervisar', $persona->id)}}" method="post">
                        @csrf
                        @if ($supervisado)
                            <button class="btn btn-success fw-bold btnSupervisar">Supervisado</button>
                        @else
                            <button class="btn btn-danger fw-bold btnSupervisar">Sin Supervisar</button>
                        @endif
                    </form>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        <form id="formularioAgregarSimpatizante" action=" {{  str_contains(url()->current(), 'agregar') ? route('contactos.agregar') : route('contactos.modificar', $persona->id) }}" method="post" style="">
            @csrf
            <div class="container">
                @error('errorValidacion') <div class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div> @enderror
                <br>
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
                        <a class="nav-link" data-bs-toggle="tab" href="#datosUbicaciones">DATOS DE UBICACIÓN ELECTORAL</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#datosRelacion">DATOS DE RELACIÓN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#datosEstructura">DATOS DE ESTRUCTURA</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#datosRelacionEmpresa">RELACION CON EMPRESAS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#datosFacturacion">DATOS DE FACTURACIÓN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#otrosDatos">OTROS DATOS</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane container pt-3 active" id="datosControl">
                        <div id="datosControl" class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Datos de control </h3>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <x-inputFormulario tipo="date" identificador="fecha_registro" nombre="datosControl[fecha_registro]" label="Fecha de registro" valor="{{Carbon\Carbon::now()->format('Y-m-d')}}"/>
                                <x-inputFormulario tipo="number" identificador="folio" nombre="datosControl[folio]" label="Folio"
                                    valor="{{ old('datosControl[folio]') }}" />
                                <div class="col">
                                    <label class="form-label mt-3">Promotor</label>
                                    <select class="form-select selectToo" id="promotores" name="datosControl[promotor]">
                                        <option value="0" selected>SIN DATO</option>
                                        @foreach ($listaPromotores as $promotor)
                                                <option value="{{$promotor->id}}">{{$promotor->nombres}} {{$promotor->apellido_paterno}} {{$promotor->apellido_materno}}, {{$promotor->apodo}}</option>
                                        @endforeach
                                    </select>
                                    @error('datosControl[promotor]')
                                        <div id="promotorError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label mt-3">Origen</label>
                                    <select id="origen" name="datosControl[origen]" class="form-select selectToo">
                                        <option value="0">SIN DATO</option>
                                        <option>DIRECTO</option>
                                        <option>ORGANICO</option>
                                        <option>REFERENCIA</option>
                                    </select>
                                    @error('datosControl[origen]')
                                        <div class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <x-inputFormulario tipo="text" identificador="identificadorOrigen" nombre="datosControl[identificadorOrigen]" label="Identificador de Origen(Opcional)"
                                     deshabilitar="1" valor="{{ old('datosControl[identificadorOrigen]') }}" />
                                </div>
                                <div class="col">
                                    <label class="form-label mt-3">Estatus</label>
                                    <select id="estatus" name="datosControl[estatus]" class="form-select selectToo" >
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaEstatus as $estatus)
                                            <option>{{$estatus->concepto}}</option>
                                        @endforeach
                                    </select>
                                    @error('datosControl[estatus]')
                                        <div id="fechaRegistroError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label mt-3">Referencia de Origen</label>
                                    <select id="referenciaOrigen" name="datosControl[referenciaOrigen]" class="form-select selectToo" >
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaPersonas as $personaA)
                                                <option value="{{$personaA->id}}">{{$personaA->nombres}} {{$personaA->apellido_paterno}} {{$personaA->apellido_materno}}, {{$personaA->apodo}}</option>
                                        @endforeach

                                    </select>
                                    @error('datosControl[referenciaOrigen]')
                                        <div id="fechaRegistroError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <x-inputFormulario tipo="text" identificador="referenciaCampania" nombre="datosControl[referenciaCampania]" deshabilitar="1" label="Referencia de Campaña"
                                    valor="{{ old('datosControl[referenciaCampania]') }}" />
                                <x-inputFormulario tipo="text" identificador="etiquetasOrigen" nombre="datosControl[etiquetasOrigen]" label="Etiquetas de Origen"
                                    valor="{{ old('datosControl[etiquetasOrigen]') }}" />
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="datosPersonales">
                        <div id="datosPersonales" class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Datos personales</h3>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <x-inputFormulario tipo="text" identificador="apodo" nombre="datosPersonales[apodo]" label="Apodo*"
                                    valor="{{ old('datosPersonales[apodo]') }}" />
                                <x-inputFormulario tipo="text" identificador="nombres" nombre="datosPersonales[nombres]" label="Nombre(s)"
                                    valor="{{ old('datosPersonales[nombres]') }}" />
                                <x-inputFormulario tipo="text" identificador="apellido_paterno" nombre="datosPersonales[apellido_paterno]" label="Apellido paterno"
                                    valor="{{ old('datosPersonales[apellido_paterno]') }}" />
                                <x-inputFormulario tipo="text" identificador="apellido_materno" nombre="datosPersonales[apellido_materno]" label="Apellido materno"
                                    valor="{{ old('datosPersonales[apellido_materno]') }}" />
                                <div class="col">
                                    <label class="form-label mt-3">Sexo</label>
                                    <select name="datosPersonales[genero]" id="genero" class="form-select">
                                        <option {{old('datosPersonales[genero]') == 'SIN ESPECIFICAR' ? 'selected' : ''}} value="SIN ESPECIFICAR">SIN ESPECIFICAR</option>
                                        <option {{old('datosPersonales[genero]') == 'HOMBRE' ? 'selected' : ''}} value="HOMBRE">HOMBRE</option>
                                        <option {{old('datosPersonales[genero]') == 'MUJER' ? 'selected' : ''}} value="MUJER">MUJER</option>
                                    </select>
                                    @error('datosPersonales[genero]')
                                        <div id="generoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <x-inputFormulario tipo="date" identificador="fecha_nacimiento" nombre="datosPersonales[fecha_nacimiento]" label="Fecha de Nacimiento"
                                    valor="{{ old('datosPersonales[fecha_nacimiento]') }}" />
                            </div>
                            <br>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col">
                                    <label class="form-label mt-3">Rango de edad</label>
                                    <select id="rangoEdad" class="form-select" name="datosPersonales[rangoEdad]">
                                        <option {{old('datosPersonales[rangoEdad]') == '0' ? 'selected' : ''}} value="23">NO ESPECIFICÓ</option>
                                        <option {{old('datosPersonales[rangoEdad]') == '23' ? 'selected' : ''}} value="23">18-28</option>
                                        <option {{old('datosPersonales[rangoEdad]') == '34' ? 'selected' : ''}} value="34">29-39</option>
                                        <option {{old('datosPersonales[rangoEdad]') == '45' ? 'selected' : ''}} value="45">40-49</option>
                                        <option {{old('datosPersonales[rangoEdad]') == '55' ? 'selected' : ''}} value="55">50-69</option>
                                        <option {{old('datosPersonales[rangoEdad]') == '74' ? 'selected' : ''}} value="74">70-adelante</option>
                                    </select>
                                    @error('datosPersonales[rangoEdad]')
                                        <div id="rangoEdadError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col"> </div>
                                <div class="col"> </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="datosContacto">
                        <div id="datosContacto" class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Datos de contacto</h3>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col" style="width: 100%">
                                    <div class="d-flex justify-content-start mb-3">
                                        <label class="form-label me-4">Números de Telefonos</label>
                                        <button type="button" id="btnAgregarTelefono" class="btn btn-primary">Agregar Telefono</button>
                                    </div>
                                    <div id="contenedorTelefono" class="contenedorScrolleable">

                                    </div>
                                </div>
                                <div class="col" style="width: 100%">
                                    <div class="d-flex justify-content-start my-3">
                                        <label class="form-label me-4">Correos Electrónicos</label>
                                        <button type="button" id="btnAgregarCorreo" class="btn btn-primary">Agregar Correo</button>
                                    </div>
                                    <div id="contenedorCorreo" class="contenedorScrolleable">

                                    </div>
                                </div>
                            </div>
                            <br>
                            <h3>Redes Sociales</h3>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <x-inputFormulario tipo="text" nombre="datosContacto[nombre_en_facebook]" identificador="nombre_en_facebook" label="Facebook"
                                    valor="{{ old('datosContacto[nombre_en_facebook]') }}" />
                                <x-inputFormulario tipo="text" nombre="datosContacto[instagram]" identificador="instagram" label="Instagram"
                                    valor="{{ old('datosContacto[instagram]') }}" />
                                <x-inputFormulario tipo="text" nombre="datosContacto[twitter]" identificador="twitter" label="X/Twitter"
                                    valor="{{ old('datosContacto[twitter]') }}" />
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="datosDomicilio">
                        <div id="datosDomicilio" class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Datos de domicilio</h3>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <x-inputFormulario tipo="text" identificador="calle1" nombre="datosDomicilio[calle1]"  label="Calle principal"
                                    valor="{{ old('datosDomicilio[calle1]') }}" />
                                <x-inputFormulario tipo="text" identificador="calle2" nombre="datosDomicilio[calle2]"  label="Entre calle 1"
                                    valor="{{ old('datosDomicilio[calle2]') }}" />
                                <x-inputFormulario tipo="text" identificador="calle3" nombre="datosDomicilio[calle3]"  label="Entre calle 2"
                                    valor="{{ old('datosDomicilio[calle3]') }}" />
                                <x-inputFormulario tipo="number" identificador="numero_exterior" nombre="datosDomicilio[numero_exterior]"  label="Número Externo"
                                    valor="{{ old('datosDomicilio[numero_exterior]') }}" />
                                <x-inputFormulario tipo="number" identificador="numero_interior" nombre="datosDomicilio[numero_interior]"  label="Número Interno"
                                    valor="{{ old('datosDomicilio[numero_interior]') }}" />
                                <div class="col"></div>
                                <div class="col">
                                    <label class="form-label mt-3">Colonia</label>
                                    <select class="form-select selectToo" id="colonias" name="datosDomicilio[colonia]" style="width: 100%">
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaColonias as $colonia)
                                            <option value="{{$colonia->id}}">{{$colonia->nombre}}</option>
                                        @endforeach
                                    </select>
                                    @error('datosDomicilio[colonia]')
                                        <div id="coloniaError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                    <button type="button" id="btnAbrirModalCrearColonia" class="btn btn-success d-none">Agregar Colonia</button>
                                </div>
                                <x-inputFormulario tipo="number" identificador="codigoPostal" nombre="datosDomicilio[codigoPostal]" label="Código Postal"
                                    valor="{{ old('codigoPostal') }}" />
                                <x-inputFormulario tipo="text" identificador="localidad" nombre="datosDomicilio[localidad]" label="Ciudad o localidad"
                                    valor="{{ old('datosDomicilio[localidad]') }}" />
                                <div class="col">
                                    <label class="form-label mt-3">Municipio o Delegación</label>
                                    <select class="form-select selectToo" id="municipios" name="datosDomicilio[municipio]" style="width: 100%">
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaMunicipios as $municipio)
                                            <option value="{{$municipio->id}}">{{$municipio->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label mt-3">Entidad Federativa</label>
                                    <select class="form-select selectToo" id="entidadFederativas" name="datosDomicilio[entidadFederativa]" style="width: 100%">
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaEstados as $estado)
                                            <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <x-inputFormulario tipo="text" identificador="pais" nombre="datosDomicilio[pais]" label="País"
                                    valor="MÉXICO" />
                                <div class="col" style="width:100%">
                                    <label class="form-label mt-3">Referencias</label>
                                    <textarea name="datosDomicilio[referencia]" id="referencia" cols="30" rows="7" class="form-control">
                                        {{old('datosDomicilio[referencia]')}}
                                    </textarea>
                                    @error('datosDomicilio[referencia]')
                                    <div id="codigoPostalError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                            <br>
                            <label class="form-label mt-3">¿Donde vive la persona? (Dar double click para crear una marca)</label>
                            <center>
                                <input type="hidden" id="coordenadas" name="datosDomicilio[coordenadas]" value="{{old('datosDomicilio[coordenadas]')}}">
                            </center>
                            <center>
                                <div id="map" class="mx-auto" style="width:100%;height:400px"></div>
                                @error('datosDomicilio[coordenadas]')
                                        <div class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </center>
                        </div>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="datosIdentificacion">
                        <div id="datosIdentificacion" class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Datos de identificación</h3>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <x-inputFormulario tipo="text" identificador="curp" nombre="datosIdentificacion[curp]" label="CURP"
                                    valor="{{ old('datosIdentificacion[curp]') }}" />
                                <x-inputFormulario tipo="text" identificador="rfc" nombre="datosIdentificacion[rfc]" label="RFC"
                                    valor="{{ old('datosIdentificacion[rfc]') }}" />
                                <div class="col">
                                    <label class="form-label mt-3">Lugar de Nacimiento</label>
                                    <select class="form-select selectToo" id="lugarNacimiento" name="datosIdentificacion[lugarNacimiento]" style="width:100%">
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaEstados as $estado)
                                            <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                                        @endforeach
                                    </select>
                                    @error('datosIdentificacion[lugarNacimiento]')
                                        <div id="seccionError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="datosUbicaciones">
                        <div id="datosIdentificacion" class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Datos de Ubicación Electoral</h3>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <x-inputFormulario tipo="text" identificador="clave_elector" nombre="datosUbicacion[clave_elector]" label="Clave Electoral"
                                    valor="{{ old('datosUbicacion[clave_elector]') }}" />
                                <div class="col">
                                    <div class="d-flex">
                                        <label class="form-label mt-3">Sección</label>
                                    </div>
                                    <select class="form-select selectToo" id="secciones" name="datosUbicacion[seccion]" style="width:100%">
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaSecciones as $seccion)
                                            <option>{{$seccion->id}}</option>
                                        @endforeach
                                    </select>
                                    @error('datosUbicacion[seccion]')
                                        <div id="seccionError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <div class="d-flex">
                                        <label class="form-label mt-3">Distrito Local</label>
                                    </div>
                                    <select class="form-select selectToo" id="distritoLocals" style="width:100%">
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaDistritoLocal as $seccion)
                                            <option>{{$seccion->id}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <div class="d-flex">
                                        <label class="form-label mt-3">Municipio</label>
                                    </div>
                                    <select class="form-select selectToo" id="municipiosUbicacion" style="width:100%">
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaMunicipios as $municipio)
                                            <option value="{{$municipio->id}}">{{$municipio->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <div class="d-flex">
                                        <label class="form-label mt-3">Distrito Federal</label>
                                    </div>
                                    <select class="form-select selectToo" id="distritoFederals" style="width:100%">
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaDistritoFederal as $seccion)
                                            <option>{{$seccion->id}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <div class="d-flex">
                                        <label class="form-label mt-3">Entidad Federativa</label>
                                    </div>
                                    <select class="form-select selectToo" id="entidadFederativaUbicacions" style="width:100%">
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaEstados as $seccion)
                                            <option value="{{$seccion->id}}">{{$seccion->id}}, {{$seccion->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="datosRelacion">
                        <div id="datosRelacion" class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Datos de relación</h3>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col">
                                    <label class="form-label mt-3">Afiliado</label>
                                    <select class="form-select" name="datosRelaciones[esAfiliado]">
                                        <option value="0">SIN DATO</option>
                                        <option {{old('datosRelaciones[esAfiliado]') == 'NO' ? 'selected' : ''}} value="NO">No</option>
                                        <option {{old('datosRelaciones[esAfiliado]') == 'SI' ? 'selected' : ''}} value="SI">Si</option>
                                    </select>
                                    @error('datosRelaciones[esAfiliado]') <div id="esAfiliadoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div> @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label mt-3">Simpatizantes</label>
                                    <select class="form-select" name="datosRelaciones[esSimpatizante]">
                                        <option value="0">SIN DATO</option>
                                        <option {{old('datosRelaciones[esSimpatizante]') == 'NO' ? 'selected' : ''}} value="NO">No</option>
                                        <option {{old('datosRelaciones[esSimpatizante]') == 'SI' ? 'selected' : ''}} value="SI">Si</option>
                                        <option {{old('datosRelaciones[esSimpatizante]') == 'TALVEZ' ? 'selected' : ''}} value="TALVEZ">Talvez</option>
                                    </select>
                                    @error('datosRelaciones[esSimpatizante]') <div id="esSimpatizanteError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div> @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label mt-3">Relación Personalizada</label>
                                    <select class="form-select selectToo" name="datosRelaciones[programa]" style="width:100%">
                                        <option value="NINGUNO">SIN DATO</option>
                                        @foreach ($listaFuncionesPersonalida as $tipo)
                                            <option value="{{$tipo->id}}">{{$tipo->Nombre}}</option>
                                        @endforeach
                                    </select>
                                    @error('datosRelaciones[programa]') <div id="programaError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div> @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label mt-3">Cliente</label>
                                    <select class="form-select selectToo" id="clientes" name="datosRelaciones[cliente]" style="width:100%">
                                        <option value="0" selected>SIN DATO</option>
                                        <option>SI</option>
                                        <option>NO</option>
                                    </select>
                                    @error('datosRelaciones[cliente]') <div id="promotorError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div> @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label mt-3">Promotor</label>
                                    <select class="form-select selectToo" id="promotorEstructura" name="datosRelaciones[promotorEstructura]" style="width:100%">
                                        <option value="0" selected>SIN DATO</option>
                                        <option>SI</option>
                                        <option>NO</option>
                                    </select>
                                    @error('datosRelaciones[promotorEstructura]') <div id="promotorError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div> @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label mt-3">Colaborador</label>
                                    <select class="form-select selectToo" id="colaborador" name="datosRelaciones[colaborador]" style="width:100%">
                                        <option value="0" selected>SIN DATO</option>
                                        <option>SI</option>
                                        <option>NO</option>
                                    </select>
                                    @error('datosRelaciones[colaborador]') <div id="promotorError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="datosEstructura">
                        <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Datos de Estructura</h3>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col">
                                    <label class="form-label mt-3">Rol en estructura</label>
                                    <select class="form-select" id="rolEstructura" name="datosEstructura[rolEstructura]">
                                        <option {{old('datosEstructura[rolEstructura]') == '-1' ? 'selected' : ''}} value="-1">SIN DATO</option>
                                        <option {{old('datosEstructura[rolEstructura]') == 'COORDINADOR ESTATAL' ? 'selected' : ''}} value="COORDINADOR ESTATAL">COORDINADOR ESTATAL</option>
                                        <option {{old('datosEstructura[rolEstructura]') == 'COORDINADOR DE DISTRITO LOCAL' ? 'selected' : ''}} value="COORDINADOR DE DISTRITO LOCAL">COORDINADOR DE DISTRITO LOCAL</option>
                                        <option {{old('datosEstructura[rolEstructura]') == 'COORDINADOR DE SECCIÓN' ? 'selected' : ''}} value="COORDINADOR DE SECCIÓN">COORDINADOR DE SECCIÓN</option>
                                        <option {{old('datosEstructura[rolEstructura]') == 'PROMOTOR' ? 'selected' : ''}} value="PROMOTOR">PROMOTOR</option>
                                    </select>
                                    @error('datosEstructura[rolEstructura]')
                                        <div id="rolEstructuraError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>

                                <div class="col">
                                    <div class="d-flex">
                                        <label class="form-label mt-3" id="rolNumeroEncabezado">Seleccione un rol en estructura</label>
                                    </div>
                                    <select class="form-select selectToo" id="rolNumero" name="datosEstructura[rolNumero][]" multiple style="width:100%" disabled>
                                        <option value="0">SIN DATO</option>
                                    </select>
                                </div>

                                <x-inputFormulario tipo="text" identificador="funcionAsignada" nombre="datosEstructura[funcionAsignada]" label="Función Asignada"
                                    valor="{{ old('datosEstructura[funcionAsignada]') }}" />
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="datosRelacionEmpresa">
                        <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Relación con empresas</h3>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col" style="width: 100%">
                                    <div class="d-flex justify-content-start my-3">
                                        <label class="form-label me-4">Relaciones con Empresas</label>
                                        <button type="button" id="btnAgregarRelacion" class="btn btn-primary">Agregar Relación</button>
                                    </div>
                                    <div id="contenedorRelaciones" class="contenedorScrolleable">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="datosFacturacion">
                        <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Datos de Facturación</h3>
                            <div class="row row-cols-1 row-cols-sm-3">
                                <div class="col" style="width: 66%">
                                    <label class="form-label mt-3">Desea utilizar los datos de domicilio ingresados en la sección: DATOS DE DOMICILIO</label>
                                    <div class="d-flex justify-content-start">
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="radio" name="reutilizarDomicilio" value="true" id="reutilizarDomicilioSi">
                                            <label class="form-check-label">
                                                Si
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="reutilizarDomicilio" value="false" id="reutilizarDomicilioNo" checked>
                                            <label class="form-check-label">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <x-inputFormulario tipo="text" identificador="rfcFacturacion" nombre="datosFacturacion[rfc]" label="RFC Facturación" clases="campoFacturacion"
                                    valor="{{ old('datosFacturacion[rfc]') }}" />
                                <x-inputFormulario tipo="text" identificador="calle1Facturacion" nombre="datosFacturacion[calle1]" label="Calle principal" clases="campoFacturacion"
                                    valor="{{ old('datosFacturacion[calle1]') }}" />
                                <x-inputFormulario tipo="text" identificador="calle2Facturacion" nombre="datosFacturacion[calle2]" label="Entre calle 1" clases="campoFacturacion"
                                    valor="{{ old('datosFacturacion[calle2]') }}" />
                                <x-inputFormulario tipo="text" identificador="calle3Facturacion" nombre="datosFacturacion[calle3]" label="Entre calle 2" clases="campoFacturacion"
                                    valor="{{ old('datosFacturacion[calle3]') }}" />
                                <x-inputFormulario tipo="number" identificador="numero_exteriorFacturacion" nombre="datosFacturacion[numero_exterior]" label="Número Externo" clases="campoFacturacion"
                                    valor="{{ old('datosFacturacion[numero_exterior]') }}" />
                                <x-inputFormulario tipo="number" identificador="numero_interiorFacturacion" nombre="datosFacturacion[numero_interior]" label="Número Interno" clases="campoFacturacion"
                                    valor="{{ old('datosFacturacion[numero_interior]') }}" />
                                <div class="col"></div>
                                <div class="col">
                                    <label class="form-label mt-3">Colonia</label>
                                    <select class="form-select selectToo campoFacturacion" id="coloniasFacturacion" name="datosFacturacion[colonia]" style="width: 100%">
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaColonias as $colonia)
                                            <option value="{{$colonia->id}}">{{$colonia->nombre}}</option>
                                        @endforeach
                                    </select>
                                    @error('datosFacturacion[colonia]')
                                        <div id="coloniaError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <x-inputFormulario tipo="number" identificador="codigoPostalFacturacion" nombre="datosFacturacion[codigoPostal]" label="Código Postal"
                                    valor="{{ old('codigoPostalFacturacion') }}" />
                                <x-inputFormulario tipo="text" identificador="localidadFacturacion" nombre="datosFacturacion[localidad]" label="Ciudad o localidad"
                                    valor="{{ old('localidadFacturacion') }}" />
                                <div class="col">
                                    <label class="form-label mt-3">Municipio o Delegación</label>
                                    <select class="form-select selectToo" id="municipiosFacturacion" name="datosFacturacion[municipio]" style="width: 100%">
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaMunicipios as $municipio)
                                            <option value="{{$municipio->id}}">{{$municipio->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label mt-3">Entidad Federativa</label>
                                    <select class="form-select selectToo" id="entidadFederativasFacturacion" name="datosFacturacion[entidadFederativa]" style="width: 100%">
                                        <option value="0">SIN DATO</option>
                                        @foreach ($listaEstados as $estado)
                                            <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <x-inputFormulario tipo="text" identificador="paisFacturacion" nombre="datosFacturacion[numero_interior]" label="País"
                                    valor="MÉXICO" />
                                <div class="col" style="width:100%">
                                    <label class="form-label mt-3">Referencias</label>
                                    <textarea name="datosFacturacion[referencia]" id="referenciaFacturacion" cols="30" rows="7" class="form-control campoFacturacion">
                                        {{old('datosFacturacion[referencia]')}}
                                    </textarea>
                                    @error('datosFacturacion[referencia]')
                                    <div id="codigoPostalError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane container pt-3 fade" id="otrosDatos">
                        {{-- CONTENEDOR OTROS DATOS --}}
                        <div id="otrosDatos" class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                            <h3>Otros datos</h3>
                            <label class="form-label mt-3">Etiquetas</label>
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
                                    <label class="form-label mt-3">Observaciones</label>
                                    <div class="form-group">
                                        <textarea class="form-control" rows="5" id="comment" name="datosOtrosDatos[observaciones]"></textarea>
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
                    <div class="d-flex justify-content-between col-2">
                        <button id="BotonAgregarPersona" class="btn btn-primary" hidden></button>
                        <div>
                            <a href="{{route('contactos.index')}}" class="btn btn-secondary">Regresar</a>
                        </div>
                        <a id="BotonValidador" onclick="validar()" class="btn btn-primary" > {{ (explode('/', url()->current()) [count(explode('/', url()->current())) - 1] == 'agregar') ? 'Agregar Persona' : 'Modificar Persona' }} </a>
                    </div>
                    <!-- <button class="btn btn-danger" type="button" class="cerrarFormulario">Limpiar</button> -->
                </center>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="modalGuardarColonia" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Colonia</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <label class="form-label mt-3">Nombre de la Colonia</label>
                    <input type="text" class="form-control" id="nombreColonia" name="nombreColonia">
                    <label class="form-label mt-3">Ciudad</label>
                    <input type="text" class="form-control" id="seleccionarCiudad" name="seleccionarCiudad">
                    <label class="form-label mt-3">Estado</label>
                    <input type="text" class="form-control" id="seleciconarEstado" name="seleciconarEstado">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDg60SDcmNRPnG1tzZNBBGFx02cW2VkWWQ&callback=initMap&v=weekly" defer></script> --}}
<script async
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_SEN6wP2RzPdhZKjFPAW6M-iNIdBtnHQ&callback=initMap">
</script>
<script src="{{url('/')}}/js/validacionesFormulario.js" text="text/javascript"></script>
<script text="text/javascript">
    var marker;
    var marker2;
    var currentUrl = window.location.href;
    var contadorTelefono = 1;
    var contadorCorreo = 1;
    var contadorRelacion = 1;
    const myLatLng = { lat: 24.123954, lng: - 110.311664 };
    var listaEmpresas = @json($listaEmpresas);
    var map;
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

        google.maps.event.addListener(map, 'click', function (event) {
            placeMarker(event.latLng);
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


                document.getElementById("coordenadas").value = latitud + "," + longitud;
                console.log(latitud, longitud);
                placeMarker({lat: latitud, lng: longitud});
            }
        })
        .catch(error => {
            console.error('Error al buscar la ubicación:', error);
        });
    }
    //FIN MAPA
    $('#rolEstructura').change(function (e) {
        $('#rolNumero').prop('disabled', false);
        $('#rolNumero').empty();
        $('#rolNumero').append(new Option('SIN DATO', 0, false, false));
        let listaEscogida;
        switch ($(this).val()) {
            case 'COORDINADOR ESTATAL':
                $('#rolNumeroEncabezado').text('¿En qué Entidad?');
                listaEscogida = @json($listaEstados);
                cargarSelect2(listaEscogida, "#rolNumero");
                break;
                case 'COORDINADOR DE DISTRITO LOCAL':
                $('#rolNumeroEncabezado').text('¿En qué Distrito?');
                listaEscogida = @json($listaDistritoLocal);
                cargarSelect2(listaEscogida, "#rolNumero");
                break;
                case 'COORDINADOR DE SECCIÓN':
                $('#rolNumeroEncabezado').text('¿En qué Sección?');
                listaEscogida = @json($listaSecciones);
                cargarSelect2(listaEscogida, "#rolNumero");
                break;
                case 'PROMOTOR':
                $('#rolNumeroEncabezado').text('¿En qué Sección?');
                listaEscogida = @json($listaSecciones);
                cargarSelect2(listaEscogida, "#rolNumero");
                break;
            default:
                $('#rolNumeroEncabezado').text('Seleccione un rol en estructura');
                $('#rolNumero').prop('disabled', true);
                cargarSelect2([], "#rolNumero");
                break;
        }
    });

    $('#btnAbrirModalCrearColonia').click(function (e){
        $('#modalGuardarColonia').modal('show');
    });

    function cargarSelect2(lista, select2){
        lista.forEach(option => {
            var newOption;
            if(option.nombre != null){
                newOption = new Option(option.nombre, option.id, false, false);
            }
            else{
                newOption = new Option(option.id, option.id, false, false);
            }
            $(select2).append(newOption);
        });
        $(select2).trigger('change');
    }
    function filtrarColonia(e){
        let colonia;
        if(e.data.Facturacion){
            colonia = $('#coloniasFacturacion').val();
        }
        else{
            colonia = $('#colonias').val();
        }
        $.when(
        $.ajax({
                type: "get",
                url: `{{url('/')}}/simpatizantes/filtrarColonias-${colonia}`,
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    if(e.data.Facturacion){
                        $('#codigoPostalFacturacion').val(response.codigoPostal);
                        $('#municipiosFacturacion').val(response.municipio);
                        $('#municipiosFacturacion').trigger('change');
                        $('#entidadFederativasFacturacion').val(response.idEntidad);
                        $('#entidadFederativasFacturacion').trigger('change');
                    }
                    else{
                        $('#codigoPostal').val(response.codigoPostal);
                        $('#municipios').val(response.municipio);
                        $('#municipios').trigger('change');
                        $('#entidadFederativas').val(response.idEntidad);
                        $('#entidadFederativas').trigger('change');
                    }
                    // $('#codigoPostal').val(response.codigoPostal);
                    // $('#codigoPostal').val(response.codigoPostal);
                    //buscarUbicacion(`${response.nombreColonia}, ${response.codigoPostal}, ${response.nombreMunicipio}, B.C.S, México`);
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
    $('#colonias').on('change', { Facturacion: false}, filtrarColonia);
    $('#coloniasFacturacion').on('change', { Facturacion: true}, filtrarColonia);
    function filtrarSeccion(e){
        let seccion = $(this).val();
        $.when(
        $.ajax({
                type: "get",
                url: `{{url('/')}}/simpatizantes/filtrarSecciones-${seccion}`,
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    console.log(response);
                    $('#municipiosUbicacion').val(response.municipio);
                    $("#municipiosUbicacion").trigger('change');
                    $("#distritoLocals").val(response.distritoLocal);
                    $("#distritoLocals").trigger('change');
                    $("#distritoFederals").val(response.distritoFederal);
                    $("#distritoFederals").trigger('change');
                    $("#entidadFederativaUbicacions").val(response.entidad);
                    $("#entidadFederativaUbicacions").trigger('change');
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
    $('#secciones').change( filtrarSeccion );

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
    $(document).ready(function () {
        @if (str_contains(url()->current(), 'agregar'))
            $("#btnAgregarTelefono").trigger('click');
            $("#btnAgregarCorreo").trigger('click');
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
        e.preventDefault();
        let apodo = $('#apodo').val();
        if(apodo.trim().length == 0){
            Swal.close();
            Swal.fire({
                'title':"Error",
                'text':"El campo apodo es obligatorio.",
                'icon':"error"
            });
            return false;
        }
        if($('#inputEtiquetaCrear').is(':focus')){
            return false;
        }
        if($('#formularioAgregarSimpatizante #etiquetas').length == 0){
            let etiquetas = "";
            $.each(tags, function (i, value) {
                etiquetas += `${value.childNodes[0].innerHTML},`;
                if(etiquetas.length > 0 && tags.length - 1 == i){
                    etiquetas = etiquetas.slice(0, -1);
                }
            });
            $('#formularioAgregarSimpatizante').append(
                $('<input>').attr('name', 'datosOtrosDatos[etiquetas]').attr('id', 'etiquetas').attr('type', 'hidden')
                .val(etiquetas)
            );
        }
        var datosFormulario = $('#formularioAgregarSimpatizante').serializeArray();
        var rutas = $('#formularioAgregarSimpatizante').attr('action');

        $.when(
            $.ajax({
                type: "post",
                url: rutas,
                data: datosFormulario,
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    if(response.icono == "success"){
                        Swal.fire({
                            'title': response.titulo,
                            'text': response.texto,
                            'icon': response.icono,
                            showCancelButton: true,
                            confirmButtonText: 'Sí',
                            cancelButtonText: 'No',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if(result.isConfirmed){
                                if (currentUrl.includes('agregar')) {
                                    window.location.href = '{{url("/")}}/contactos/modificar-' + response.id;
                                }
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                window.location.href = '{{route("contactos.index")}}';
                            }
                        });
                        Swal.getPopup().addEventListener('click', (e) => {
                            if (e.target === Swal.getPopup()) {
                                if (currentUrl.includes('agregar')) {
                                    window.location.href = '{{route("contactos.index")}}';
                                }
                            }
                        });
                    }
                    else{
                        Swal.fire({
                            'title': response.titulo,
                            'text': response.texto,
                            'icon': response.icono,
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
        ).then(
            function( data, textStatus, jqXHR ) {
                // Swal.close();
        });



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
    $('#btnAgregarTelefono').click(function(e) {
        nuevoRenglon(`datosContacto[telefonos][${contadorTelefono}][telefono]`, "6120000000", '#contenedorTelefono', 'Telefono', 'telefonos', contadorTelefono);
        contadorTelefono++;
    });
    $('#btnAgregarCorreo').click(function(e) {
        nuevoRenglon(`datosContacto[correos][${contadorCorreo}][correo]`, "example@mail.com", "#contenedorCorreo", 'Correo', 'correos', contadorCorreo);
        contadorCorreo++;
    });
    $('#btnAgregarRelacion').click(function(){
        nuevoRenglonEmpresa();
        contadorRelacion++;
    });
    $('[name="reutilizarDomicilio"]').change(function () {
        if($("#reutilizarDomicilioSi").is(":checked")){
            $('.campoFacturacion').prop('disabled', true);
        }
        else{
            $('.campoFacturacion').prop('disabled', false);
        }
    });
    $('#origen').change(function (e) {
        var valor = $(this).val();
        if(valor == 0){
            $('#identificadorOrigen').prop('disabled', true);
            $('#identificadorOrigen').val('');
        }
        else{
            $('#identificadorOrigen').prop('disabled', false);
        }
    });

    function nuevoRenglon(nombre, placeholder, contenedor, label, conjuntoDatos, contador){
        var nuevoRenglon = $('<div class="row mx-0 mb-2">').append(
            $('<div class="col-sm-3">').append(
                $('<label class="form-label">').text(label),
                $('<input type="text">').attr({name: nombre, class:"form-control", placeholder:placeholder}),
            ),
            $('<div class="col-sm-3">').append(
                $('<label class="form-label">').text('Descripción'),
                $('<input type="text">').attr({name: `datosContacto[${conjuntoDatos}][${contador}][descripcion]`, class:"form-control", placeholder: 'Etiqueta'}),
            ),
            $('<div class="col-sm-2">').append(
                $('<button type="button">').addClass('btn btn-danger borrarInput').text('Borrar').on('click', borrarRenglon)
            )
        );
        $(contenedor).append(nuevoRenglon);
    }
    function nuevoRenglonEmpresa(){
        var nuevoRenglon = $('<div class="row mx-0 mb-2">').append(
            $('<div class="col-sm-8">').append(
                $('<div class="row row-cols-2">').append(
                    $('<div class="col-5">').append(
                        $('<label class="form-label">').text('Empresa'),
                        $(`<select name="datosRelacionEmpresa[${contadorRelacion}][empresa_id]">`).addClass("form-select selectToo selectNombresEmpresas").css("width", "100%")
                        .append(
                            $('<option>').val(0).text('SIN DATO'),
                        )
                    ),
                    $('<div class="col-sm-5">').append(
                        $('<label class="form-label">').text('Cargo'),
                        $('<input type="text">').attr({name: `datosRelacionEmpresa[${contadorRelacion}][cargo]`, class:"form-control", placeholder: ""})
                    )
                )
            ),
            $('<div class="col-4">').append(
                $('<button type="button">').addClass('btn btn-danger borrarInput').text('Borrar').on('click', borrarRenglon)
            )
        );
        $("#contenedorRelaciones").append(nuevoRenglon);

        listaEmpresas.forEach(element => {
            $(`[name="datosRelacionEmpresa[${contadorRelacion}][empresa_id]"]`).append($(`<option value="${element.id}">`).text(element.nombreEmpresa));
        });
    }

    function borrarRenglon(){
        $(this).parent().parent().remove();
    }

    @if (str_contains(url()->current(), 'modificar') && !session()->has('mensajeError'))
        var tabTrigger = new bootstrap.Tab($('[href="#{{$conjunto}}"]'));
        if("{{$conjunto}}" != "")
            tabTrigger.show();
        let datosFormulario = @json($persona);
        let telefonos =  @json($telefonos);
        let correos =  @json($correos);
        let identificacion =  @json($identificacion);
        let relacionDomicilio =  @json($relacionDomicilio);
        let relacionesEmpresas = @json($relacionesEmpresa);
        cargarFormulario();
        function cargarFormulario(){
            //$("#fecha_registro").val(datosFormulario.fecha_registro);
            $("#folio").val(datosFormulario.folio);
            $("#promotores").val(datosFormulario.promotor_id ?? 0);
            $("#promotores").trigger('change');
            $("#origen").val(datosFormulario.origen ?? 0);
            $("#origen").trigger('change');
            $("#estatus").val(datosFormulario.estatus ?? 0);
            $("#estatus").trigger('change');
            $("#referenciaOrigen").val(datosFormulario.referenciaOrigen ?? 0);
            $("#referenciaOrigen").trigger('change');
            //$("#referenciaCampania").val();
            $("#etiquetasOrigen").val(datosFormulario.etiquetasOrigen);
            $("#apodo").val(datosFormulario.apodo);
            $("#nombres").val(datosFormulario.nombres);
            $('#identificadorOrigen').val(datosFormulario.identificadorOrigen);
            $("#apellido_paterno").val(datosFormulario.apellido_paterno);
            $("#apellido_materno").val(datosFormulario.apellido_materno);
            $("#genero").val(datosFormulario.genero);
            $("#fecha_nacimiento").val(datosFormulario.fecha_nacimiento);
            $("#rangoEdad").val(datosFormulario.rangoEdad);
            $("#nombre_en_facebook").val(datosFormulario.nombre_en_facebook);
            $("#instagram").val(datosFormulario.instagram);
            $("#twitter").val(datosFormulario.twitter);

            $("#calle1").val(relacionDomicilio[0].domicilio.calle1);
            $("#calle2").val(relacionDomicilio[0].domicilio.calle2);
            $("#calle3").val(relacionDomicilio[0].domicilio.calle3);
            $("#numero_exterior").val(relacionDomicilio[0].domicilio.numero_exterior);
            $("#numero_interior").val(relacionDomicilio[0].domicilio.numero_interior);
            $("#colonias").val(relacionDomicilio[0].domicilio.colonia_id ?? 0);
            $("#colonias").trigger('change');
            $("#referencia").val(relacionDomicilio[0].domicilio.referencia);
            setTimeout(function () {
                if(relacionDomicilio[0].domicilio.latitud != null){
                    $("#coordenadas").val(relacionDomicilio[0].domicilio.latitud + ',' + relacionDomicilio[0].domicilio.longitud);
                    console.log({lat: relacionDomicilio[0].domicilio.latitud, lng: relacionDomicilio[0].domicilio.longitud});
                    placeMarker({lat: relacionDomicilio[0].domicilio.latitud, lng: relacionDomicilio[0].domicilio.longitud});
                }
            }, 2000);

            $("#curp").val(identificacion.curp);
            $("#rfc").val(identificacion.rfc);
            $("#lugarNacimiento").val(identificacion.lugarNacimiento ?? 0);
            $("#lugarNacimiento").trigger('change');
            $("#clave_elector").val(identificacion.clave_elector);
            $("#secciones").val(identificacion.seccion_id ?? 0);
            $("#secciones").trigger('change');
            ///trigger
            $('[name="datosRelaciones[esAfiliado]"').val(datosFormulario.afiliado ?? 0);
            $('[name="datosRelaciones[esAfiliado]"').trigger('change');
            $('[name="datosRelaciones[esSimpatizante]"').val(datosFormulario.simpatizante ?? 0);
            $('[name="datosRelaciones[esSimpatizante]"').trigger('change');
            $('[name="datosRelaciones[programa]"').val(datosFormulario.programa ?? 0);
            $('[name="datosRelaciones[programa]"').trigger('change');
            $("#clientes").val(datosFormulario.cliente ?? 0);
            $("#clientes").trigger('change');
            $("#promotorEstructura").val(datosFormulario.promotor ?? 0);
            $("#promotorEstructura").trigger('change');
            $("#colaborador").val(datosFormulario.colaborador ?? 0);
            $("#colaborador").trigger('change');
            $("#rolEstructura").val(datosFormulario.rolEstructura);
            $('#rolEstructura').trigger('change');
            console.log(datosFormulario);

            $("#rolNumero").val(datosFormulario.coordinadorDe.split(',') ?? []);
            if(datosFormulario.coordinadorDe > 0)
                $("#rolNumero").prop('disabled', false);
            $("#funcionAsignada").val(datosFormulario.funcionAsignada);
            $("#reutilizarDomicilioNo").prop('checked', true);
            $("#rfcFacturacion").val(relacionDomicilio[1].domicilio.rfc);
            $("#calle1Facturacion").val(relacionDomicilio[1].domicilio.calle1);
            $("#calle2Facturacion").val(relacionDomicilio[1].domicilio.calle2);
            $("#calle3Facturacion").val(relacionDomicilio[1].domicilio.calle3);
            $("#numero_exteriorFacturacion").val(relacionDomicilio[1].domicilio.numero_exterior);
            $("#numero_interiorFacturacion").val(relacionDomicilio[1].domicilio.numero_interior);
            $("#coloniasFacturacion").val(relacionDomicilio[1].domicilio.colonia_id ?? 0);
            $("#coloniasFacturacion").trigger('change');
            $("#referenciaFacturacion").val(relacionDomicilio[1].domicilio.referencia);
            $("#comment").val(datosFormulario.observaciones);
            correos.forEach(correo => {
                nuevoRenglon(`datosContacto[correos][${contadorCorreo}][correo]`, "example@mail.com", "#contenedorCorreo", 'Correo', 'correos', contadorCorreo);
                $(`[name="datosContacto[correos][${contadorCorreo}][correo]"]`).val(correo.correo);
                $(`[name="datosContacto[correos][${contadorCorreo}][descripcion]"]`).val(correo.etiqueta);
                contadorCorreo++;
            });
            telefonos.forEach(telefono => {
                nuevoRenglon(`datosContacto[telefonos][${contadorTelefono}][telefono]`, "6120000000", '#contenedorTelefono', 'Telefono', 'telefonos', contadorTelefono);
                $(`[name="datosContacto[telefonos][${contadorTelefono}][telefono]"]`).val(telefono.telefono);
                $(`[name="datosContacto[telefonos][${contadorTelefono}][descripcion]"]`).val(telefono.etiqueta);
                contadorTelefono++;
            });

            let etiquedasPreprocesar = ("{{$persona->etiquetas}}" != null) ? "{{$persona->etiquetas}}".split(',') : [];
            $.each(etiquedasPreprocesar, function (i, valor) {
                createTag(valor);
            });

            relacionesEmpresas.forEach(relacion => {
                nuevoRenglonEmpresa();
                $(`[name="datosRelacionEmpresa[${contadorRelacion}][empresa_id]"]`).val(relacion.empresa_id);
                $(`[name="datosRelacionEmpresa[${contadorRelacion}][cargo]"]`).val(relacion.puesto);
                contadorRelacion++;
            });
            //casos especiales
            // <input type="hidden" id="coordenadas" name="coordenadas"
            // <div class="mt-3 contenedorEtiquetasCrear"
        }

    @endif
</script>
@endsection
