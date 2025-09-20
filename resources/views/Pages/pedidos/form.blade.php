@extends('Pages.plantilla')

@section('tittle')
    Realizar pedido — Catálogo
@endsection


@section('cuerpo')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Pequeños ajustes visuales */
        .product-card { cursor: pointer; transition: transform .08s ease; }
        .product-card:hover { transform: translateY(-3px); }
        .product-image { object-fit: cover; height: 110px; width: 100%; border-radius: .25rem; }
        .cart-table td, .cart-table th { vertical-align: middle; }
        .muted-small { font-size: .85rem; color: #6c757d; }
        .no-select { user-select: none; }
        #products-grid {
            max-height: 500px;   /* ajusta a tu gusto */
            overflow-y: auto;
            padding-right: .5rem; /* espacio para que no tape el scroll */
        }

        /* Scrollbar más delgado (opcional, Chrome/Edge/Safari) */
        #products-grid::-webkit-scrollbar {
            width: 6px;
        }
        #products-grid::-webkit-scrollbar-thumb {
            background-color: rgba(0,0,0,0.3);
            border-radius: 3px;
        }

        @media (max-width: 768px) {
            .product-image { height: 90px; }
        }
    </style>
<div class="container py-4">
    <div class="row g-3">
      <div class="col-12 d-flex justify-content-between align-items-center mb-2">
        <h1 class="h4 mb-0">Realizar pedido</h1>
        <div>
          <button class="btn btn-outline-secondary btn-sm" id="btn-clear-cart"><i class="bi bi-trash"></i> Vaciar carrito</button>
        </div>
      </div>

     <!-- Buscador + resultados -->
<div class="col-lg-7">
  <div class="card">
    <div class="card-body">
      <div class="mb-3">
        <label for="search" class="form-label">Buscar producto</label>
        <input id="search" class="form-control" placeholder="Escribe nombre o parte del nombre...">
      </div>

      <!-- Contenedor scroll -->
      <div id="products-grid" class="row g-3"
           style="max-height: 500px; overflow-y: auto;">
        <!-- Productos renderizados desde JS -->
      </div>

      <p id="no-results" class="text-muted mt-3 d-none">No se encontraron productos.</p>
    </div>
  </div>
