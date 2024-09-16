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
</style>
<div class="card" class="m-3">
    <div class="card-header d-flex justify-content-between">
        <h3>Gestionar Grupos de: </h3>
        {{-- <div>
            <button id="botonCrearGrupo" class="btn btn-primary">
                Crear grupo
            </button>
        </div> --}}
    </div>
    <div class="col-11 mx-auto card-body">
        <div class="mx-auto">
            <form class="row g-3 needs-validation" novalidate>
                <div class="col-md-4 position-relative">
                    <x-inputFormulario :identificador="'validationTooltip01'" :label="'First name'" :mensajeError="'Please choose a name.'" :requerido="true" />
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Submit form</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection



@section('scripts')
    <script text="text/javascript">
        (function () {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.classList.add('was-validated')
            }, false)
        })
        })()
    </script>
@endsection

