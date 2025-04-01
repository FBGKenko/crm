<?php

namespace App\Http\Controllers\compras;

use App\Http\Controllers\Controller;
use App\Http\Requests\catalogoRequest;
use App\Models\categoria;
use App\Models\producto;
use Illuminate\Http\Request;

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
    public function modificar($producto){
        $titulo = "Modificar producto";
        $listaCategorias = categoria::all();
        return view('pages.compras.formulario', compact('titulo', 'listaCategorias'));
    }
    public function agregando(catalogoRequest $request){
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
}
