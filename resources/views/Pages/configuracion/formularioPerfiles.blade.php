@extends('Pages.plantilla')

@section('tittle')
    Agregar empresa
@endsection

@section('cuerpo')
<style>
    .eliminarOpcionSeleccionada, .borrarGrupoAsignado{
        cursor: pointer;
    }
    h4{
        font-weight: 400;
    }

    label.form-label{
        font-weight: 700;
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
<div class="modal fade" id="modalCrearGrupo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Crear/Modificar grupos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="border rounded p-3">
                    <form id="formularioPerfil" action="" method="post">
                        @csrf
                        <div class="d-flex justify-content-between">
                            <div class="col-5">
                                <label class="form-label">Seleccione un grupo existente:</label>
                                <div class="d-flex justify-content-between">
                                    <select id="perfilesCreados" name="perfilesCreados" class="select2Modal" style="width: 50%">
                                        <option value="0">Crear nuevo grupo</option>
                                        @foreach ($listaPerfil as $perfil)
                                            <option value="{{$perfil->perfil_id}}">{{$perfil->nombre}}</option>
                                        @endforeach
                                    </select>
                                    <div class="d-flex">
                                        <button type="button" id="" class="btn btn-primary">
                                            Renombrar
                                        </button>
                                        <button type="button" id="btnGuardarPerfil" class="btn btn-success">
                                            Guardar
                                        </button>

                                    </div>
                                </div>
                                <label class="form-label mt-3">Nombre del grupo:</label>
                                <input type="text" id="nombreGrupo" name="nombreGrupo" class="form-control">
                                <div class="row justify-content-between mt-3">
                                    <div class="col-5">
                                        <label class="form-label">Seleccione una lista:</label>
                                        <br>
                                        <select id="listaDeListas" class="select2Modal w-100">
                                            <option value="0">Seleccione una lista</option>
                                            @foreach ($listaDeListas as $lista)
                                                <option value="{{$lista->tipoLista}}">{{$lista->lista}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <label id="labelListaSeleccionada" class="form-label">Seleccione una opción:</label>
                                        <br>
                                        <select id="agregarOpcion" class="select2Modal w-100">
                                            <option value="0">seleccione una opción</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="fw-bold">Contactos y empresas relacionadas a el nuevo grupo:</h4>
                                <div id="contenedorItemsCompartir"  class="border rounded bg-secondary bg-opacity-10 p-3 h-75">
                                    <label id="labelNoSeleccionadas" class="form-label text-secondary">No hay opciones seleccionadas</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>
<br>
<div class="card" class="m-3">
    <div class="card-header d-flex justify-content-between">
        <h3>Gestionar Grupos de: {{$usuario->nombre}} {{$usuario->apellido_paterno}} {{$usuario->apellido_materno}}</h3>
        {{-- <div>
            <button id="botonCrearGrupo" class="btn btn-primary">
                Crear grupo
            </button>
        </div> --}}
    </div>
    <div class="col-11 mx-auto card-body">
        <button id="botonCrearGrupo" class="btn btn-primary">
            Crear/Modificar grupos
        </button>
        <br>
        <div class="mx-auto d-flex justify-content-between">
            <div class="col-5">
                <form action="{{route('perfil.relacionarGruposConUsuarios')}}" method="post">
                     @csrf
                    <h4 class="mt-4 fw-bold">Asignar Grupos</h4>
                    <div class="border rounded p-3">
                        <label class="form-label">Usuarios seleccionados:</label>
                        <select name="selectUsuarioSeleccionados[]" id="selectUsuarioSeleccionados" multiple class="w-100">
                            @foreach ($listaUsuarios as $usuario)
                                <option value="{{$usuario->id}}">{{$usuario->nombre}} {{$usuario->apellido_paterno}} {{$usuario->apellido_materno}}</option>
                            @endforeach
                        </select>
                        <label class="form-label">Grupo seleccionados:</label>
                        <select name="selectGruposSeleccionados[]" id="selectGruposSeleccionados" multiple class="w-100">
                            @foreach ($listaPerfil as $perfil)
                                <option value="{{$perfil->perfil_id}}">{{$perfil->nombre}}</option>
                            @endforeach
                        </select>
                        <button id="botonGuardarRelacion" class="btn btn-success mt-2">
                            Guardar Relacion
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-6">
                <h4 class="mt-4 fw-bold">Grupos asignados</h4>
                <div id="contenedorGruposCreados" class="border rounded bg-secondary bg-opacity-10 p-3">
                    @if (count($gruposRelacionados) <= 0)
                        <label class="form-label text-secondary">No hay grupos relacionados con este usuario</label>
                    @endif
                    @foreach ($gruposRelacionados as $perfil)
                        <span class="p-1 rounded bg-primary text-white fw-bolder me-2">
                            <span class="borrarGrupoAsignado">
                                X
                            </span>
                            <input type="hidden" name="grupoRelacionado_{{$perfil->perfil_id}}" value="{{$perfil->perfil_id}}">
                            {{$perfil->nombre}}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@section('scripts')
    <script text="text/javascript">
        var listaDatos = @json($listas);
        var contadorContactos = 0;

        $(document).ready(function () {
            $('#listaDeListas').change(cargarLista);
            $('#agregarOpcion').change(agregarOpcionAGrupo);
            $('#btnGuardarPerfil').click(guardarGrupoActual);
            $('#perfilesCreados').change(cargarGrupo);
            $('#botonCrearGrupo').click(abrirModalCrearGrupo);
            $('.borrarGrupoAsignado').click(borrarRelacion);

            $('#selectUsuarioSeleccionados').select2({
                placeholder: "Seleccione uno o varios usuarios",
            });
            $('#selectGruposSeleccionados').select2({
                placeholder: "Seleccione uno o varios grupos",
            });
            $('.select2Modal').select2({
                dropdownParent: $('#modalCrearGrupo')
            });
        });



        function abrirModalCrearGrupo(e){
            limpiarFormulario();
            $('#modalCrearGrupo').modal('show');
        }

        function cargarGrupo(e){
            idGrupo = $(this).val();
            if(idGrupo > 0){
                peticionAjax(
                    '{{route("perfil.buscarRelaciones")}}',
                    cargarSuccess,
                    'get',
                    {
                        idGrupo: idGrupo
                    }
                )
            }
            else{
                $('#contenedorItemsCompartir').html('');
                cambiarLabelOpcionesSeleccionadas('#contenedorItemsCompartir', 'labelNoSeleccionadas', 'No hay opciones seleccionadas');
            }
        }

        function cargarSuccess(response){
            $('#contenedorItemsCompartir').html('');
            $('#nombreGrupo').val(response[0].nombre);
            for (let i = 0; i < response[0].perfiles_modelos_relacionados.length; i++) {
                var datosModeloAsociado = response[0].perfiles_modelos_relacionados[i];
                var nombresModelo = response[1][i];
                agregarOpcionEtiqueta(datosModeloAsociado.idAsociado, datosModeloAsociado.modelo, nombresModelo) //FALTA VER COMO OBTENER LOS NOMBRES DE LOS MODELOS RELACIONADOS
            }
            cambiarLabelOpcionesSeleccionadas('#contenedorItemsCompartir', 'labelNoSeleccionadas', 'No hay opciones seleccionadas');
        }

        function borrarRelacion(e){
            var idPerfil = $(this).next().val();
            peticionAjax(
                '{{route("perfil.borrarRelacion", $usuario->id)}}',
                exitoRelacionBorrada,
                'POST',
                {
                    _token: "{{csrf_token()}}",
                    idPerfil: idPerfil
                }
            )
        }

        function exitoRelacionBorrada(response){
            swal.fire({
                title: response.titulo,
                text: response.mensaje,
                icon: response.tipo
            });
            if(response.respuesta){
                $(`input[name^="grupoRelacionado_${response.idPerfilIngresado}"]`).parent().remove();
                cambiarLabelOpcionesSeleccionadas('#contenedorGruposCreados', 'labelNoGruposAsignados', 'No hay grupos relacionados con este usuario');
            }
        }

        function cargarLista(e){
            var index = (document.getElementById("listaDeListas").selectedIndex) - 1;
            $('#agregarOpcion').html('');
            $('#agregarOpcion').append(
                $('<option>').val(0).text('seleccione una opción'),
            );
            if(index >= 0){
                listaDatos[index].forEach(element => {
                    $('#agregarOpcion').append(
                        $('<option>').val(element.id).text(element.nombre),
                    )
                });
            }
        }

        function agregarOpcionAGrupo(e){
            var index = (document.getElementById("agregarOpcion").selectedIndex) - 1;
            var claseLista = $('#listaDeListas').val();
            var listaOrigenTexto = `. ${claseLista.split("\\")[claseLista.split("\\").length - 1]}`;
            valorEscogido = [
                $('#agregarOpcion option:selected').text() + listaOrigenTexto,
                $(this).val(),
            ];
            if(index >= 0){
                agregarOpcionEtiqueta(valorEscogido[1], claseLista, valorEscogido[0])
                cambiarLabelOpcionesSeleccionadas('#contenedorItemsCompartir', 'labelNoSeleccionadas', 'No hay opciones seleccionadas');
            }
        }

        function agregarOpcionEtiqueta(idModelo, claseLista, nombreModelo){
            contadorContactos += 1;
            $('#contenedorItemsCompartir').append(
                $('<div>').append(
                    $('<input type="hidden">').attr('name', `contactos[${contadorContactos}][idModelo]`).val(idModelo),
                    $('<input type="hidden">').attr('name', `contactos[${contadorContactos}][claseLista]`).val(claseLista),
                    $('<span class="eliminarOpcionSeleccionada">').text('X '),
                    $('<label class="form-label">').text(nombreModelo),
                )
            );
            cambiarValorSelect2('#agregarOpcion');
            $('.eliminarOpcionSeleccionada').click(eliminarOpcionGuardada);
        }

        function eliminarOpcionGuardada(e){
            $(this).parent().remove();
            cambiarLabelOpcionesSeleccionadas('#contenedorItemsCompartir', 'labelNoSeleccionadas', 'No hay opciones seleccionadas');
        }

        function cambiarLabelOpcionesSeleccionadas(contenedor, selector, mensaje){
            var labelEncontrado = $('#' + selector);
            if(labelEncontrado.length > 0){
                $(contenedor+' #'+selector).remove();
            }
            else{
                var etiquetaCreada = $(`<label id="${selector}">`).addClass('form-label text-secondary').text(mensaje);
                if($(contenedor).children().length <= 0){
                    $(contenedor).append(etiquetaCreada);
                }
            }
        }

        function guardarGrupoActual(e){
            var datos = $('#formularioPerfil').serializeArray();
            peticionAjax(
                '{{route("perfil.manejarPerfil", $usuario->id)}}',
                exitoGuardarPerfil,
                'POST',
                datos
            )
        }

        function exitoGuardarPerfil(response){
            swal.fire({
                title: response.titulo,
                text: response.mensaje,
                icon: response.tipo
            });
            if(response.respuesta){
                if(response.idPerfilRequest == 0){
                    var nuevoPerfil = new Option(response.nombrePerfil, response.idPerfil, false, false);
                    $('#selectGruposSeleccionados').append(nuevoPerfil).trigger('change');
                    var nuevoPerfil2 = new Option(response.nombrePerfil, response.idPerfil, false, false);
                    cambiarValorSelect2('#perfilesCreados');
                    $('#perfilesCreados').append(nuevoPerfil2);
                    $('#perfilesCreados').trigger('change');
                }
                else{
                    // $(`#perfilesCreados option[value="${response.idPerfil}"]`).text(response.nombrePerfil);
                    // $(`#perfilesCreados option[value="${response.idPerfil}"]`).data(response.nombrePerfil);
                    // cambiarValorSelect2('#perfilesCreados');
                    // $(`#selectGruposSeleccionados option[value="${response.idPerfil}"]`).text(response.nombrePerfil);
                    // $(`#selectGruposSeleccionados option[value="${response.idPerfil}"]`).data(response.nombrePerfil);
                    // cambiarValorSelect2('#selectGruposSeleccionados');
                }
                limpiarFormulario();
            }
        }

        function etiquetaGrupo(nombre, idPerfil){
            var etiqueta = $('<span>').addClass('p-1 rounded bg-primary text-white fw-bolder me-2').append(
                $('<apan>').addClass('borrarGrupoAsignado').text('X '),
                $('<input type="hidden">').attr('name', 'idPerfil').val(idPerfil),
                nombre
            )
            $('#contenedorGruposCreados').append(etiqueta);
            cambiarLabelOpcionesSeleccionadas('#contenedorGruposCreados', 'labelNoGruposAsignados', 'No hay grupos relacionados con este usuario');
        }

        function limpiarFormulario(){
            $('#nombreGrupo').val('');
            cambiarValorSelect2('#perfilesCreados');
            cambiarValorSelect2('#listaDeListas');
            cambiarValorSelect2('#agregarOpcion');
            $('#contenedorItemsCompartir').html('');
            cambiarLabelOpcionesSeleccionadas('#contenedorItemsCompartir', 'labelNoSeleccionadas', 'No hay opciones seleccionadas');
        }
    </script>
@endsection

