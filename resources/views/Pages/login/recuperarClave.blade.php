<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Recuperar contraseña</title>
        <link href="{{ asset('Plantilla/css/styles.css') }}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            body, html {
                height: 100%;
                background-repeat: no-repeat;
                background: url("{{asset('Plantilla/assets/img/fondo.jpg')}}") no-repeat center center fixed;
                background-size: 100% 100%;
            }
        </style>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h4 class="text-center font-weight-light my-4">Recuperar contraseña</h4></div>
                                    <div class="card-body">
                                    <form action="{{route('login.cambiarClave', $token)}}" id="formularioIniciarSesion" method="post" class="form-floating mb-3">
                                        @csrf
                                        <div class="form-floating mb-3">
                                            <input class="form-control" type="password" name="clave" id="clave" min="3"/>
                                            <label for="">Nueva contraseña</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                                <input class="form-control" type="password" name="nuevaClave" id="nuevaClave"  />
                                                <label for="">Repetir contraseña</label>
                                        </div>
                                        <center><button class="botonInicio btn btn-primary">Cambiar</button></center>
                                        @error('email')
                                            <div class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                        @enderror
                                    </form>

                                    </div>
                                    <!-- <div class="card-footer text-center py-3">
                                        <div class="small"><a href="register.html">Need an account? Sign up!</a></div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; IngeniaSI 2024</div>

                        </div>
                    </div>
                </footer>
            </div>
        </div>

            <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" >
    Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-body">
        <center><img src="https://elturf.com/generales_imagenes/loading.gif" alt=""></center>

      </div>

    </div>
  </div>
</div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('Plantilla/js/scripts.js') }}"></script>
        <script text="text/javascript">
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

            $('#formularioIniciarSesion').submit(function(e){
                var clave1 = $('#clave').val();
                var clave2 = $('#nuevaClave').val();
                if(clave1 == '' && clave2 == ''){
                    Swal.fire({
                        'title':"Error",
                        'text':"Las contraseñas ingresadas no deben de estar vacias. Verifiquelas porfavor.",
                        'icon':"error"
                    });
                    return false;
                }
                else if(clave1 != clave2){
                    Swal.fire({
                        'title':"Error",
                        'text':"Las contraseñas ingresadas no coinciden. Verifiquelas porfavor.",
                        'icon':"error"
                    });
                    return false;
                }
                else{
                    Swal.fire({
                        title: 'Cargando...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        html: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
                    });
                }

            });
        </script>
    </body>
</html>


