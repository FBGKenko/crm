@extends('Pages.plantilla')

@section('tittle')
    Pedidos — DataTable
@endsection


@section('cuerpo')
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<div class="container py-4">
    <div class="d-flex justify-content-between">
        <h2 class="h5 mb-3">Pedidos</h2>
        <a href="{{route('pedidos.vistaCrear')}}" class="btn btn-primary btn-sm">Nuevo Pedido</a>
    </div>
  <table id="pedidosTable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Número de Pedido</th>
        <th>Nombre Cliente</th>
        <th>Porcentaje del Pedido</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($pedidos as $pedido)
            <tr>
                <td>{{ $pedido->id }}</td>
                <td>{{ $pedido->folio }}</td>
                <td>{{ $pedido->nombre_completo }}</td>
                <td>{{ $pedido->porcentaje_avance }}%</td>
                <td>
                    <a href="{{route('pedidos.vistaVer', $pedido->id)}}" class="btn btn-primary btn-sm me-1">Ver Pedido</a>
                </td>
            </tr>
        @endforeach
    </tbody>
  </table>
</div>
@endsection

@section('scripts')

<!-- jQuery, Bootstrap y DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#pedidosTable').DataTable({
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            }
        }
    });
});
</script>
@endsection
