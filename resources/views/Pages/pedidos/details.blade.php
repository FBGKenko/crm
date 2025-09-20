@extends('Pages.plantilla')

@section('tittle')
    Pedidos — Vista responsiva
@endsection


@section('cuerpo')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <h2 class="h5 mb-2 mb-md-0">Tabla de pedidos</h2>
        <button id="toggleMode" class="btn btn-sm btn-outline-primary d-none">Modo: Encargado</button>
    </div>

    <!-- Detalles del pedido -->
    <div class="card mb-3 p-3">
        <div class="row g-2">
            <div class="col-12 col-md-4"><strong>Nombre Cliente:</strong> <span id="detalleCliente">Juan Pérez</span></div>
            <div class="col-12 col-md-4"><strong>Número Pedido:</strong> <span id="detalleNumero">2025-0001</span></div>
            <div class="col-12 col-md-4"><strong>Fecha Pedido:</strong> <span id="detalleFecha">06/09/2025</span></div>
        </div>
    </div>

    <!-- Tabla responsive -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
            <tr>
                <th>Imagen</th>
                <th># Pedido</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Estatus</th>
                <th>Acción / Observación</th>
            </tr>
            </thead>
            <tbody id="orders-body"></tbody>
        </table>
    </div>
</div>

<!-- Modal de rechazo -->
<div class="modal fade" id="rechazoModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="rechazoForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Motivo de rechazo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <textarea id="motivoRechazo" class="form-control" rows="3" required></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-danger">Rechazar</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const pedidos = @json($productos);

let modo = @json($modoUso); // "encargado" o "cliente"
let pedidoSeleccionado = null;

$(document).ready(function () {

});

function renderTabla() {
  const body = $("#orders-body");
  body.empty();

  pedidos.forEach(p => {
    const tr = $("<tr>");
    tr.append(`<td><img src="${p.imagen}" alt="${p.producto}" class="img-thumbnail" style="width:60px;height:60px;object-fit:cover;"></td>`);
    tr.append(`<td>${p.id}</td>`);
    tr.append(`<td>${p.producto}</td>`);
    tr.append(`<td>${p.cantidad}</td>`);

    if (modo === "encargado") {
      const select = $(`<select class="form-select form-select-sm" ${p.cliente === "autorizado" ? "disabled" : ""}>
        <option value="pendiente" ${p.estatus==="pendiente"?"selected":""}>Pendiente</option>
        <option value="surtido" ${p.estatus==="surtido"?"selected":""}>Surtido</option>
        <option value="enviado" ${p.estatus==="enviado"?"selected":""}>Enviado</option>
      </select>`);
      select.on("change", function(){
        p.estatus = $(this).val();
        if (p.estatus === "enviado") { p.cliente = "pendiente"; p.observacion = null; }
      });
      tr.append($("<td>").append(select));
      tr.append(`<td>${p.observacion ? `<span class="text-danger">Observación: ${p.observacion}</span>` : `<span class="text-muted">Cliente: ${p.cliente}</span>`}</td>`);
    } else {
      tr.append(`<td>${p.estatus}</td>`);
      const tdAccion = $("<td>");
      if (p.cliente === "pendiente" && p.estatus === "enviado") {
        const btnAutorizar = $(`<button class="btn btn-success btn-sm me-1">Autorizar</button>`);
        const btnRechazar = $(`<button class="btn btn-danger btn-sm">Rechazar</button>`);
        btnAutorizar.on("click", function(){ p.cliente = "autorizado"; renderTabla(); });
        btnRechazar.on("click", function(){ pedidoSeleccionado = p; $("#motivoRechazo").val(""); new bootstrap.Modal("#rechazoModal").show(); });
        tdAccion.append(btnAutorizar, btnRechazar);
      } else if (p.cliente === "rechazado") {
        tdAccion.html(`<span class="text-danger">Rechazado<br><small>${p.observacion || ""}</small></span>`);
      } else if (p.cliente === "autorizado") {
        tdAccion.html(`<span class="text-success">Autorizado</span>`);
      } else {
        tdAccion.html(`<span class="text-muted">Esperando envío</span>`);
      }
      tr.append(tdAccion);
    }

    body.append(tr);
  });
}

$("#rechazoForm").on("submit", function(e){
  e.preventDefault();
  if (pedidoSeleccionado) {
    pedidoSeleccionado.cliente = "rechazado";
    pedidoSeleccionado.estatus = "pendiente";
    pedidoSeleccionado.observacion = $("#motivoRechazo").val();
    renderTabla();
    bootstrap.Modal.getInstance(document.getElementById("rechazoModal")).hide();
  }
});

$("#toggleMode").on("click", function(){
    modo = (modo === "encargado") ? "cliente" : "encargado";
    $(this).text("Modo: " + (modo === "encargado" ? "Encargado" : "Cliente"));
    renderTabla();
});

renderTabla();
</script>
@endsection



