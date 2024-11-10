<?php

namespace App\Http\Controllers\cotizaciones;

use App\Http\Controllers\Controller;
use App\Models\inventario;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InventarioController extends Controller
{
    public function index(){
        return view('Pages.inventario.index');
    }

    public function cargarTabla(Request $request){
        $draw = ($request->get('draw') != null) ? $request->get('draw') : 1;
        $start = ($request->get('start') != null) ? $request->get('start') : 0;
        $length = ($request->get('length') != null) ? $request->get('length') : 10;
        $filter = $request->get('search');
        $search = (isset($filter['value']))? $filter['value'] : false;
        // $ticketBuscar = $request->input('ticketAFiltrar');
        $consulta = inventario::where('fechaBorrado', null);
        // if ($Usuario->getRoleNames()->first() == 'CAPTURISTA') {
        //     $consulta->where('IdDependencia', $Usuario->IdDependencia);
        // }
        // if(isset($ticketBuscar)){
        //     $consulta->whereHas('personas', function ($consulta) use ($ticketBuscar){
        //         $numeroPalabras = explode(' ', $ticketBuscar);
        //         foreach ($numeroPalabras as $palabra) {
        //             $consulta->whereRaw("MATCH(Nombres, PrimerApellido, SegundoApellido, Curp) AGAINST('{$palabra}*' IN BOOLEAN MODE)");
        //         }
        //     });
        //     $consulta->orWhere(function($query) use ($ticketBuscar) {
        //         $numeroPalabras = explode(' ', $ticketBuscar);
        //         foreach ($numeroPalabras as $palabra) {
        //             $query->whereRaw("MATCH(Nombres, PrimerApellido, SegundoApellido, Curp) AGAINST('{$palabra}*' IN BOOLEAN MODE)");
        //         }
        //     });
        // }
        $consulta = $consulta->select(
            'id',
            'codigo',
            'nombreProducto',
            'existencia',
            'unidadMedida'
        );
        $totalesFiltrados = $consulta->get();
        $total = $consulta->count();
        $consulta->orderBy('id', 'DESC');
        $datos = $consulta->skip($start)
        ->take($length)
        ->get();
        return [
            'data' => $datos,
            'length' => $length,
            'recordsTotal' => $total,
            'recordsFiltered' => count($totalesFiltrados),
            'start' => $start,
            'draw' => $draw,
        ];
    }

    public function obtenerProducto($inventario){
        return inventario::select(
            'id',
            'nombreProducto',
            'unidadMedida',
            'existencia',
            'codigo',
        )->find($inventario);
    }

    public function vistaCrear(){
        $urlFormulario = route('inventario.crear');
        $empresa = [];
        return view('Pages.inventario.formulario', compact('urlFormulario', 'empresa'));
    }

    public function crear(Request $request){
        try{
            if(inventario::buscarProductoPorCodigo($request->codigo)){
                session()->flash('mensajeError', "El código ingresado ya se encuentra registrado");
                return back()->withInput();
            }
            inventario::crear($request->all());
            session()->flash('mensajeExito', "Se ha agregado el producto: {$request->codigo}");
            return redirect()->route('inventario.index');
        }
        catch(Exception $e){
            session()->flash('mensajeError', "Los campos ingresados no son validos");
            return back()->withInput();
        }
    }

    public function cambiarExistencia(Request $request){
        try{
            inventario::cambiarExistencia($request->all());
            return [true, "Se ha actualizado la existencia del producto"];
        }
        catch(Exception $e){
            Log::info($e->getMessage());
            return [false, "Ha ocurrido un error al cambiar la existencia"];
        }
    }

    public function eliminarProducto(Request $request){
        $producto = inventario::find($request->idInventario);
        $producto->fechaBorrado = Carbon::now();
        $producto->save();
        session()->flash('mensajeExito', "Se ha eliminado el producto: {$request->codigo}");
        return redirect()->route('inventario.index');
    }
}
