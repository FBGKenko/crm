@extends('Pages.plantilla')

@section('tittle')
    Agregar empresa
@endsection

@section('cuerpo')
<style>
    .eliminarOpcionSeleccionada, .borrarGrupoAsignado{
        cursor: pointer;
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
    .imgLogotipoEmpresa{
        margin-left: 0.3rem;
        width: 350px;
        height: auto;
        border: 1px solid darkslategrey;
        border-radius: 5px;
    }
</style>
<div class="card" class="m-3">
    <div class="card-header d-flex justify-content-between">
        <h3>Personalizar Sistema</h3>
    </div>
    <div class="col-11 mx-auto card-body">
        <form action="{{route('personalizar.cambiar')}}" method="post" enctype="multipart/form-data">
                @csrf
            <div class="row row-cols-1 row-cols-sm-2">
                <div class="col d-flex justify-content-center">
                    <div>
                        <label class="form-label mt-3">Logotipo Actual</label>
                        <br>
                        <img src="{{$rutaLogo}}" alt="" class="imgLogotipoEmpresa">
                    </div>
                </div>
                <div class="col d-flex justify-content-center">
                    <div>
                        <label class="form-label mt-3">Fondo Inicio de sesión</label>
                        <br>
                        <img src="{{$rutaFondo}}" alt="" class="imgLogotipoEmpresa">
                    </div>
                </div>
                <div class="col">
                    <label class="form-label mt-3">Imagen del Proyecto</label>
                    <input type="file" id="imagenesProyecto" name="imagenesProyecto" class="form-control" accept="image/*">
                </div>
                <div class="col">
                    <label class="form-label mt-3">Fondo de Iniciar Sesión</label>
                    <input type="file" id="fondoInicio" name="fondoInicio" class="form-control" accept="image/*">
                </div>
                <div class="col">
                    <label class="form-label mt-3">Nombre Empresa Propietaria</label>
                    <input type="text" id="nombreEmpresaPropietaria" name="nombreEmpresaPropietaria" class="form-control" value="{{ $nombreEmpresa }}">
                </div>
                <div class="col d-flex justify-content-center align-items-center">
                    <div class="align-self-end">
                        <button id="botonGuardarPersonalizacion" class="btn btn-primary">
                            Guardar cambios
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection



@section('scripts')
    <script text="text/javascript">

    </script>
@endsection

