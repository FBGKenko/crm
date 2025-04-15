<?php

namespace App\Http\Controllers\compras;

use App\Http\Controllers\Controller;
use App\Http\Requests\catalogoRequest;
use App\Http\Requests\varianteRequest;
use App\Models\categoria;
use App\Models\precio;
use App\Models\producto;
use App\Models\variante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class catalogocontroller extends Controller
{
    public function index(){
        $listaProductos = producto::where('fechaBorrado', null)->get();
        return view('pages.compras.catalogo', compact('listaProductos'));
        return $listaProductos;
    }
    public function agregar(){
        $titulo = "Crear producto";
        $ruta = route('catalogo.agregar');
        $listaCategorias = categoria::all();
        return view('pages.compras.formulario', compact('titulo', 'listaCategorias', 'ruta'));
    }
    public function modificar(producto $producto){
        $titulo = "Modificar producto";
        $ruta = route('catalogo.modificar', $producto->id);
        $listaCategorias = categoria::all();
        return view('pages.compras.formulario', compact('titulo', 'listaCategorias', 'producto', 'ruta'));
    }
    public function agregando(catalogoRequest $request){
        Log::info($request);
        $productoNuevo = producto::create($request->producto);
            
        return [
            "mensaje" => "Se ha agregado el producto con éxito"
        ];
    }
    public function modificando(Request $request, $producto){
        return redirect()->route('catalogo.index');
    }
    public function agregarCategoria(Request $request){
        $resultado = [
            "resultado" => false,
            "mensaje" => "Ha ocurrido un error al crear una categoria",
            "idCategoria" => 0,
            "nombreCategoria" => ""
        ];
        if(strlen(trim($request->categoria)) <= 0){
            $resultado["mensaje"] = "La categoria ingresada esta vacia";
            return $resultado;
        }
        if(categoria::where('nombre', strtoupper(trim($request->categoria)))->exists()){
            $resultado["mensaje"] = "La categoria ya existe";
            return $resultado;
        }
        $categoria = new categoria();
        $categoria->nombre = strtoupper(trim($request->categoria));
        $categoria->save();
        $resultado["idCategoria"] = $categoria->id;

        $resultado["resultado"] = true;
        $resultado["mensaje"] = "Se ha creado la categoria con éxito";
        $resultado["nombreCategoria"] = $categoria->nombre;
        return $resultado;
    }
    public function cargarVariantes(producto $producto){
        $variantes = variante::where('producto_id', $producto->id)
        ->select(
            'id',
            'codigo',
            'nombre',
            'presentacion',
            'cantidad',
            'unidad',
        )
        ->get();
        $resultado = [
            'titulo' => $producto->nombreCorto,
            'variantes' => $variantes
        ];
        return $resultado;
    }

    public function crearVariante(producto $producto, varianteRequest $request){
        $varianteNueva = variante::create($request->variante);
        return [
            "respuesta" => true,
            "mensaje" => "Se ha agregado la variante con éxito",
            "variante" => [$varianteNueva]
        ];
    }

    public function cargarPrecios(producto $producto){
        $listaPrecios = variante::select('id', 'nombre')
        ->with(['precios'])
        ->where('producto_id', $producto->id)
        ->get();

        $nombreVariantes = $listaPrecios->pluck('nombre')->toArray();
        $idsListaVariantes = $listaPrecios->pluck('id')->toArray();
        $nombrePrecios = precio::whereIn('variante_id', $idsListaVariantes)->pluck('nombre')->toArray();

        return [
            "lista" => $listaPrecios,
            'nombreVariantes' => $nombreVariantes,
            'nombrePrecios' => $nombrePrecios,
            'nombreProducto' => $producto->nombreCorto
        ];
    }
}
