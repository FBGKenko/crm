@extends('Pages.plantilla')

@section('tittle')
Catálogo
@endsection

@section('cuerpo')
    <br>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Catálogo</h1>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-end">
                    <a href="" target="_blank" class="me-3">
                        <button class="btn btn-primary">Importar de Excel</button>
                    </a>
                    <a href="" target="_blank" class="me-3">
                        <button class="btn btn-secondary">Exportar a Excel</button>
                    </a>
                    <a href="{{route('catalogo.agregar')}}">
                        <button class="btn btn-success">Agregar Producto</button>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="tablaProductos" class="wrap table table-striped" style="width: 100%;">
                    <thead>
                        <th>Id</th>
                        <th>Categoria</th>
                        <th>Nombre</th>
                        <th>No. Variantes</th>
                        <th>Opciones:</th>
                    </thead>
                    <tbody>
                        @foreach ($listaProductos as $producto)
                            <tr>
                                <td>{{$producto->id}}</td>
                                <td>{{$producto->categorias->nombre}}</td>
                                <td>{{$producto->nombreCorto}}</td>
                                <td>{{$producto->conteoVariantes}}</td>
                                <td>
                                    <a href="" class="btn btn-primary">Variantes</a>
                                    <a href="" class="btn btn-primary">Precios</a>
                                    <a href="{{route('catalogo.modificar', $producto->id)}}" class="btn btn-secondary">modificar</a>
                                    <a href="" class="btn btn-danger">Eliminar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="//cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css"></script>
<script text="text/javascript">

    $(document).ready(function () {
        $('#tablaProductos').DataTable({
            searching: true,
            paging: true,
            pageLength: 10,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
            },
            order: [],
            scrollX: true,
            scrollY: '450px',
            "processing": true,
        });
    });


</script>
@endsection
