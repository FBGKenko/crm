@extends('Pages.plantilla')

@section('tittle')
    Catalogos
@endsection

@section('cuerpo')
<div class="card" class="m-3">
    <div class="card-header d-flex justify-content-between">
        <h3>Catalogos</h3>
    </div>
    <div class="col-11 mx-auto card-body">
        <div class="row row-cols-1 mb-3">
            <label class="form-label">Catalogos disponibles:</label>
            <select id="selectorCatalogos" class="form-control select2">
                <option value="0">SIN DATO</option>
                <option>Secciones</option>
                <option>Distritos Locales</option>
                <option>Municipios</option>
                <option>Distritos Federales</option>
                <option>Entidades</option>
                <option>Colonias</option>
                <option>Estatus</option>
                <option>Tipo Funciones Personalizadas</option>
                <option>Origenes</option>
                <option>Estados</option>
            </select>
        </div>
        <div class="row rows-col-1">
            <div class="col-12">
                <div class="card" class="m-3">
                    <div class="card-header d-flex justify-content-between">
                        <div class="d-flex">
                            <h4>Catalogo x</h4>
                            <button class="btn btn-primary ms-3">Agregar</button>
                        </div>
                    </div>
                    <div class="col-11 mx-auto card-body">
                        <div class="row row-cols-3">
                            <div class="col">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="nombreOpcion">
                                    <label for="nombreOpcion">Input</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="valorOpcion">
                                    <label for="valorOpcion">value</label>
                                </div>
                            </div>
                            <div class="col">
                                <button class="btn btn-danger">Borrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@section('scripts')
    <script text="text/javascript">
        $(document).ready(function () {
            $('.select2').select2({
                language: {
                    noResults: function() {
                        return "No hay resultado";
                    },
                    searching: function() {
                        return "Buscando..";
                    }
                }
            });
        });
    </script>
@endsection

