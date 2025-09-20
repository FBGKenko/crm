<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Imports\metasSeccionImport;
use App\Imports\personasYDatosImport;
use App\Mail\recuperarClave;
use App\Models\bitacora;
use App\Models\tokenClave;
use App\Models\User;
use App\Models\Usuario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class iniciarSesionController extends Controller
{
    public function index(Request $formulario){
        $user = auth()->user();
        if($user){
            switch ($user->getRoleNames()->first()) {
                case 'SUPER ADMINISTRADOR':
                    return redirect()->route('crudUsuario.index');
                    break;
                case 'ADMINISTRADOR':
                    return redirect()->route('estadistica.index');
                    break;
                case 'SUPERVISOR':
                    return redirect()->route('crudSimpatizantes.index');
                    break;
                case 'CAPTURISTA':
                    return redirect()->route('crudSimpatizantes.index');
                    break;
                case 'CONSULTAS':
                    return redirect()->route('crudSimpatizantes.index');
                    break;
                case 'DISTRIBUIDOR':
                    return redirect()->route('pedidos.index');
                    break;
            }
        }
        else{
            $bitacora = new bitacora();
            $bitacora->accion = 'entrando pantalla iniciar sesion';
            $bitacora->url = url()->current();
            $bitacora->ip = $formulario->ip();
            $bitacora->tipo = 'vista';
            $bitacora->user_id = null;
            $bitacora->save();
            return view('Pages.login.inicioSesion');
        }

    }
    public function validarUsuario(Request $formulario){
        $validarSiEliminado = User::where('email', strtoupper($formulario->correo))->first();
        if(!isset($validarSiEliminado) || isset($validarSiEliminado->deleted_at)){
            return back()->withErrors(['email' => 'El correo ingresado es incorrecto']);
        }
        if (Auth::attempt(['email' => strtoupper($formulario->correo), 'password' => $formulario->contrasenia])) {
            $bitacora = new bitacora();
            $bitacora->accion = 'Iniciando sesion';
            $bitacora->url = url()->current();
            $bitacora->ip = $formulario->ip();
            $bitacora->tipo = 'post';
            $bitacora->user_id = null;
            $bitacora->save();
            // Obtener el usuario de la sesion
            $user = auth()->user();
            switch ($validarSiEliminado->getRoleNames()->first()) {
                case 'SUPER ADMINISTRADOR':
                    return redirect()->route('crudUsuario.index');
                    break;
                case 'ADMINISTRADOR':
                    return redirect()->route('estadistica.index');
                    break;
                case 'SUPERVISOR':
                    return redirect()->route('contactos.index');
                    break;
                case 'CAPTURISTA':
                    return redirect()->route('contactos.index');
                    break;
                    case 'CONSULTAS':
                        return redirect()->route('contactos.index');
                        break;
                default:
                    return back()->withErrors(['email' => 'Ocurrió un error con el usuario ingresado, comuniquese con el administrador del sistema.']);
                    break;
            }

        } else {
            return back()->withErrors(['email' => 'La contraseña ingresada es incorrecta']);
        }
    }
    public function cerrarSesion(Request $formulario){
        $user = auth()->user();
        $bitacora = new bitacora();
        $bitacora->accion = 'Cerrando sesion de : ' . $user->email;
        $bitacora->url = url()->current();
        $bitacora->ip = $formulario->ip();
        $bitacora->tipo = 'post';
        $bitacora->user_id = $user->id;
        $bitacora->save();

        Auth::logout();
        return redirect()->route('login');
    }
    public function enviarCorreo(Request $formulario){
        $correo = strtolower(trim($formulario->recuperarCorreo));
        $usuario = User::where('email', $correo)->first();
        if($usuario){
            //FALTA VALIDAR CUANDO EL USUARIO YA TIENE UN TOKEN CREADO
            $token = "";
            $banderaSalida = true;
            $token = Str::random(200);
            while ($banderaSalida) {
                try {
                    tokenClave::create([
                        'token' => $token,
                        'user_id' => $usuario->id,
                    ]);
                    $banderaSalida = false;
                } catch (Exception $e) {
                    $token = Str::random(200);
                    Log::info($e->getMessage());
                }
            }
            $url = route('login.vistaRecuperarClave', $token);
            $mensajeCorreo = new recuperarClave($url);
            try{
                Mail::to($correo)->send($mensajeCorreo);
                session()->flash('mensajeExito', 'Se ha enviado un mensaje a su correo para restablecer su contraseña. El mensaje de correo Tiene vigencia de 48 horas');
                return redirect()->route('login');
            }
            catch(Exception $e){
                Log::info($e->getMessage());
                tokenClave::where('user_id', $usuario->id)->delete();
                session()->flash('mensajeError', 'Ha ocurrido un error al enviar el correo, Intente enviandolo de nuevo');
                return redirect()->route('login');
            }
        }
        session()->flash('mensajeError', 'El correo ingresado no pertenece a la base de datos');
        return redirect()->route('login');
    }

    public function vistaRecuperarClave($token){
        $tokenEncontrado = tokenClave::where('token', $token)->first();
        if($tokenEncontrado){
            $idUsuario = $tokenEncontrado->user_id;
            return view('Pages.login.recuperarClave', compact('idUsuario', 'token'));
        }
        else{
            session()->flash('mensajeError', 'Hubo un error al intentar recuperar la contraseña');
            return redirect()->route('login');
        }
    }

    public function cambiarClave(Request $form, $token){
        $tokenEncontrado = tokenClave::where('token', $token)->first();
        if($tokenEncontrado){
            try {
                DB::transaction(function () use ($tokenEncontrado, $form){
                    $usuario = User::find($tokenEncontrado->user_id);
                    $usuario->password = Hash::make($form->clave);
                    $usuario->save();
                    $tokenEncontrado->delete();
                });
                session()->flash('mensajeExito', 'Se ha cambiado la contraseña con éxito');
                return redirect()->route('login');
            } catch (Exception $e) {
                session()->flash('mensajeError', 'Hubo un error al cambiar la contraseña. intentelo de nuevo');
                return back()->withInput();
            }
        }
        session()->flash('mensajeError', 'Hubo un error, no tiene acceso a cambiar la clave del usuario');
        return back()->withInput();
    }
}
