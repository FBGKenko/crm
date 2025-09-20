<?php

namespace App\Http\Controllers\cotizaciones;

use App\Http\Controllers\Controller;
use App\Models\pedido;
use App\Models\pedidoProducto;
use App\Models\producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidosController extends Controller
{
    public function index(){
        $pedidos = Pedido::select(
                'pedidos.*',
                DB::raw("TRIM(CONCAT(personas.apodo, ' ', personas.nombres, ' ', personas.apellido_paterno, ' ', personas.apellido_materno)) as nombre_completo"),
            )
            ->when(Auth::user()->hasRole('DISTRIBUIDOR'), function ($query) {
                $query->where('pedidos.persona_id', Auth::user()->persona->id);
            })
            ->join('pedido_productos', 'pedidos.id', '=', 'pedido_productos.pedido_id')
            ->join('personas', 'pedidos.persona_id', '=', 'personas.id')
            ->selectRaw("
                SUM(CASE WHEN pedido_productos.estatus = 'ENTREGADO' THEN 1 ELSE 0 END) as entregados,
                COUNT(pedido_productos.id) as total,
                ROUND(SUM(CASE WHEN pedido_productos.estatus = 'ENTREGADO' THEN 1 ELSE 0 END) / COUNT(pedido_productos.id) * 100, 2) as porcentaje_avance
            ")
            ->groupBy('pedidos.id', 'pedidos.folio', 'pedidos.estatus', 'pedidos.created_at', 'pedidos.updated_at', 'pedidos.persona_id', 'nombre_completo')
            ->get();
        return view('Pages.pedidos.index', compact('pedidos'));
    }

    public function vistaCrear(){
        $catalogo = producto::select('id', 'nombreCorto')
        ->get()
        ->map(function($item){
            $imagen = $item->imagenes()->first() != null ?
                '/storage/' . $item->imagenes()->first()->ruta :
                'img/img-default.jpg';
            $precio = $item->precios()
                ->where('nombre', 'PRECIO_DISTRIBUIDOR')
                ->select('monto')
                ->first() != null ?
                $item->precios()
                    ->where('nombre', 'PRECIO_DISTRIBUIDOR')
                    ->select('monto')
                    ->first()->monto : 'POR DEFINIR';
            return (object)[
                'id' => $item->id,
                'name' => $item->nombreCorto,
                'price' => $precio,
                'stock' => 1,
                'image' => $imagen,
            ];
        });
        return view('Pages.pedidos.form', compact('catalogo'));
    }

    public function hacerPedido(Request $request){

        $pedido = pedido::create([
            'estatus' => 'PENDIENTE',
            'persona_id' => Auth::user()->persona != null ? Auth::user()->persona->id : null,
        ]);
        $pedido->folio = 'PED-' . $pedido->id;
        $pedido->save();

        foreach ($request->cart as $producto) {
            $productoDelCarrito = producto::find($producto['productId']);
            $precioUnitario = $productoDelCarrito->precios()
                ->where('nombre', 'PRECIO_DISTRIBUIDOR')
                ->select('monto')
                ->first() != null ?
                $productoDelCarrito->precios()
                    ->where('nombre', 'PRECIO_DISTRIBUIDOR')
                    ->select('monto')
                    ->first()->monto : 0;

            $pivote = pedidoProducto::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $productoDelCarrito->id,
                'precioUnitario' => $precioUnitario,
                'cantidad' => $producto['qty'],
                'estatus' => 'PENDIENTE',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pedido realizado con éxito',
            'pedido_id' => $pedido->id,
        ]);
    }

    public function vistaVer(pedido $pedido){
        if(Auth::user()->hasRole('DISTRIBUIDOR') && $pedido->persona_id != Auth::user()->persona->id){
            return redirect()->route('pedidos.index');
        }

        $productos = $pedido->load('productos.producto', 'productos.producto.imagenes')->productos;
        $productos = $productos->map(function($item){
            $imagen = $item->producto->imagenes()->first() != null ?
                '/storage/' . $item->producto->imagenes()->first()->ruta :
                'img/img-default.jpg';
            return (object)[
                'id' => $item->id,
                'producto' => $item->producto->nombreCorto,
                'cantidad' => $item->cantidad,
                'estatus' => $item->estatus,
                'cliente' => $item->estatusCliente,
                'imagen' => $imagen,
                'observacion' => null
            ];
        });

        $modoUso = Auth::user()->hasRole('DISTRIBUIDOR') ? "cliente" : "encargado";
        return view('Pages.pedidos.details', compact('pedido', 'modoUso', 'productos'));
    }
}
