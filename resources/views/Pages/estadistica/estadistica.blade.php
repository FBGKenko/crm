@extends('Pages.plantilla')

@section('tittle')
   Estadística
@endsection

@section('cuerpo')

<style>
    .select2-container .select2-selection--multiple {
    /* width: 120px; */
    }
    .contenedorSeccionesGraficas{
        overflow-x: auto;
        max-height: 825px;
    }
    .select2-container .select2-selection--single {
        height: 38px; /* Ajuste de altura para select2 */
    }
</style>
@can('estadistica.cambiarMeta')
    <!-- Modal Agregar Usuario -->
    <div class="modal fade" id="cargarMeta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            {{-- FORMULARIO DE CAMBIAR META --}}
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cargar meta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body contenedorCambiarMetas">
                    {{-- REPLICAR CON TODAS LAS SECCIONES EN EL READY --}}

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endcan
<div class="container-fluid px-4">
    <div class="d-flex justify-content-start">
        <h1 class="mt-4">Estadística</h1>
        <div class="d-flex align-self-end iconoRefrescar" style="opacity: 0;">
            <i class="fas fa-exclamation-triangle me-1"></i>
            <h5>Refresque la pagina para reflejar los cambios </h5>
            <a href="#" onclick="location.reload(true)"> Click aqui</a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-calendar me-1"></i>
                    Fecha de Inicio
                </div>
                <div class="card-body">
                    <input id="fechaInicio" class="form-control" type="date"  />
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-calendar me-1"></i>
                    Fecha de Final
                </div>
                <div class="card-body justify-content">
                    <center>
                        <input id="fechaFin" class="form-control" type="date"/>
                    </center>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card mb-2">
                <div class="card-header">
                    <i class="fas fa-search me-1"></i>
                    Base de Datos
                </div>
                <div class="card-body">
                    <input id="botonConsultar" class="btn btn-primary btn-block" type="button" value="Consultar" />
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formularioCollapse" aria-expanded="false" aria-controls="formularioCollapse">
                        Filtros
                    </button>
                    @can('estadistica.cambiarMeta')

                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cargarMeta">Cargar Meta </button>

                    @endcan
                </div>
            </div>

        </div>
    </div>
    <div class="collapse my-3" id="formularioCollapse">
        <div class="card card-body mt-3">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-bar-chart me-1"></i>
                            Entidad Federativa
                        </div>
                        <div class="card-body justify-content" style="max-height:100px; overflow:auto;">
                            <center>
                                <select id="filtroEntidadFederativa" class="form-select selectToo" style="width:100%;" name="" multiple="multiple">
                                    <option value="TODOS">TODOS</option>
                                    @foreach ($listaEntidadFederativa as $item)
                                        <option value="{{$item->id}}">{{$item->nombre}}</option>
                                    @endforeach
                                </select>
                            </center>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-bar-chart me-1"></i>
                            Distrito Federal
                        </div>
                        <div class="card-body justify-content" style="max-height:100px; overflow:auto;">
                            <center>
                                <select id="filtroDistritoFederal" class="form-select selectToo" style="width:100%;" name="" multiple="multiple">
                                    <option value="TODOS">TODOS</option>
                                    @foreach ($litaDistritoFederal as $item)
                                        <option value="{{$item->id}}">{{$item->id}}</option>
                                    @endforeach
                                </select>
                            </center>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-bar-chart me-1"></i>
                            Distrito Local
                        </div>
                        <div class="card-body justify-content" style="max-height:100px; overflow:auto;">
                            <center>
                                <select id="filtroDistritoLocal" class="form-select selectToo" style="width:100%;" name="" multiple="multiple">
                                    <option value="TODOS">TODOS</option>
                                    @foreach ($listaDistritoLocal as $item)
                                        <option value="{{$item->id}}">{{$item->id}}</option>
                                    @endforeach
                                </select>
                            </center>
                        </div>
                    </div>
                </div>
                <div class="col" id="PorSeparado">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-bar-chart me-1"></i>
                            Sección
                        </div>
                        <div class="card-body justify-content" style="max-height:100px; overflow:auto;">
                            <center>
                                <select id="seccionarAgrupar" class="form-select selectToo" style="width:100%;" name="seccionesAgrupadas[]" multiple="multiple">
                                    <option value="TODOS">TODOS</option>
                                    @foreach ($listaSecciones as $item)
                                        <option value="{{$item->id}}">{{$item->id}}</option>
                                    @endforeach
                                </select>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-users me-1"></i>
                            Tipo Relación
                        </div>
                        <div class="card-body justify-content" style="max-height:100px; overflow:auto;">
                            <center>
                                <select class="form-select selectToo" style="width:100%;" id="filtroRelacion" multiple="multiple">
                                    <option value="TODOS">TODOS</option>
                                    @foreach ($listaRelaciones as $item)
                                        <option value="{{$item}}">{{$item}}</option>
                                    @endforeach
                                </select>
                            </center>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-signal me-1"></i>
                            Estatus
                        </div>
                        <div class="card-body justify-content" style="max-height:100px; overflow:auto;">
                            <center>
                                <select class="form-select selectToo" style="width:100%;" id="filtroEstatus" multiple="multiple">
                                    <option value="TODOS">TODOS</option>
                                    @foreach ($listaEstatus as $item)
                                        <option value="{{$item->concepto}}">{{$item->concepto}}</option>
                                    @endforeach
                                </select>
                            </center>
                        </div>
                    </div>
                </div>
                <div class="col">

                </div>
                <div class="col" id="PorSeparado">

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between" data-bs-toggle="collapse" data-bs-target="#graficaSeccionesCollapse" aria-expanded="false" aria-controls="graficaSeccionesCollapse">
                    <div>
                        <i class="fas fa-chart-bar me-1"></i>
                        Secciones
                    </div>
                    <i class="fa-solid fa-arrow-down"></i>
                </div>
                <div id="graficaSeccionesCollapse" class="card-body collapse my-3 contenedorSeccionesGraficas">

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Registros Supervisados
                </div>
                <div class="card-body"><canvas id="graficaRegistrosSupervisados" width="100%" height="50"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Registros por Estatus
                </div>
                <div class="card-body"><canvas id="graficaRegistrosPorEstatus" width="100%" height="50"></canvas></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4 contenedorGraficoTiempo">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Registros por días
                </div>
                <div class="card-body"><canvas id="graficaTiempo" width="100%" height="50"></canvas></div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.2/chart.min.js"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.6.0"></script>
        <link href="vendor/select2/dist/css/select2.min.css" rel="stylesheet" />
        <script src="vendor/select2/dist/js/select2.min.js"></script>
        {{-- <script src="{{ asset('Plantilla/assets/demo/chart-area-demo.js')}}"></script>
        <script src="{{ asset('Plantilla/assets/demo/chart-bar-demo.js')}}"></script>
        <script src="{{ asset('Plantilla/assets/demo/chart-pie-demo.js')}}"></script> --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script text="text/javascript">
            var configurarSecciones;
            var datosVacios = false;
            var cambioMeta = false;
            var options = {
                tooltips: {
                    enabled: false
                },
                plugins: {
                    datalabels: {
                        formatter: (value, ctx) => {
                            const datapoints = ctx.chart.data.datasets[0].data
                            const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                            const percentage = value / total * 100
                            // return percentage.toFixed(2) + "%";
                            return value;
                        },
                        color: '#000',
                        backgroundColor: '#fff',
                        borderWidth: '1',
                        borderColor: '#aaa',
                        borderRadius: '5'
                    }
                }
            };
            var graficaSupervisado;
            var graficaRegistrosEstatus;
            var graficaTiempo;
            $(document).ready(function () {
                $('.selectToo, .select2').select2();
                Swal.fire({
                    title: 'Cargando...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    html: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
                });
                $.when(
                $.ajax({
                    type: "get",
                    url: "{{route('estadistica.inicializar')}}",
                    data: [],
                    contentType: "application/x-www-form-urlencoded",
                    success: function (response) {
                        if(response.conteoSeparado.length > 0){
                            if(response.conteoSeparado[0].seccion_id == null)
                                response.conteoSeparado[0].seccion_id = "Sin Ubicar";
                        }
                        $.each(response.seccionesAccesibles, function (indexInArray, valueOfElement) {
                            $('#seccionarAgrupar').append($('<option>').html(valueOfElement))
                        });
                        @can('estadistica.cambiarMeta')

                            $.each(response.seccionesConfigurarMetas, function (indexInArray, valueOfElement) {
                                var formulario = $('<form>').attr('method', 'post').addClass('formulariosCambiarMetas').append(
                                    $('<input>').attr('type', 'hidden').attr('name', '_token').val('{{csrf_token()}}'),
                                    $('<div>').addClass('row').append(
                                        $('<div>').addClass('col').append(
                                            $('<h4>').text('Sección Objetivo:'),
                                                $('<input>').attr('type', 'number').addClass('form-control').prop('disabled', true).val(valueOfElement.id),
                                                $('<input>').attr('type', 'hidden').attr('name', 'idSeccion').val(valueOfElement.id)
                                        ),
                                        $('<div>').addClass('col').append(
                                            $('<h4>').text('Meta de Registros:'),
                                                $('<input>').attr('type', 'number').attr('id', 'cantidadObjetivo').attr('name', 'cantidadObjetivo').addClass('form-control').val(valueOfElement.objetivo)
                                        ),
                                        $('<div>').addClass('col').append(
                                            $('<h4>').text('Listado Nominal:'),
                                                $('<input>').attr('type', 'number').attr('id', 'poblacion').attr('name', 'poblacion').addClass('form-control').val(valueOfElement.poblacion)
                                        ),
                                        $('<div>').addClass(['col', 'align-self-end']).append(
                                            $('<button>').addClass(['btn', 'btn-primary', 'botonCambiarMetaAjax']).text('Guardar')
                                        )
                                    )
                                )
                                $('.contenedorCambiarMetas').append(formulario);
                            });
                            $('.formulariosCambiarMetas').submit(function (e) {
                                e.preventDefault();
                                let datosAjax = $(this).serialize();
                                let datosParaValidar = $(this).serializeArray();

                                if((datosParaValidar[2].value == '' || datosParaValidar[2].value <= 0) || (datosParaValidar[3].value == '' || datosParaValidar[3].value <= 0)){
                                    Swal.fire({
                                        'title':"Error",
                                        'text':"Hay campos que deben ser mayores a 0",
                                        'icon':"error"
                                    });
                                }
                                else{
                                    Swal.fire({
                                        title: 'Cargando...',
                                        allowOutsideClick: false,
                                        showConfirmButton: false,
                                        html: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
                                    });
                                    $.when(
                                        $.ajax({
                                            type: "post",
                                            url: "{{route('estadistica.cargarMeta')}}",
                                            data: datosAjax,
                                            contentType: "application/x-www-form-urlencoded",
                                            success: function (response) {
                                                $('.iconoRefrescar').css('opacity','1');
                                                cambioMeta = true;
                                                Swal.close();
                                                if(response[0]){
                                                    Swal.fire({
                                                        'title':"Éxito",
                                                        'text':response[1],
                                                        'icon':"success"
                                                    });
                                                }
                                                else{
                                                    Swal.fire({
                                                        'title':"Error",
                                                        'text':response[1],
                                                        'icon':"error"
                                                    });
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
                                            if(!cambioMeta){
                                                Swal.close();
                                            }
                                    });
                                }
                            });
                        @endcan

                        let columnas = 0;
                        var grafico = '';
                        var renglon = $('<div>').addClass('row');
                        $.each(response.conteoSeparado, function (indexInArray, valueOfElement) {
                            grafico = $('<div>').addClass('col-lg-6').html(
                                $('<div>').addClass('card').addClass('mb-4').append(
                                    $('<div>').addClass('card-header').append(
                                        $('<i>').addClass('fas').addClass('fa-chart-bar').addClass('me-1'),
                                        $('<span>').text(`Conteo sección ${valueOfElement.seccion_id}`)
                                    ),
                                    $('<div>').addClass('card-body').html(
                                        $('<canvas>').attr('id', `graficaBarra_${valueOfElement.seccion_id}`).attr('width', '100%').attr('height', '50px')
                                    )
                                )
                            );
                            if(columnas >= 2){
                                columnas = 0;
                                $('.contenedorSeccionesGraficas').append(renglon);
                                renglon = $('<div>').addClass('row');
                                renglon.append(grafico);
                                columnas++;
                            }
                            else{
                                renglon.append(grafico);
                                columnas++;
                            }
                        });
                        if(columnas > 0){
                                columnas = 0;
                                $('.contenedorSeccionesGraficas').append(renglon);
                            }

                        $.each(response.conteoSeparado, function (indexInArray, valueOfElement) {
                            var ctx3 = document.getElementById(`graficaBarra_${valueOfElement.seccion_id}`);
                            var datos = [valueOfElement.conteoTotal, valueOfElement.objetivo, valueOfElement.poblacion];
                            var maximoActual = datos[0];
                            if(datos[1] > maximoActual){
                                maximoActual = datos[1];
                            }
                            if(datos[2] > maximoActual){
                                maximoActual = datos[2];
                            }
                            let labelsGrafica = [ "Registros Hechos", "Registros Objetivos", "Lista Nominal" ];
                            if(valueOfElement.seccion_id == 'Sin Ubicar'){
                                labelsGrafica = ["Registros Hechos"];
                                datos = [valueOfElement.conteoTotal];
                            }
                            var myLineChart = new Chart(ctx3, {
                                type: 'bar',
                                data: {
                                    labels: labelsGrafica,
                                    datasets: [{
                                        label: "Conteo",
                                        backgroundColor: "rgba(2,117,216,1)",
                                        borderColor: "rgba(2,117,216,1)",
                                        data: datos,
                                    }],
                                },
                                options: {
                                    scales: {
                                    xAxes: [{
                                        time: {
                                            unit: 'month'
                                        },
                                        gridLines: {
                                            display: false
                                        },
                                        ticks: {
                                            maxTicksLimit: 4
                                        }
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            min: 0,
                                            max: maximoActual,
                                            maxTicksLimit: 5
                                        },
                                        gridLines: {
                                            display: true
                                        }
                                    }],
                                    },
                                    legend: {
                                        display: false
                                    },
                                    plugins: {
                                        datalabels: {
                                            formatter: (value, ctx) => {
                                                const datapoints = ctx.chart.data.datasets[0].data
                                                const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                                                const percentage = value / total * 100
                                                // return percentage.toFixed(2) + "%";
                                                return value;
                                            },
                                            color: '#000',
                                            backgroundColor: '#fff',
                                            borderWidth: '1',
                                            borderColor: '#aaa',
                                            borderRadius: '5'
                                        }
                                    }
                                },
                                plugins: [ChartDataLabels],

                            });
                        });
                        var ctx = document.getElementById("graficaTiempo");
                        graficaTiempo = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: response.registrosPorFechas.fechas,
                                datasets: [{
                                    label: "Cantidad",
                                    lineTension: 0.3,
                                    backgroundColor: "rgba(2,117,216,0.2)",
                                    borderColor: "rgba(2,117,216,1)",
                                    pointRadius: 5,
                                    pointBackgroundColor: "rgba(2,117,216,1)",
                                    pointBorderColor: "rgba(255,255,255,0.8)",
                                    pointHoverRadius: 5,
                                    pointHoverBackgroundColor: "rgba(2,117,216,1)",
                                    pointHitRadius: 50,
                                    pointBorderWidth: 2,
                                    data: response.registrosPorFechas.conteos,
                                }],
                            },
                            options: {
                                scales: {
                                    xAxes: [{
                                        time: {
                                            unit: 'date'
                                        },
                                        gridLines: {
                                            display: false
                                        },
                                        ticks: {
                                            maxTicksLimit: 14
                                        }
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            min: 0,
                                            max: response.registrosPorFechas.maximo,
                                            maxTicksLimit: 5
                                        },
                                        gridLines: {
                                            color: "rgba(0, 0, 0, .125)",
                                        }
                                    }],
                                },
                                legend: {
                                    display: false
                                },
                                plugins: {
                                    datalabels: {
                                        formatter: (value, ctx) => {
                                            const datapoints = ctx.chart.data.datasets[0].data
                                            const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                                            const percentage = value / total * 100
                                            return '';
                                        },
                                        color: '#fff',
                                    }
                                }
                            },
                        });


                        var graficaRegistrosSupervisados = document.getElementById("graficaRegistrosSupervisados");
                        graficaSupervisado = new Chart(graficaRegistrosSupervisados, {
                            type: 'bar',
                            data: {
                                labels: response.conteoSupervisados.labels,
                                datasets: [{
                                    label: "Conteo",
                                    backgroundColor: "rgba(2,117,216,1)",
                                    borderColor: "rgba(2,117,216,1)",
                                    data: response.conteoSupervisados.datos,
                                }],
                            },
                            options: {
                                scales: {
                                xAxes: [{
                                    time: {
                                        unit: 'month'
                                    },
                                    gridLines: {
                                        display: false
                                    },
                                    ticks: {
                                        maxTicksLimit: 4
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                        min: 0,
                                        //max: maximoActual,
                                        maxTicksLimit: 5
                                    },
                                    gridLines: {
                                        display: true
                                    }
                                }],
                                },
                                legend: {
                                    display: false
                                },
                                plugins: {
                                    datalabels: {
                                        formatter: (value, ctx) => {
                                            const datapoints = ctx.chart.data.datasets[0].data
                                            const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                                            const percentage = value / total * 100
                                            // return percentage.toFixed(2) + "%";
                                            return value;
                                        },
                                        color: '#000',
                                        backgroundColor: '#fff',
                                        borderWidth: '1',
                                        borderColor: '#aaa',
                                        borderRadius: '5'
                                    }
                                }
                            },
                            plugins: [ChartDataLabels],

                        });
                        var graficaRegistrosPorEstatus = document.getElementById("graficaRegistrosPorEstatus");
                        graficaRegistrosEstatus = new Chart(graficaRegistrosPorEstatus, {
                            type: 'bar',
                            data: {
                                labels: response.conteoEstatus.labels,
                                datasets: [{
                                    label: "Conteo",
                                    backgroundColor: "rgba(2,117,216,1)",
                                    borderColor: "rgba(2,117,216,1)",
                                    data: response.conteoEstatus.datos,
                                }],
                            },
                            options: {
                                scales: {
                                xAxes: [{
                                    time: {
                                        unit: 'month'
                                    },
                                    gridLines: {
                                        display: false
                                    },
                                    ticks: {
                                        maxTicksLimit: 4
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                        min: 0,
                                        //max: maximoActual,
                                        maxTicksLimit: 5
                                    },
                                    gridLines: {
                                        display: true
                                    }
                                }],
                                },
                                legend: {
                                    display: false
                                },
                                plugins: {
                                    datalabels: {
                                        formatter: (value, ctx) => {
                                            const datapoints = ctx.chart.data.datasets[0].data
                                            const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                                            const percentage = value / total * 100
                                            // return percentage.toFixed(2) + "%";
                                            return value;
                                        },
                                        color: '#000',
                                        backgroundColor: '#fff',
                                        borderWidth: '1',
                                        borderColor: '#aaa',
                                        borderRadius: '5'
                                    }
                                }
                            },
                            plugins: [ChartDataLabels],

                        });




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
            });
            $('#botonConsultar').click(function (e) {
                datosVacios = false;
                var datosAjax = {
                    'fechaInicio': $('#fechaInicio').val(),
                    'fechaFin': $('#fechaFin').val(),
                    'entidades': $("#filtroEntidadFederativa").val(),
                    'distritosFederales': $("#filtroDistritoFederal").val(),
                    'distritosLocales': $("#filtroDistritoLocal").val(),
                    'secciones': $("#seccionarAgrupar").val(),
                    'tipoPersonas': $("#filtroRelacion").val(),
                    'estatus': $("#filtroEstatus").val(),
                }
                Swal.fire({
                    title: 'Cargando...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    html: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
                });
                Swal.fire({
                    title: 'Cargando...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    html: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
                });
                $.when(
                $.ajax({
                    type: "get",
                    url: "{{route('estadistica.inicializar')}}",
                    data: datosAjax,
                    contentType: "application/x-www-form-urlencoded",
                    success: function (response) {
                        if(response.conteoSeparado.length > 0 && response.conteoSeparado[0].seccion_id == null)
                            response.conteoSeparado[0].seccion_id = "Sin Ubicar";
                        let columnas = 0;
                        var grafico = '';
                        $('.contenedorSeccionesGraficas').html('');
                        var renglon = $('<div>').addClass('row');
                        $.each(response.conteoSeparado, function (indexInArray, valueOfElement) {
                            grafico = $('<div>').addClass('col-lg-6').html(
                                $('<div>').addClass('card').addClass('mb-4').append(
                                    $('<div>').addClass('card-header').append(
                                        $('<i>').addClass('fas').addClass('fa-chart-bar').addClass('me-1'),
                                        $('<span>').text(`Conteo sección ${valueOfElement.seccion_id}`)
                                    ),
                                    $('<div>').addClass('card-body').html(
                                        $('<canvas>').attr('id', `graficaBarra_${valueOfElement.seccion_id}`).attr('width', '100%').attr('height', '50px')
                                    )
                                )
                            );
                            if(columnas >= 2){
                                columnas = 0;
                                $('.contenedorSeccionesGraficas').append(renglon);
                                renglon = $('<div>').addClass('row');
                                renglon.append(grafico);
                                columnas++;
                            }
                            else{
                                renglon.append(grafico);
                                columnas++;
                            }
                        });
                        if(columnas > 0){
                                columnas = 0;
                                $('.contenedorSeccionesGraficas').append(renglon);
                            }

                        $.each(response.conteoSeparado, function (indexInArray, valueOfElement) {
                            var ctx3 = document.getElementById(`graficaBarra_${valueOfElement.seccion_id}`);
                            var datos = [valueOfElement.conteoTotal, valueOfElement.objetivo, valueOfElement.poblacion];
                            var maximoActual = datos[0];
                            if(datos[1] > maximoActual){
                                maximoActual = datos[1];
                            }
                            if(datos[2] > maximoActual){
                                maximoActual = datos[2];
                            }
                            let labelsGrafica = [ "Registros Hechos", "Registros Objetivos", "Lista Nominal" ];
                            if(valueOfElement.seccion_id == 'Sin Ubicar'){
                                labelsGrafica = ["Registros Hechos"];
                                datos = [valueOfElement.conteoTotal];
                            }
                            var myLineChart = new Chart(ctx3, {
                                type: 'bar',
                                data: {
                                    labels: labelsGrafica,
                                    datasets: [{
                                        label: "Conteo",
                                        backgroundColor: "rgba(2,117,216,1)",
                                        borderColor: "rgba(2,117,216,1)",
                                        data: datos,
                                    }],
                                },
                                options: {
                                    scales: {
                                    xAxes: [{
                                        time: {
                                            unit: 'month'
                                        },
                                        gridLines: {
                                            display: false
                                        },
                                        ticks: {
                                            maxTicksLimit: 4
                                        }
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            min: 0,
                                            max: maximoActual,
                                            maxTicksLimit: 5
                                        },
                                        gridLines: {
                                            display: true
                                        }
                                    }],
                                    },
                                    legend: {
                                        display: false
                                    },
                                    plugins: {
                                        datalabels: {
                                            formatter: (value, ctx) => {
                                                const datapoints = ctx.chart.data.datasets[0].data
                                                const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                                                const percentage = value / total * 100
                                                // return percentage.toFixed(2) + "%";
                                                return value;
                                            },
                                            color: '#000',
                                            backgroundColor: '#fff',
                                            borderWidth: '1',
                                            borderColor: '#aaa',
                                            borderRadius: '5'
                                        }
                                    }
                                },
                                plugins: [ChartDataLabels],

                            });
                        });

                        $("#graficaRegistrosSupervisados").html('');
                        var graficaRegistrosSupervisados = document.getElementById("graficaRegistrosSupervisados");
                        graficaSupervisado.destroy();
                        graficaSupervisado = new Chart(graficaRegistrosSupervisados, {
                            type: 'bar',
                            data: {
                                labels: response.conteoSupervisados.labels,
                                datasets: [{
                                    label: "Conteo",
                                    backgroundColor: "rgba(2,117,216,1)",
                                    borderColor: "rgba(2,117,216,1)",
                                    data: response.conteoSupervisados.datos,
                                }],
                            },
                            options: {
                                scales: {
                                xAxes: [{
                                    time: {
                                        unit: 'month'
                                    },
                                    gridLines: {
                                        display: false
                                    },
                                    ticks: {
                                        maxTicksLimit: 4
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                        min: 0,
                                        //max: maximoActual,
                                        maxTicksLimit: 5
                                    },
                                    gridLines: {
                                        display: true
                                    }
                                }],
                                },
                                legend: {
                                    display: false
                                },
                                plugins: {
                                    datalabels: {
                                        formatter: (value, ctx) => {
                                            const datapoints = ctx.chart.data.datasets[0].data
                                            const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                                            const percentage = value / total * 100
                                            // return percentage.toFixed(2) + "%";
                                            return value;
                                        },
                                        color: '#000',
                                        backgroundColor: '#fff',
                                        borderWidth: '1',
                                        borderColor: '#aaa',
                                        borderRadius: '5'
                                    }
                                }
                            },
                            plugins: [ChartDataLabels],

                        });
                        $("#graficaRegistrosPorEstatus").html('');
                        var graficaRegistrosPorEstatus = document.getElementById("graficaRegistrosPorEstatus");
                        graficaRegistrosEstatus.destroy();
                        graficaRegistrosEstatus = new Chart(graficaRegistrosPorEstatus, {
                            type: 'bar',
                            data: {
                                labels: response.conteoEstatus.labels,
                                datasets: [{
                                    label: "Conteo",
                                    backgroundColor: "rgba(2,117,216,1)",
                                    borderColor: "rgba(2,117,216,1)",
                                    data: response.conteoEstatus.datos,
                                }],
                            },
                            options: {
                                scales: {
                                xAxes: [{
                                    time: {
                                        unit: 'month'
                                    },
                                    gridLines: {
                                        display: false
                                    },
                                    ticks: {
                                        maxTicksLimit: 4
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                        min: 0,
                                        //max: maximoActual,
                                        maxTicksLimit: 5
                                    },
                                    gridLines: {
                                        display: true
                                    }
                                }],
                                },
                                legend: {
                                    display: false
                                },
                                plugins: {
                                    datalabels: {
                                        formatter: (value, ctx) => {
                                            const datapoints = ctx.chart.data.datasets[0].data
                                            const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                                            const percentage = value / total * 100
                                            // return percentage.toFixed(2) + "%";
                                            return value;
                                        },
                                        color: '#000',
                                        backgroundColor: '#fff',
                                        borderWidth: '1',
                                        borderColor: '#aaa',
                                        borderRadius: '5'
                                    }
                                }
                            },
                            plugins: [ChartDataLabels],

                        });
                        $("#graficaTiempo").html('');
                        var ctx = document.getElementById("graficaTiempo");
                        graficaTiempo.destroy();
                        graficaTiempo = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: response.registrosPorFechas.fechas,
                                datasets: [{
                                    label: "Cantidad",
                                    lineTension: 0.3,
                                    backgroundColor: "rgba(2,117,216,0.2)",
                                    borderColor: "rgba(2,117,216,1)",
                                    pointRadius: 5,
                                    pointBackgroundColor: "rgba(2,117,216,1)",
                                    pointBorderColor: "rgba(255,255,255,0.8)",
                                    pointHoverRadius: 5,
                                    pointHoverBackgroundColor: "rgba(2,117,216,1)",
                                    pointHitRadius: 50,
                                    pointBorderWidth: 2,
                                    data: response.registrosPorFechas.conteos,
                                }],
                            },
                            options: {
                                scales: {
                                    xAxes: [{
                                        time: {
                                            unit: 'date'
                                        },
                                        gridLines: {
                                            display: false
                                        },
                                        ticks: {
                                            maxTicksLimit: 14
                                        }
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            min: 0,
                                            max: response.registrosPorFechas.maximo,
                                            maxTicksLimit: 5
                                        },
                                        gridLines: {
                                            color: "rgba(0, 0, 0, .125)",
                                        }
                                    }],
                                },
                                legend: {
                                    display: false
                                },
                                plugins: {
                                    datalabels: {
                                        formatter: (value, ctx) => {
                                            const datapoints = ctx.chart.data.datasets[0].data
                                            const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                                            const percentage = value / total * 100
                                            return '';
                                        },
                                        color: '#fff',
                                    }
                                }
                            },
                        });
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

            });

            function generarEstadistica(){

            }

            function componentToHex(c) {
                var hex = c.toString(16); // Convertir el valor a hexadecimal
                return hex.length == 1 ? "0" + hex : hex; // Asegurarse de que el resultado siempre tenga dos caracteres
            }
        </script>
@endsection

