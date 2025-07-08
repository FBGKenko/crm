<?php

namespace App\Http\Controllers\compras;

use App\Exports\productoExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\catalogoRequest;
use App\Http\Requests\varianteRequest;
use App\Imports\CatalogoProductoImport;
use App\Models\bitacora;
use App\Models\categoria;
use App\Models\imagenProducto;
use App\Models\perfilPrecio;
use App\Models\precio;
use App\Models\producto;
use App\Models\variante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Stmt\Return_;

class catalogocontroller extends Controller
{
    public function index(){
        bitacora::crearRegistro('ACCESO AL CATALOGO DE PRODUCTOS', request()->ip(), 'compras');
        $listaProductos = producto::where('fechaBorrado', null)
        ->orderByDesc('id')
        ->get();
        return view('pages.compras.catalogo', compact('listaProductos'));
        return $listaProductos;
    }
    public function agregar(){
        $titulo = "Crear producto";
        $ruta = route('catalogo.agregar');
        $listaCategorias = categoria::all();

        $configuracionPrecios = perfilPrecio::first()->jsonNombrePrecios;
        $configuracionPrecios = json_decode($configuracionPrecios, true);
        $productoSinPrecio = (object)[
            'sinPrecios' => true,
            'variantes' => false,
        ];
        //DECLARAR OBJETO ANONIMO PRODUCTO
        $producto = (object)[
            "variantes" => [],
            "precios" => [],
            "imagenes" => [],
        ];
        return view('pages.compras.formulario', compact('titulo', 'listaCategorias', 'producto', 'ruta', 'productoSinPrecio', 'configuracionPrecios'));
    }
    public function agregando(catalogoRequest $request){
        try {
            DB::beginTransaction();
            //CREAR OBJETO DEL PRODUCTO
            $productoNuevo = producto::create($request->producto);
            $categoria = $productoNuevo->categorias->nombre ?? '';
            $presentacion = $productoNuevo->presentacion ?? '';
            $codigoProducto = 'NIT' . strtoupper(substr($categoria, 0, 2)) . $presentacion . $productoNuevo->id;
            $productoNuevo->codigo = $codigoProducto;
            $productoNuevo->save();

            //CREAR REGISTRO EN BITACORA
            bitacora::crearRegistro('CREACION DE PRODUCTO: ' . $productoNuevo->nombreCorto, request()->ip(), 'compras');

            //VALIDAR SI EXISTEN IMAGENES
            if ($request->hasFile('images')) {
                //AGREGAR IMAGENES AL PRODUCTO DEL REQUEST
                foreach ($request->file('images') as $image) {
                    $path = $image->store('product_images', 'public');
                    imagenProducto::create([
                        'producto_id' => $productoNuevo->id,
                        'ruta' => $path,
                    ]);
                }
            }
            //VALIDAR SI PUEDE TENER VARIANTES EL PRODUCTO
            if($request->producto["SinVariantes"] == 'true'){
                $productoNuevo->precios()->createMany($request->producto["precios"] ?? []);
            }
            else{
                //AGREGAR VARIANTES AL PRODUCTO DEL REQUEST
                foreach($request->producto["variantes"] as $key => $value){
                    //CREAR VARIANTE
                    $variante = variante::create([
                        'nombre' => $key,
                        'producto_id' => $productoNuevo->id
                    ]);
                    $codigoVariante = $codigoProducto . '-' . $variante->id;
                    $variante->codigo = $codigoVariante;
                    $variante->save();

                    $variante->precios()->createMany($value["precios"] ?? []);
                }
            }
            DB::commit();
            return [
                "respuesta" => true,
                "mensaje" => "Se ha agregado el producto con éxito"
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            bitacora::crearRegistro('ERROR AL CREAR PRODUCTO: ' . $e->getMessage(), request()->ip(), 'compras');
            return [
                "respuesta" => false,
                "mensaje" => "Se produjo un error al agregar el producto"
            ];
        }
    }
    public function modificar(producto $producto){
        $titulo = "Modificar producto";
        $ruta = route('catalogo.modificar', $producto->id);
        $listaCategorias = categoria::all();
        $configuracionPrecios = perfilPrecio::first()->jsonNombrePrecios;
        $configuracionPrecios = json_decode($configuracionPrecios, true);
        $productoSinPrecio = perfilPrecio::ComprobarSiTienenPrecios($producto);
        return view('pages.compras.formulario', compact('titulo', 'listaCategorias', 'producto', 'ruta', 'configuracionPrecios', 'productoSinPrecio'));
    }
    public function modificando(Request $request, producto $producto){
        try {
            DB::beginTransaction();
            $producto->update($request->producto);
            $categoria = $producto->categorias->nombre ?? '';
            $presentacion = $producto->presentacion ?? '';
            $codigoProducto = 'NIT' . strtoupper(substr($categoria, 0, 2)) . $presentacion . $producto->id;
            $producto->codigo = $codigoProducto;
            $producto->save();
            $producto->precios()->delete();
            $producto->variantes()->each(function ($variante) {
                $variante->precios()->delete();
                $variante->delete();
            });

            // 1. Eliminar imágenes marcadas
            if ($request->has('delete_ids')) {
                foreach ($request->delete_ids as $id) {
                    $imagen = imagenProducto::find($id);
                    if ($imagen) {
                        Storage::disk('public')->delete($imagen->ruta); // borra del storage
                        $imagen->delete(); // borra del DB
                    }
                }
            }

            foreach ($request->file('images') ?? [] as $image) {
                $path = $image->store('product_images', 'public');
                imagenProducto::create([
                    'producto_id' => $producto->id,
                    'ruta' => $path,
                ]);
            }

            Log::info($request->all());
            if ($request->producto["SinVariantes"] == 'true') {
                $producto->precios()->createMany($request->producto["precios"] ?? []);
            } else {
                foreach ($request->producto["variantes"] as $key => $value) {
                    log::info($key);
                    $variante = variante::firstOrCreate(
                        [ 'nombre' => $key],
                        ['producto_id' => $producto->id]
                    );
                    $codigoVariante = $codigoProducto . '-' . $variante->id;
                    $variante->codigo = $codigoVariante;
                    $variante->save();

                    $variante->precios()->createMany($value["precios"] ?? []);
                }
            }
            DB::commit();
            return [
                "respuesta" => true,
                "mensaje" => "Se ha modificado el producto con éxito"
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al modificar el producto: ' . $e);
            return [
                "respuesta" => false,
                "mensaje" => "Se produjo un error al modificar el producto"
            ];
        }

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
    //DESUSO
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
    //DESUSO
    public function crearVariante(producto $producto, varianteRequest $request){
        $varianteNueva = variante::create($request->variante);
        return [
            "respuesta" => true,
            "mensaje" => "Se ha agregado la variante con éxito",
            "variante" => [$varianteNueva]
        ];
    }
    //DESUSO
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
    public function borrar($id){
        $producto = Producto::findOrFail($id);
        $producto->fechaBorrado = now();
        $producto->save();
        return response()->json(['success' => true]);
    }
    public function importar(Request $request){
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        Excel::import(new CatalogoProductoImport, $request->file('file'));

        return back()->with('success', 'Usuarios importados correctamente.');
    }
    public function exportar(){
        $nombresPrecios = DB::table('precios')->distinct()->pluck('nombre');
        //FALTA AGREGAR CATEGORIA, CODIGO, NOMBRE CORTO, DESCRIPCION, PRESENTACION
        $selects = [
            "p.id AS producto_id",
            'p.codigo',
            "COALESCE(c.nombre, 'SIN CATEGORÍA') AS categoria",
            "p.nombreCorto AS producto",
            'p.descripcion',
            "COALESCE(P.presentacion, 'POR DEFINIR') AS presentacion",
            "COALESCE(v.nombre, 'SIN VARIANTES') AS variante"
        ];
        $headings = [
            'NÚMERO',
            'CÓDIGO',
            'CATEGORÍA',
            'PRODUCTO',
            'DESCRIPCIÓN',
            'PRESENTACIÓN',
            'VARIANTE'
        ];

        foreach ($nombresPrecios as $nombre) {
            $selects[] = "MAX(CASE WHEN pr.nombre = '$nombre' THEN pr.monto END) AS `$nombre`";
            $headings[] = $nombre;
        }

        $sql = "
            SELECT " . implode(", ", $selects) . "
            FROM productos p
            LEFT JOIN categorias c ON c.id = p.categoria_id
            LEFT JOIN variantes v ON v.producto_id = p.id
            LEFT JOIN precios pr
                ON (pr.variante_id = v.id OR (v.id IS NULL AND pr.producto_id = p.id))
            GROUP BY
                p.id, p.codigo, c.nombre, p.nombreCorto, p.descripcion,
                p.presentacion, v.nombre, v.id
        ";

        $resultado = DB::select($sql);
        // Convertir stdClass a array
        $datos = array_map(function ($row) use ($headings) {
            $rowArray = (array) $row;

            foreach (array_slice($headings, 3) as $col) {
                if (!isset($rowArray[$col]) || $rowArray[$col] === null) {
                    $rowArray[$col] = '0.00';
                }
            }

            return $rowArray;
        }, $resultado);


        return Excel::download(new productoExport($datos, $headings), 'productos_con_precios.xlsx');

    }
}
