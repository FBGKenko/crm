<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>@yield('tittle')</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="{{ asset('Plantilla/css/styles.css') }}" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>

    <style>
        li ul.nav{
            background: #0d0f11;
        }
        h4{
            font-weight: 400;
        }

        label.form-label{
            font-weight: 700;
        }
        .imgLogotipo{
            margin-left: 0.3rem;
            width: 150px;
            height: auto;
        }
        /* .flex-column {
            flex-direction: column !important;
            background-color: #000000e3;
        } */
    </style>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <div class="navbar-brand ps-3">
                <img src="{{url('/')}}/img/logotipo.png" class="imgLogotipo" alt="logotipo_empresa">
            </div>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3 d-none d-sm-block" href="">{{$nombrePropietario}}</a>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <!-- <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button> -->
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i> {{$user->nombre.' '.$user->apellido_paterno.' '.$user->apellido_materno}}</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <!-- <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li> -->
                        <li><hr class="dropdown-divider" /></li>
                        <li>
                        <form action="{{route('logout')}}" method="post">
                            @csrf
                            <button class="dropdown-item" >Cerrar sesion</button>
                        </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menú</div>

                            @can('estadistica.index')
                            <li>
                                <a href="#submenu1" data-bs-toggle="collapse" class="nav-link collapsed" aria-expanded="false">
                                    <i class="fas fa-chart-bar"></i> <span class="ms-1 d-none d-sm-inline"> &nbsp; &nbsp;Estadística</span> </a>
                                <ul class="collapse  nav flex-column ms-1" id="submenu1" data-bs-parent="#menu">
                                    <li class="w-100">
                                        <a class="nav-link" href="{{url('/')}}/estadistica" class="nav-link ">
                                            <div class="sb-nav-link-icon"><i class="fa-solid fa-chart-simple"></i></div>
                                            Panel de Estadística
                                            </a>
                                    </li>
                                    @can('mapa.index')
                                        <li class="w-100">
                                            <a class="nav-link" href="{{url('/')}}/mapa">
                                                <div class="sb-nav-link-icon"><i class="fa-solid fa-location-dot"></i></div>
                                                Mapa de Personas
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                            @endcan
                            {{--
                            @can('encuestas.index')

                                <li>
                                    <a href="#submenu2" data-bs-toggle="collapse" class="nav-link collapsed" aria-expanded="false">
                                        <i class="fas fa-file"></i> <span class="ms-1 d-none d-sm-inline"> &nbsp; &nbsp;Encuestas</span>
                                    </a>
                                    <ul class="collapse  nav flex-column ms-1" id="submenu2" data-bs-parent="#menu">
                                        <li class="w-100">
                                            <a href="{{route('encuestas.index')}}" class="nav-link ">
                                                <div class="sb-nav-link-icon"><i class="fa-solid fa-file-lines"></i></div>
                                                 Encuestas
                                             </a>
                                        </li>
                                        <li class="w-100">
                                            <a class="nav-link" href="{{url('/')}}/encuestas/resultados">
                                                <div class="sb-nav-link-icon"><i class="fa-solid fa-file-signature"></i></div>
                                                Respuestas de las Encuestas
                                             </a>
                                        </li>
                                    </ul>
                                </li>

                            @endcan --}}

                            <li>
                                <a href="#submenu3" data-bs-toggle="collapse" class="nav-link collapsed" aria-expanded="false">
                                    <i class="fas fa-id-card"></i> <span class="ms-1 d-none d-sm-inline"> &nbsp; &nbsp;Contactos</span>
                                </a>
                                <ul class="collapse  nav flex-column ms-1" id="submenu3" data-bs-parent="#menu">
                                        <li class="w-100">
                                            <a class="nav-link" href="{{route('contactos.index')}}">
                                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                                Personas
                                            </a>
                                        </li>
                                        <li class="w-100">
                                            <a class="nav-link" href="{{route('empresas.index')}}">
                                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                                Empresas
                                            </a>
                                        </li>

                                    {{-- <li class="w-100">
                                        <a class="nav-link" href="{{url('/')}}/crudPromotores">
                                            <div class="sb-nav-link-icon"><i class="fas fa-user-group"></i></div>
                                            Promotores
                                        </a>
                                    </li> --}}
                                    {{-- @can('mapa.index')
                                    <li class="w-100">
                                        <a class="nav-link" href="{{url('/')}}/mapa">
                                            <div class="sb-nav-link-icon"><i class="fa-solid fa-location-dot"></i></div>
                                            Mapa de Personas
                                        </a>
                                    </li>
                                    @endcan --}}
                                </ul>
                            </li>
                            {{-- <li>
                                <a href="#submenu2" data-bs-toggle="collapse" class="nav-link collapsed" aria-expanded="false">
                                    <i class="fas fa-balance-scale"></i> <span class="ms-1 d-none d-sm-inline"> &nbsp; &nbsp;Cotizaciones</span>
                                </a>
                                <ul class="collapse  nav flex-column ms-1" id="submenu2" data-bs-parent="#menu">
                                        <li class="w-100">
                                            <a class="nav-link" href="{{route('inventario.index')}}">
                                                <div class="sb-nav-link-icon"><i class="fas fa-archive"></i></div>
                                                Inventario
                                            </a>
                                        </li>
                                        <li class="w-100">
                                            <a class="nav-link" href="{{route('cotizacion.index')}}">
                                                <div class="sb-nav-link-icon"><i class="fas fa-balance-scale"></i></div>
                                                Cotizaciones
                                            </a>
                                        </li>
                                        <li class="w-100">
                                            <a class="nav-link" href="{{route('factura.index')}}">
                                                <div class="sb-nav-link-icon"><i class="fas fa-university"></i></div>
                                                Facturas
                                            </a>
                                        </li>
                                </ul>
                            </li> --}}
                            {{-- <li>
                                <a href="#submenu5" data-bs-toggle="collapse" class="nav-link collapsed" aria-expanded="false">
                                    <i class="fas fa-bullhorn"></i> <span class="ms-1 d-none d-sm-inline"> &nbsp; &nbsp;Marketing</span>
                                </a>
                                <ul class="collapse  nav flex-column ms-1" id="submenu5" data-bs-parent="#menu">
                                    <li class="w-100">
                                        <a class="nav-link" href="{{url('/')}}/objetivos">
                                            <div class="sb-nav-link-icon"><i class="fa-solid fa-bullseye"></i></div>
                                            Objetivos
                                        </a>
                                    </li>
                                    <li class="w-100">
                                        <a class="nav-link" href="{{url('/')}}/crudOportunidades">
                                            <div class="sb-nav-link-icon"><i class="fa-solid fa-person-burst"></i></div>
                                            Oportunidades
                                        </a>
                                    </li>
                                </ul>
                            </li> --}}
                        @can('crudUsuarios.create')
                            <li>
                                <a href="#submenu4" data-bs-toggle="collapse" class="nav-link collapsed" aria-expanded="false">
                                    <i class="fas fa-gear"></i> <span class="ms-1 d-none d-sm-inline"> &nbsp; &nbsp;Configuración</span>
                                </a>
                                <ul class="collapse nav flex-column ms-1" id="submenu4" data-bs-parent="#menu">
                                    <li class="w-100">
                                        <a class="nav-link" href="{{route('crudUsuario.index')}}">
                                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                            Usuarios del Sistema
                                        </a>
                                    </li>
                                    {{-- <li class="w-100">
                                        <a class="nav-link" href="{{route('permisos.index')}}">
                                            <div class="sb-nav-link-icon"><i class="fas fa-unlock-alt"></i></div>
                                            Permisos
                                        </a>
                                    </li> --}}
                                    <li class="w-100">
                                            <a class="nav-link" href="{{route('personalizar.index')}}">
                                                <div class="sb-nav-link-icon"><i class="fas fa-paint-brush"></i></div>
                                                Personalizar
                                            </a>
                                    </li>
                                    <li class="w-100 d-none">
                                        <a class="nav-link" href="{{route('catalogos.index')}}">
                                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                            Catalogos
                                        </a>
                                    </li>
                                    {{--                                     <li class="w-100">
                                        <a class="nav-link" href="{{route('importar.index')}}">
                                            <div class="sb-nav-link-icon"><i class="fas fa-upload"></i></div>
                                            Importar datos
                                        </a>
                                    </li> --}}
                                    <li class="w-100">
                                        <a class="nav-link" href="{{url('/')}}/bitacora">
                                            <div class="sb-nav-link-icon"><i class="fas fa-info"></i></div>
                                            Bitácora
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <a class="nav-link" href="{{route('integracion.index')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-globe"></i></div>
                                Integraciones
                            </a>
                        @endcan

                        @can('crudUsuarios.create')
                            <div class="sb-sidenav-menu-heading">Cotizaciones y Ventas</div>
                            <li>
                                <a href="#menuCatalogo" data-bs-toggle="collapse" class="nav-link collapsed" aria-expanded="false">
                                    <i class="fas fa-chart-bar"></i> <span class="ms-1 d-none d-sm-inline"> &nbsp; &nbsp;Productos</span> </a>
                                <ul class="collapse  nav flex-column ms-1" id="menuCatalogo" data-bs-parent="#menu">
                                    <li class="w-100">
                                        <a class="nav-link" href="{{route('catalogo.index')}}" class="nav-link ">
                                            <div class="sb-nav-link-icon"><i class="fa-solid fa-chart-simple"></i></div>
                                            Catálogo
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            {{-- <a class="nav-link" href="{{route('integracion.index')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-globe"></i></div>
                                Tablero de Ventas
                            </a>
                            <li>
                                <a href="#menuVentas" data-bs-toggle="collapse" class="nav-link collapsed" aria-expanded="false">
                                    <i class="fas fa-chart-bar"></i> <span class="ms-1 d-none d-sm-inline"> &nbsp; &nbsp;Ventas</span> </a>
                                <ul class="collapse  nav flex-column ms-1" id="menuVentas" data-bs-parent="#menu">
                                    <li class="w-100">
                                        <a class="nav-link" href="{{url('/')}}/estadistica" class="nav-link ">
                                            <div class="sb-nav-link-icon"><i class="fa-solid fa-chart-simple"></i></div>
                                            Cotizaciones
                                            </a>
                                    </li>
                                    <li class="w-100">
                                        <a class="nav-link" href="{{url('/')}}/estadistica" class="nav-link ">
                                            <div class="sb-nav-link-icon"><i class="fa-solid fa-chart-simple"></i></div>
                                            Ventas en Proceso
                                            </a>
                                    </li>
                                    <li class="w-100">
                                        <a class="nav-link" href="{{url('/')}}/estadistica" class="nav-link ">
                                            <div class="sb-nav-link-icon"><i class="fa-solid fa-chart-simple"></i></div>
                                            Zona de Empaquetado
                                            </a>
                                    </li>
                                    <li class="w-100">
                                        <a class="nav-link" href="{{url('/')}}/estadistica" class="nav-link ">
                                            <div class="sb-nav-link-icon"><i class="fa-solid fa-chart-simple"></i></div>
                                            Guías de Envío
                                            </a>
                                    </li>
                                    <li class="w-100">
                                        <a class="nav-link" href="{{url('/')}}/estadistica" class="nav-link ">
                                            <div class="sb-nav-link-icon"><i class="fa-solid fa-chart-simple"></i></div>
                                            Ventas Entregadas
                                            </a>
                                    </li>
                                </ul>
                            </li> --}}
                        @endcan
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Usuario:</div>
                        <span>{{$role}}</span>
                        <div class="small">{{Auth::user()->nivel_acceso}} {{Auth::user()->niveles}}</div>
                    </div>
                </nav>

            </div>
            <div id="layoutSidenav_content">
                <main>

                    @yield('cuerpo')

                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; IngeniaSI 2024</div>

                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('Plantilla/js/scripts.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.bootstrap4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
        @if (session()->has('mensajeExito'))
            Swal.fire({
                'title':"Éxito",
                'text':"{{session('mensajeExito')}}",
                'icon':"success"
            });
        @endif
        @if (session()->has('mensajeError'))
            Swal.fire({
                'title':"Error",
                'text':"{{session('mensajeError')}}",
                'icon':"error"
            });
        @endif
        @if (session('nivelAccesoDenegado'))
            Swal.fire({
                'title':"Acceso denegado",
                'text':"{{ session('nivelAccesoDenegado') }}",
                'icon':"warning"
            });
        @endif
        $('.select2').select2();

        function alertaCargando(activar = true){
            if (activar) {
                Swal.fire({
                    title: 'Cargando...',
                    html: 'Espera por favor...',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
            }
            else {
                Swal.close();
            }
        }
        function peticionAjax(url, funcionExito, tipoRequest = "GET", data = null) {
            $.ajax({
                url: url,
                type: tipoRequest,
                data: data,
                success: funcionExito,
                error: function (xhr, status, error) {
                    alertaCargando(false);
                }
            });
        }

        function cambiarValorSelect2(selector, valor = 0){
            $(selector).val(valor);
            $(selector).trigger('change');
        }
        function formatoInputLetrasYEspacios(e){
            const key = e.key;
            // Permite letras, espacio, y teclas de control (backspace, delete, etc.)
            if (!/^[a-zA-Z\s]$/.test(key) && key !== "Backspace" && key !== "Delete" &&
                key !== "ArrowLeft" && key !== "ArrowRight" && key !== "Tab") {
                e.preventDefault();
            }
        }
        </script>
        @yield('scripts')
    </body>




</html>
