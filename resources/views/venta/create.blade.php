@extends('layouts.app')

@section('title','Realizar venta')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Estilos personalizados con la paleta de colores del sistema */
    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #5a6fd1, #67418f);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .btn-success-custom {
        background: linear-gradient(135deg, #48bb78, #38a169);
        border: none;
        color: white;
    }
    .btn-success-custom:hover {
        background: linear-gradient(135deg, #3fa56b, #2d8555);
    }
    .btn-danger-custom {
        background: linear-gradient(135deg, #f56565, #e53e3e);
        border: none;
        color: white;
    }
    .btn-danger-custom:hover {
        background: linear-gradient(135deg, #e04f4f, #d12e2e);
    }
    .card-header-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 0.375rem 0.375rem 0 0 !important;
    }
    .card-header-success {
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
        border-radius: 0.375rem 0.375rem 0 0 !important;
    }
    .breadcrumb {
        background-color: transparent;
        padding: 0.75rem 1rem;
    }
    .breadcrumb-item a {
        color: #667eea;
        text-decoration: none;
    }
    h1 {
        color: #4a5568;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
    .selectpicker:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
    .table thead {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    .modal-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    .stock-info {
        font-size: 0.85rem;
        color: #6c757d;
    }
    .invalid-feedback {
        display: block;
    } 
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Realizar Venta</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ventas.index')}}">Ventas</a></li>
        <li class="breadcrumb-item active">Realizar Venta</li>
    </ol>
</div>

<form action="{{ route('ventas.store') }}" method="post">
    @csrf
    <div class="container-lg mt-4">
        <div class="row gy-4">

            <!-- Detalles de la venta -->
            <div class="col-xl-8">
                <div class="card shadow-sm">
                    <div class="card-header-primary p-3">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Detalles de la Venta</h5>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">

                            <!-- Producto -->
                            <div class="col-12">
                                <select name="producto_id" id="producto_id" class="form-control selectpicker" data-live-search="true" data-size="1" title="Busque un producto aquí">
                                    @foreach ($productos as $item)
                                    <option value="{{$item->id}}-{{$item->stock}}-{{$item->precio_venta}}">{{$item->codigo.' '.$item->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Información del producto -->
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Stock disponible:</label>
                                        <div class="input-group">
                                            <input disabled id="stock" type="text" class="form-control"> <!-- Cambiado a id="stock" -->
                                            <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                                        </div>
                                        <small class="text-muted stock-info" id="stock-message"></small> <!-- Añadido mensaje de stock -->
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Precio unitario:</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input disabled type="number" name="precio_venta" id="precio_venta" class="form-control" step="0.1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="cantidad" class="form-label">Cantidad:</label>
                                        <input type="number" name="cantidad" id="cantidad" class="form-control" min="1">
                                    </div>
                                </div>
                            </div>

                            <!-- Descuento -->
                            <div class="col-12">
                                <label for="descuento" class="form-label">Descuento ($):</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="descuento" id="descuento" class="form-control" min="0" value="0">
                                </div>
                                <small class="text-muted">Ingrese 0 si no aplica descuento</small>
                            </div>

                            <!-- Botón agregar -->
                            <div class="col-12 text-end">
                                <button id="btn_agregar" class="btn btn-primary-custom" type="button">
                                    <i class="fas fa-plus-circle me-1"></i> Agregar Producto
                                </button>
                            </div>

                            <!-- Tabla de detalles -->
                            <div class="col-12">
                                <div class="table-responsive">
                                <table id="tabla_detalle" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Filas se agregarán dinámicamente -->
                                    </tbody>
                                    <tfoot>
                                        <!-- Tus filas de totales aquí -->
                                    </tfoot>
                                </table>
                                </div>
                            </div>
                            

                            <!-- Botón cancelar -->
                            <div class="col-12 text-end">
                                <button id="cancelar" type="button" class="btn btn-danger-custom" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                    <i class="fas fa-times-circle me-1"></i> Cancelar Venta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Datos generales -->
            <div class="col-xl-4">
                <div class="card shadow-sm">
                    <div class="card-header-success p-3">
                        <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Datos Generales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <!-- Cliente -->
                            <div class="col-12">
                                <label for="cliente_id" class="form-label">Cliente:</label>
                                <select name="cliente_id" id="cliente_id" class="form-control selectpicker show-tick" data-live-search="true" title="Seleccione un cliente" data-size='2'>
                                    @foreach ($clientes as $item)
                                    <option value="{{$item->id}}">{{$item->persona->razon_social}}</option>
                                    @endforeach
                                </select>
                                @error('cliente_id')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Comprobante -->
                            <div class="col-12">
                                <label for="comprobante_id" class="form-label">Tipo de Comprobante:</label>
                                <select name="comprobante_id" id="comprobante_id" class="form-control selectpicker" title="Seleccione un tipo">
                                    @foreach ($comprobantes as $item)
                                    <option value="{{$item->id}}">{{$item->tipo_comprobante}}</option>
                                    @endforeach
                                </select>
                                @error('comprobante_id')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Número de comprobante -->
                            <div class="col-12">
                                <label for="numero_comprobante" class="form-label">Número de Comprobante:</label>
                                <input required type="text" name="numero_comprobante" id="numero_comprobante" class="form-control" placeholder="Ingrese el número">
                                @error('numero_comprobante')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Impuesto -->
                            <div class="col-md-6">
                                <label for="impuesto" class="form-label">Impuesto (%):</label>
                                <input type="number" name="impuesto" id="impuesto" class="form-control" min="0" max="100" step="0.1" value="16">
                                @error('impuesto')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="total" class="form-label">Total:</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="text" id="total" class="form-control" readonly>
                                </div>
                                <!-- Campo oculto que se enviará con el formulario -->
                                <input type="hidden" name="total" id="inputTotal">
                            </div>

                            <!-- Fecha -->
                            <div class="col-md-6">
                                <label for="fecha" class="form-label">Fecha:</label>
                                <input readonly type="date" name="fecha" id="fecha" class="form-control" value="<?php echo date("Y-m-d") ?>">
                                <?php
                                use Carbon\Carbon;
                                $fecha_hora = Carbon::now()->toDateTimeString();
                                ?>
                                <input type="hidden" name="fecha_hora" value="{{$fecha_hora}}">
                            </div>

                            <!-- Usuario -->
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                            <!-- Botón guardar -->
                            <div class="col-12 text-center mt-3">
                                <button type="submit" class="btn btn-success-custom px-4" id="guardar">
                                    <i class="fas fa-check-circle me-2"></i> Registrar Venta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para cancelar venta -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar Cancelación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro que desea cancelar esta venta?</p>
                    <p class="text-muted small">Se eliminarán todos los productos agregados al detalle.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> No, continuar
                    </button>
                    <button id="btnCancelarVenta" type="button" class="btn btn-danger-custom" data-bs-dismiss="modal">
                        <i class="fas fa-check me-1"></i> Sí, cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
$(document).ready(function() {
    // Inicialización del selectpicker
    $('.selectpicker').selectpicker();
    
    // Variables globales
    let cont = 0;
    let subtotal = [];
    let sumas = 0;
    let iva = 0;
    let total = 0;
    let productsInCart = {};
    let productStocks = {};

    // Eventos
    $('#producto_id').on('changed.bs.select', mostrarValores);
    $('#btn_agregar').click(agregarProducto);
    $('#btnCancelarVenta').click(cancelarVenta);
    $('#impuesto').change(recalcularTotales);
    
    disableButtons();

    // Mostrar valores del producto seleccionado
    function mostrarValores() {
        let productoSeleccionado = $('#producto_id').val();
        
        if (productoSeleccionado) {
            let datos = productoSeleccionado.split('-');
            if (datos.length >= 3) {
                let id = datos[0];
                let stock = parseInt(datos[1]);
                let precio = parseFloat(datos[2]);
                
                productStocks[id] = stock;
                let stockDisponible = productsInCart[id] ? stock - productsInCart[id].cantidad : stock;
                
                $('#stock').val(stockDisponible);
                $('#precio_venta').val(precio.toFixed(2));
                $('#cantidad').val('1').attr('max', stockDisponible).focus();
                updateStockMessage(stockDisponible);
            }
        } else {
            limpiarCamposStock();
        }
    }

    function updateStockMessage(stock) {
        let message = $('#stock-message');
        message.text(stock > 10 ? 'Stock suficiente' : (stock > 0 ? 'Stock bajo' : 'Sin stock'))
               .removeClass('text-danger text-warning text-success')
               .addClass(stock > 10 ? 'text-success' : (stock > 0 ? 'text-warning' : 'text-danger'));
    }

    function limpiarCamposStock() {
        $('#stock, #precio_venta, #cantidad, #stock-message').val('').removeAttr('max');
        $('#stock-message').removeClass('text-success text-warning text-danger');
    }

    function round(num, decimales = 2) {
        return parseFloat(num.toFixed(decimales));
    }

    function showModal(message, icon = 'error') {
        Swal.fire({
            icon: icon,
            title: icon === 'error' ? 'Error' : (icon === 'warning' ? 'Advertencia' : 'Éxito'),
            text: message,
            confirmButtonColor: '#667eea'
        });
    }

    function disableButtons() {
        $('#guardar, #cancelar').toggle(total > 0);
    }

    function updateTotals(impuestoActual) {
        sumas = subtotal.reduce((a, b) => a + (b || 0), 0);
        iva = round(sumas * (impuestoActual / 100));
        total = round(sumas + iva);

        $('#sumas').text(sumas.toFixed(2));
        $('#iva').text(iva.toFixed(2));
        $('#total').val(total.toFixed(2));
        $('#inputTotal').val(total.toFixed(2));
        
        disableButtons();
    }

    function recalcularTotales() {
        updateTotals(parseFloat($('#impuesto').val()) || 0);
    }

    function limpiarCampos() {
        $('#producto_id').selectpicker('val', '');
        $('#descuento').val('0');
        limpiarCamposStock();
        $('#producto_id').focus();
    }

    function agregarProducto() {
        let productoSeleccionado = $('#producto_id').val();
        if (!productoSeleccionado) return showModal('Debe seleccionar un producto', 'error');
        
        let datos = productoSeleccionado.split('-');
        if (datos.length < 3) return showModal('Datos del producto incompletos', 'error');
        
        let [idProducto, stockTotal, precioVenta] = [datos[0], parseInt(datos[1]), parseFloat(datos[2])];
        let cantidad = parseInt($('#cantidad').val()) || 0;
        let descuento = parseFloat($('#descuento').val()) || 0;
        let impuestoActual = parseFloat($('#impuesto').val()) || 0;
        
        if (isNaN(cantidad) || cantidad <= 0) return showModal('La cantidad debe ser mayor a cero', 'error');
        
        let stockDisponible = productStocks[idProducto] - (productsInCart[idProducto]?.cantidad || 0);
        if (cantidad > stockDisponible) return showModal(`No hay suficiente stock. Disponible: ${stockDisponible} unidades`, 'warning');
        
        let subtotalItem = round(cantidad * precioVenta - descuento);
        subtotal[cont] = subtotalItem;
        
        let fila = `
        <tr id="fila${cont}" data-product-id="${idProducto}">
            <th>${cont + 1}</th>
            <td><input type="hidden" name="detalles[${cont}][producto_id]" value="${idProducto}">${$('#producto_id option:selected').text()}</td>
            <td><input type="hidden" name="detalles[${cont}][cantidad]" value="${cantidad}">${cantidad}</td>
            <td><input type="hidden" name="detalles[${cont}][precio_venta]" value="${precioVenta.toFixed(2)}">$${precioVenta.toFixed(2)}</td>
            <td><input type="hidden" name="detalles[${cont}][descuento]" value="${descuento.toFixed(2)}">$${descuento.toFixed(2)}</td>
            <td>$${subtotalItem.toFixed(2)}</td>
            <td><button class="btn btn-sm btn-danger-custom" type="button" onClick="eliminarProducto(${cont}, '${idProducto}', ${cantidad})"><i class="fas fa-trash-alt"></i></button></td>
        </tr>`;
        
        $('#tabla_detalle tbody').append(fila);
        
        productsInCart[idProducto] = {
            cantidad: (productsInCart[idProducto]?.cantidad || 0) + cantidad,
            precio: precioVenta,
            stock: stockTotal
        };
        
        updateTotals(impuestoActual);
        cont++;
        limpiarCampos();
    }

    window.eliminarProducto = function(indice, idProducto, cantidad) {
        subtotal.splice(indice, 1);
        
        if (productsInCart[idProducto]) {
            productsInCart[idProducto].cantidad -= cantidad;
            if (productsInCart[idProducto].cantidad <= 0) delete productsInCart[idProducto];
        }
        
        $(`#fila${indice}`).remove();
        $('#tabla_detalle tbody tr').each((i, row) => $(row).find('th').text(i + 1));
        
        updateTotals(parseFloat($('#impuesto').val()) || 0);
        if ($('#producto_id').val().split('-')[0] === idProducto) mostrarValores();
    };

    function cancelarVenta() {
        $('#tabla_detalle tbody').empty();
        [cont, subtotal, sumas, iva, total, productsInCart] = [0, [], 0, 0, 0, {}];
        $('#sumas, #iva').text('0.00');
        $('#total, #inputTotal').val('0');
        limpiarCampos();
        disableButtons();
    }

    $('form').on('submit', function(e) {
        if (Object.keys(productsInCart).length === 0) {
            e.preventDefault();
            return showModal('Debe agregar al menos un producto para realizar la venta', 'error');
        }
        
        if ($('#cliente_id').val() === null) {
            e.preventDefault();
            return showModal('Debe seleccionar un cliente', 'error');
        }
        
        if ($('#comprobante_id').val() === null) {
            e.preventDefault();
            return showModal('Debe seleccionar un tipo de comprobante', 'error');
        }
        
        if ($('#numero_comprobante').val().trim() === '') {
            e.preventDefault();
            return showModal('Debe ingresar un número de comprobante', 'error');
        }
        
        if (total <= 0 || isNaN(total)) {
            e.preventDefault();
            return showModal('El total de la venta no es válido', 'error');
        }
    });
});
</script>
@endpush