</div>

      <!-- Carrito -->
      <div class="col-lg-5">
        <div class="card sticky-top" style="top:20px;">
          <div class="card-body">
            <h5 class="card-title">Carrito</h5>
            <div class="table-responsive">
              <table class="table table-sm cart-table">
                <thead>
                  <tr>
                    <th>Producto</th>
                    <th class="text-center" style="width:120px;">Cantidad</th>
                    <th class="text-end">Subtotal</th>
                    <th style="width:40px;"></th>
                  </tr>
                </thead>
                <tbody id="cart-body">
                  <!-- filas del carrito -->
                </tbody>
              </table>
            </div>

            <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
              <div>
                <div class="fw-semibold">Total</div>
                <div id="total-small" class="muted-small">0 items</div>
              </div>
              <div class="text-end">
                <div class="h5 mb-1" id="total-amount">$0.00</div>
                <small class="text-muted d-block">IVA no incluido</small>
              </div>
            </div>

            <div class="mt-3 d-grid">
              <button id="checkout" class="btn btn-primary" disabled><i class="bi bi-cart-plus"></i> Finalizar pedido</button>
            </div>

            <small class="text-muted d-block mt-2">Nota: ajusta la cantidad o elimina artículos antes de finalizar.</small>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // === Ejemplo de catálogo - reemplaza esto con tus productos desde backend (AJAX) ===
        const products = @json($catalogo);

        // Carrito: estructura { productId, qty }
        let cart = [];

        // ----------------- Utilidades -----------------
        function formatCurrency(v) {
            return v.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });
        }

        function findProduct(id) {
            return products.find(p => p.id === id);
        }

        // ----------------- Render productos -----------------
        function renderProducts(filter = "") {
            const grid = $("#products-grid");
            grid.empty();

            const q = filter.trim().toLowerCase();
            const filtered = products.filter(p => !q || p.name.toLowerCase().includes(q));
            if (!filtered.length) {
                $("#no-results").removeClass('d-none');
            } else {
                $("#no-results").addClass('d-none');
            }

            filtered.forEach(p => {
                const col = $(`
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="card product-card h-100" data-id="${p.id}">
                        <img src="${p.image}" alt="${escapeHtml(p.name)}" class="product-image card-img-top">
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title mb-1">${escapeHtml(p.name)}</h6>
                            <div class="mb-2 muted-small d-none">${p.stock > 0 ? 'Existencia: ' + p.stock : '<span class="text-danger">Agotado</span>'}</div>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                            <div class="fw-semibold">${formatCurrency(p.price)}</div>
                            <button class="btn btn-sm btn-outline-primary add-to-cart" ${p.stock === 0 ? 'disabled' : ''}><i class="bi bi-cart-plus"></i> Agregar</button>
                            </div>
                        </div>
                        </div>
                    </div>
                `);

                // clic en tarjeta también agrega
                col.find('.product-card').on('click', function(e) {
                    // si el clic es sobre el boton, botón ya maneja. Si es en tarjeta, abrimos opciones rápidas:
                    if ($(e.target).closest('.add-to-cart').length) return;
                    // agregar 1 si hay stock
                    // if (p.stock > 0) addToCart(p.id, 1);
                    if (true) addToCart(p.id, 1);
                });

                col.find('.add-to-cart').on('click', function(e){
                    e.stopPropagation();
                    addToCart(p.id, 1);
                });

                grid.append(col);
            });
        }

        // Escape básico para textos
        function escapeHtml(text) {
            return (text + '').replace(/[&<>"']/g, function(m) {
                return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[m];
            });
        }

        // ----------------- Carrito -----------------
        function addToCart(productId, qty = 1) {
            const product = findProduct(productId);
            if (!product) return;
            // if (product.stock <= 0) {
            //     alert('El producto está agotado.');
            //     return;
            // }

            const line = cart.find(c => c.productId === productId);
            if (line) {
                const newQty = Math.min(product.stock, line.qty + qty);
                // if (newQty === line.qty) {
                // // ya en max
                // flashMessage('Cantidad máxima disponible alcanzada', 'warning');
                // return;
                // }
                //line.qty = newQty;
            } else {
                cart.push({ productId, qty: Math.min(product.stock, qty) });
            }
            renderCart();
        }

        function removeFromCart(productId) {
            cart = cart.filter(c => c.productId !== productId);
            renderCart();
        }

        function updateQty(productId, newQty) {
            const product = findProduct(productId);
            if (!product) return;
            newQty = parseInt(newQty) || 0;
            if (newQty <= 0) {
                // eliminar si llega a 0
                removeFromCart(productId);
                return;
            }
            // if (newQty > product.stock) {
            //     newQty = product.stock;
            //     flashMessage('Ajustado a la existencia disponible', 'warning');
            // }
            const line = cart.find(c => c.productId === productId);
            if (line) {
                line.qty = newQty;
            } else {
                cart.push({ productId, qty: newQty });
            }
            renderCart();
        }

        function calcTotals() {
        let total = 0;
        let items = 0;
        cart.forEach(line => {
            const p = findProduct(line.productId);
            if (!p) return;
            total += p.price * line.qty;
            items += line.qty;
        });
        return { total, items };
        }

        function renderCart() {
        const body = $("#cart-body");
        body.empty();

        if (cart.length === 0) {
            body.append(`<tr><td colspan="4" class="text-center text-muted">Tu carrito está vacío</td></tr>`);
            $("#checkout").prop('disabled', true);
            $("#total-amount").text(formatCurrency(0));
            $("#total-small").text('0 items');
            return;
        }

        cart.forEach(line => {
            const p = findProduct(line.productId);
            if (!p) return;
            const subtotal = p.price * line.qty;
            const tr = $(`
            <tr data-id="${p.id}">
                <td>
                <div class="d-flex align-items-center gap-2">
                    <img src="${p.image}" alt="${escapeHtml(p.name)}" style="width:48px;height:48px;object-fit:cover;border-radius:.25rem;">
                    <div>
                    <div class="fw-semibold">${escapeHtml(p.name)}</div>
                    <div class="muted-small">${formatCurrency(p.price)}</div>
                    </div>
                </div>
                </td>
                <td class="text-center">
                <div class="input-group input-group-sm mx-auto" style="width:110px;">
                    <button class="btn btn-outline-secondary btn-decr" type="button"><i class="bi bi-dash"></i></button>
                    <input type="number" min="1" max="${p.stock}" class="form-control text-center qty-input" value="${line.qty}">
                    <button class="btn btn-outline-secondary btn-incr" type="button"><i class="bi bi-plus"></i></button>
                </div>
                <div class="muted-small mt-1 d-none">Exist: ${p.stock}</div>
                </td>
                <td class="text-end fw-semibold">${formatCurrency(subtotal)}</td>
                <td class="text-end">
                <button class="btn btn-sm btn-outline-danger remove-item" title="Eliminar"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            `);

            // events
            tr.find('.remove-item').on('click', () => removeFromCart(p.id));
            tr.find('.qty-input').on('change', function() {
                let v = parseInt($(this).val()) || 0;
                if (v > p.stock) v = p.stock;
                if (v < 1) v = 1;
                $(this).val(v);
                updateQty(p.id, v);
            });
            tr.find('.btn-incr').on('click', function(){
                let current = parseInt(tr.find('.qty-input').val()) || 0;
                updateQty(p.id, current + 1);
            });
            tr.find('.btn-decr').on('click', function(){
                let current = parseInt(tr.find('.qty-input').val()) || 0;
                updateQty(p.id, current - 1);
            });

            body.append(tr);
        });

        const totals = calcTotals();
        $("#total-amount").text(formatCurrency(totals.total));
        $("#total-small").text(`${totals.items} ${totals.items === 1 ? 'item' : 'items'}`);
        $("#checkout").prop('disabled', false);
        }

        // Mensajitos breves
        function flashMessage(msg, type = 'info') {
        // simple alert por ahora
        // puedes reemplazar con Toasts de bootstrap fácilmente
        // type: 'info' | 'warning' | 'danger' | 'success'
        console.log(`[${type}] ${msg}`);
        }

        // ----------------- Buscador (debounce simple) -----------------
        let searchTimer = null;
        $('#search').on('input', function(){
        clearTimeout(searchTimer);
        const q = $(this).val();
        searchTimer = setTimeout(() => renderProducts(q), 180);
        });

        // Vaciar carrito
        $('#btn-clear-cart').on('click', function(){
        if (!cart.length) return;
        if (!confirm('¿Deseas vaciar el carrito?')) return;
        cart = [];
        renderCart();
        });

        // Checkout (ejemplo)
        // $('#checkout').on('click', function(){
        //     const totals = calcTotals();
        //     if (cart.length === 0) {
        //         alert('El carrito está vacío.');
        //         return;
        //     }

        //     // Aquí es donde debes llamar a tu backend para procesar el pedido (AJAX).
        //     // Ejemplo de payload:
        //     const payload = {
        //         items: cart.map(line => ({ product_id: line.productId, qty: line.qty })),
        //         total: totals.total
        //     };

        //     // Reemplaza este alert por un POST AJAX a tu API
        //     alert('Pedido listo para enviar al servidor (abre consola para ver payload).');
        //     console.log('PAYLOAD', payload);

        //     // Simulación: vaciar carrito al "finalizar"
        //     cart = [];
        //     renderCart();
        // });

        // Inicialización
        $(function(){
        renderProducts();
        renderCart();
        });

        // Si quieres obtener productos via AJAX, un ejemplo (comentar / adaptar):
        /*
        $.get('/api/products', { q: '' }, function(data) {
        // esperar array de objetos { id, name, price, stock, image }
        products.length = 0;
        data.forEach(p => products.push(p));
        renderProducts();
        });
        */

    </script>
    <script>
        $('#checkout').click(function (e) {
            $.ajax({
                type: "POST",
                url: "{{route('pedidos.hacerPedido')}}",
                data: {
                    _token: "{{csrf_token()}}",
                    cart: cart
                },
                dataType: "json",
                success: function (response) {
                    if(response.status === 'success'){
                        Swal.fire({
                            title: "Éxito",
                            text: response.message,
                            icon: "success"
                        }).then((resultado) => {
                            location.href = "{{route('pedidos.index')}}"
                        })
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "Error al realizar el pedido. Verifique los campos",
                            icon: "error"
                        })
                    }
                }
            });
        });

    </script>
@endsection





