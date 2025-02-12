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
            <button id="BotonAgregarPersona" class="btn btn-primary BotonAgregarPersona">
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
                                <label class="form-label">Representante de la empresa</label>
                                <select class="form-select selectToo" id="personas" name="persona_id">
                                    <option value="0" selected>SIN DATO</option>
                                    @foreach ($listaPersonas as $persona)
                                        <option value="{{$persona->id}}">{{$persona->apodo}}, {{$persona->nombres}} {{$persona->apellido_paterno}} {{$persona->apellido_materno}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label">Nombre de la empresa</label>
                                <input type="text" id="nombreEmpresa" name="nombreEmpresa" class="form-control" value="{{old('nombreEmpresa')}}">
                            </div>
                            <div class="col">
                                <label class="form-label">Pagina web</label>
                                <input type="text" id="paginaWeb" name="paginaWeb" class="form-control" value="{{old('paginaWeb')}}">
                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-sm-3">
                            <div class="col">
                                <label class="form-label">Telefono celular principal</label>
                                <input type="text" id="telefono1" name="telefono1" class="form-control" value="{{old('telefono1')}}">
                            </div>
                            <div class="col">
                                <label class="form-label">Telefono celular alternativo</label>
                                <input type="text" id="telefono2" name="telefono2" class="form-control" value="{{old('telefono2')}}">
                            </div>
                            <div class="col">
                                <label class="form-label">Telefono fijo</label>
                                <input type="text" id="telefono3" name="telefono3" class="form-control" value="{{old('telefono3')}}">
                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-sm-3">
                            <div class="col">
                                <label class="form-label">Correo electrónico principal</label>
                                <input type="email" id="correo1" name="correo1" class="form-control" value="{{old('correo1')}}">
                            </div>
                            <div class="col">
                                <label class="form-label">Correo electrónico alternativo 1</label>
                                <input type="email" id="correo2" name="correo2" class="form-control" value="{{old('correo2')}}">
                            </div>
                            <div class="col">
                                <label class="form-label">Correo electrónico alternativo 2</label>
                                <input type="email" id="correo3" name="correo3" class="form-control" value="{{old('correo3')}}">
                            </div>
                        </div>
                    </div>
                    <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                        <h3>Domicilio de la empresa</h3>
                        <div class="row row-cols-1 row-cols-sm-3">
                            <div class="col">
                                <label class="form-label">Calle principal</label>
                                <input type="text" id="calle1" name="calle1" class="form-control" value="{{old('calle1')}}">
                            </div>
                            <div class="col">
                                <label class="form-label">Calle colindante 1</label>
                                <input type="text" id="calle2" name="calle2" class="form-control" value="{{old('calle2')}}">
                            </div>
                            <div class="col">
                                <label class="form-label">Calle colindante 2</label>
                                <input type="text" id="calle3" name="calle3" class="form-control" value="{{old('calle3')}}">
                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-sm-3">
                            <div class="col">
                                <label class="form-label">Número exterior</label>
                                <input type="text" id="numero_exterior" name="numero_exterior" class="form-control" value="{{old('numero_exterior')}}">
                            </div>
                            <div class="col">
                                <label class="form-label">Número interior</label>
                                <input type="text" id="numero_interior" name="numero_interior" class="form-control" value="{{old('numero_interior')}}">
                            </div>
                            <div class="col">
                                <label class="form-label">Colonia</label>
                                <select id="colonias" name="colonia_id" class="form-select selectToo" style="width: 100%">
                                    <option value="0">SIN DATO</option>
                                    @foreach ($listaColonias as $colonia)
                                        <option value="{{$colonia->id}}">{{$colonia->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Referencia de oficina</label>
                            <textarea id="referencia" name="referencia" class="form-control" rows="5"></textarea>
                        </div>
                        <div class="col">
                            <label class="form-label mt-3">¿Donde vive la persona? (Dar double click para crear una marca)</label>
                            <center>
                                <input type="hidden" id="coordenadas" name="coordenadas" value="{{old('coordenadas')}}">
                            </center>
                            <center>
                                <div id="map" class="mx-auto" style="width:100%;height:400px"></div>
                                @error('coordenadas')
                                        <div class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </center>
                        </div>
                    </div>
                </div>
            <br>
            <div>
                <center>
                    <button id="BotonAgregarPersona" class="btn btn-primary BotonAgregarPersona">
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
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDg60SDcmNRPnG1tzZNBBGFx02cW2VkWWQ&callback=initMap&v=weekly" defer></script> --}}
    <script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_SEN6wP2RzPdhZKjFPAW6M-iNIdBtnHQ&callback=initMap">
    </script>
    <script text="text/javascript">
    var marker;
    var marker2;
    var currentUrl = window.location.href;
    const myLatLng = { lat: 24.123954, lng: - 110.311664 };
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

        google.maps.event.addListener(map, 'dblclick', function (event) {
            placeMarker(event.latLng);
            document.getElementById("coordenadas").value = event.latLng.lat() + "," + event.latLng.lng();
        });
    }
    window.initMap = initMap;
    function buscarUbicacion(nombre) {
        var apiKey = 'AIzaSyDg60SDcmNRPnG1tzZNBBGFx02cW2VkWWQ';
        var url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + encodeURIComponent(nombre) + '&key=' + apiKey;
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
                placeMarker({lat: latitud, lng: longitud});
            }
        })
        .catch(error => {
            console.error('Error al buscar la ubicación:', error);
        });
    }
        $('.BotonAgregarPersona').click(function (){
            $('#formularioAgregarSimpatizante').trigger('submit');
        });

    $(document).ready(function () {
        @if(!str_contains($urlFormulario, "agregar"))
            cargarFormulario();
        @endif
    });
        function cargarFormulario(){
            var valores = @json($empresa);
            console.log(valores);
            $('#personas').val(valores.persona_id ?? 0);
            $('#nombreEmpresa').val(valores.nombreEmpresa);
            $('#paginaWeb').val(valores.paginaWeb);
            $('#telefono1').val(valores.telefono1);
            $('#telefono2').val(valores.telefono2);
            $('#telefono3').val(valores.telefono3);
            $('#correo1').val(valores.correo1);
            $('#correo2').val(valores.correo2);
            $('#correo3').val(valores.correo3);
            $('#calle1').val(valores.relacion_domicilio[0].domicilio.calle1);
            $('#calle2').val(valores.relacion_domicilio[0].domicilio.calle2);
            $('#calle3').val(valores.relacion_domicilio[0].domicilio.calle3);
            $('#numero_exterior').val(valores.relacion_domicilio[0].domicilio.numero_exterior);
            $('#numero_interior').val(valores.relacion_domicilio[0].domicilio.numero_interior);
            $('#colonias').val(valores.relacion_domicilio[0].domicilio.colonia_id ?? 0);
            $('#referencia').val(valores.relacion_domicilio[0].domicilio.referencia);
            setTimeout(function () {
                if(valores.relacion_domicilio[0].domicilio.latitud != null){
                    $("#coordenadas").val(valores.relacion_domicilio[0].domicilio.latitud + ',' + valores.relacion_domicilio[0].domicilio.longitud);
                    console.log({lat: valores.relacion_domicilio[0].domicilio.latitud, lng: valores.relacion_domicilio[0].domicilio.longitud});
                    placeMarker({lat: valores.relacion_domicilio[0].domicilio.latitud, lng: valores.relacion_domicilio[0].domicilio.longitud});
                }
            }, 2000);

        }
    </script>
@endsection
