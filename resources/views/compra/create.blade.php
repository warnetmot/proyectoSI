@extends('layouts.app')

@section('title','Realizar compra')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Estilos personalizados con la paleta de colores del login */
    body {
        background-color: #f8f9fa;
    }
    .bg-primary-custom {
        background: linear-gradient(135deg, #667eea, #764ba2) !important;
        color: white;
    }
    .bg-success-custom {
        background: linear-gradient(135deg, #48bb78, #38a169) !important;
        color: white;
    }
    .border-primary-custom {
        border-color: #667eea !important;
    }
    .border-success-custom {
        border-color: #48bb78 !important;
    }
    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        color: white;
    }
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #5a6fd1, #67418f);
        color: white;
    }
    .btn-success-custom {
        background: linear-gradient(135deg, #48bb78, #38a169);
        border: none;
        color: white;
    }
    .btn-success-custom:hover {
        background: linear-gradient(135deg, #3fa56b, #2d8555);
        color: white;
    }
    .table thead {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    .breadcrumb {
        background-color: transparent;
        padding: 0.75rem 1rem;
    }
    .breadcrumb-item a {
        color: #667eea;
        text-decoration: none;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
    .selectpicker:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
    .modal-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    .card-header-custom {
        border-radius: 5px 5px 0 0 !important;
        padding: 0.75rem 1rem;
    }
    .card-body-custom {
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 5px 5px;
    }
    .btn-danger-custom {
        background: linear-gradient(135deg, #f56565, #e53e3e);
        border: none;
        color: white;
    }
    .btn-danger-custom:hover {
        background: linear-gradient(135deg, #e04f4f, #d12e2e);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center" style="color: #4a5568;">Crear Compra</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('compras.index')}}">Compras</a></li>
        <li class="breadcrumb-item active">Crear Compra</li>
    </ol>
</div>

<form action="{{ route('compras.store') }}" method="post">
    @csrf

    <div class="container-lg mt-4">
        <div class="row gy-4">
            <!------Compra producto---->
            <div class="col-xl-8">
                <div class="card-header-custom bg-primary-custom p-2 text-center">
                    <h5 class="mb-0">Detalles de la compra</h5>
                </div>
                <div class="card-body-custom p-3">
                    <div class="row">
                        <!-----Producto---->
                        <div class="col-12 mb-4">
                            <select name="producto_id" id="producto_id" class="form-control selectpicker" data-live-search="true" data-size="1" title="Busque un producto aquí">
                                @foreach ($productos as $item)
                                <option value="{{$item->id}}">{{$item->codigo.' '.$item->nombre}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-----Cantidad---->
                        <div class="col-sm-4 mb-2">
                            <label for="cantidad" class="form-label">Cantidad:</label>
                            <input type="number" name="cantidad" id="cantidad" class="form-control">
                        </div>

                        <!-----Precio de compra---->
                        <div class="col-sm-4 mb-2">
                            <label for="precio_compra" class="form-label">Precio de compra:</label>
                            <input type="number" name="precio_compra" id="precio_compra" class="form-control" step="0.1">
                        </div>

                        <!-----Precio de venta---->
                        <div class="col-sm-4 mb-2">
                            <label for="precio_venta" class="form-label">Precio de venta:</label>
                            <input type="number" name="precio_venta" id="precio_venta" class="form-control" step="0.1">
                        </div>

                        <!-----botón para agregar--->
                        <div class="col-12 mb-4 mt-2 text-end">
                            <button id="btn_agregar" class="btn btn-primary-custom" type="button">Agregar</button>
                        </div>

                        <!-----Tabla para el detalle de la compra--->
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tabla_detalle" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio compra</th>
                                            <th>Precio venta</th>
                                            <th>Subtotal</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Filas se agregarán dinámicamente -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th colspan="4">Sumas</th>
                                            <th colspan="2"><span id="sumas">0</span></th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th colspan="4">Impuesto %</th>
                                            <th colspan="2"><span id="iva">0</span></th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th colspan="4">Total</th>
                                            <th colspan="2"> <input type="hidden" name="total" value="0" id="inputTotal"> <span id="total">0</span></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!--Boton para cancelar compra-->
                        <div class="col-12 mt-2 text-end">
                            <button id="cancelar" type="button" class="btn btn-danger-custom" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="fas fa-times-circle me-1"></i> Cancelar compra
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-----Compra---->
            <div class="col-xl-4">
                <div class="card-header-custom bg-success-custom p-2 text-center">
                    <h5 class="mb-0">Datos generales</h5>
                </div>
                <div class="card-body-custom p-3">
                    <div class="row">
                        <!--Proveedor-->
                        <div class="col-12 mb-2">
                            <label for="proveedore_id" class="form-label">Proveedor:</label>
                            <select name="proveedore_id" id="proveedore_id" class="form-control selectpicker show-tick" data-live-search="true" title="Selecciona" data-size='2'>
                                @foreach ($proveedores as $item)
                                <option value="{{$item->id}}">{{$item->persona->razon_social}}</option>
                                @endforeach
                            </select>
                            @error('proveedore_id')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <!--Tipo de comprobante-->
                        <div class="col-12 mb-2">
                            <label for="comprobante_id" class="form-label">Comprobante:</label>
                            <select name="comprobante_id" id="comprobante_id" class="form-control selectpicker" title="Selecciona">
                                @foreach ($comprobantes as $item)
                                <option value="{{$item->id}}">{{$item->tipo_comprobante}}</option>
                                @endforeach
                            </select>
                            @error('comprobante_id')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <!--Numero de comprobante-->
                        <div class="col-12 mb-2">
                            <label for="numero_comprobante" class="form-label">Numero de comprobante:</label>
                            <input required type="text" name="numero_comprobante" id="numero_comprobante" class="form-control">
                            @error('numero_comprobante')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <!--Impuesto---->
                        <div class="col-sm-6 mb-2">
                            <label for="impuesto" class="form-label">Impuesto (%):</label>
                            <input type="number" name="impuesto" id="impuesto" class="form-control" step="0.1" value="16">
                            @error('impuesto')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <!--Fecha--->
                        <div class="col-sm-6 mb-2">
                            <label for="fecha" class="form-label">Fecha:</label>
                            <input readonly type="date" name="fecha" id="fecha" class="form-control" value="<?php echo date("Y-m-d") ?>">
                            <?php
                            use Carbon\Carbon;
                            $fecha_hora = Carbon::now()->toDateTimeString();
                            ?>
                            <input type="hidden" name="fecha_hora" value="{{$fecha_hora}}">
                        </div>

                        <!--Botones--->
                        <div class="col-12 mt-4 text-center">
                            <button type="submit" class="btn btn-success-custom px-4" id="guardar">
                                <i class="fas fa-check-circle me-1"></i> Realizar compra
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para cancelar la compra -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Advertencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Seguro que quieres cancelar la compra?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btnCancelarCompra" type="button" class="btn btn-danger-custom" data-bs-dismiss="modal">Confirmar</button>
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
        $('#btn_agregar').click(function() {
            agregarProducto();
        });

        $('#btnCancelarCompra').click(function() {
            cancelarCompra();
        });

        disableButtons();

        // Configuración inicial del impuesto
        $('#impuesto').val(impuesto);
    });

    let cont = 0;
    let subtotal = [];
    let sumas = 0;
    let iva = 0;
    let total = 0;
    const impuesto = 16; // Valor por defecto del impuesto

    function cancelarCompra() {
        $('#tabla_detalle tbody').empty();
        cont = 0;
        subtotal = [];
        sumas = 0;
        iva = 0;
        total = 0;

        $('#sumas').html(sumas.toFixed(2));
        $('#iva').html(iva.toFixed(2));
        $('#total').html(total.toFixed(2));
        $('#impuesto').val(impuesto);
        $('#inputTotal').val(total);

        limpiarCampos();
        disableButtons();
    }

    function disableButtons() {
        if (total == 0) {
            $('#guardar').hide();
            $('#cancelar').hide();
        } else {
            $('#guardar').show();
            $('#cancelar').show();
        }
    }

    function agregarProducto() {
        let idProducto = $('#producto_id').val();
        let nameProducto = ($('#producto_id option:selected').text()).split(' ')[1];
        let cantidad = $('#cantidad').val();
        let precioCompra = $('#precio_compra').val();
        let precioVenta = $('#precio_venta').val();
        let currentImpuesto = parseFloat($('#impuesto').val()) || impuesto;

        if (nameProducto != '' && nameProducto != undefined && cantidad != '' && precioCompra != '' && precioVenta != '') {
            if (parseInt(cantidad) > 0 && (cantidad % 1 == 0) && parseFloat(precioCompra) > 0 && parseFloat(precioVenta) > 0) {
                if (parseFloat(precioVenta) > parseFloat(precioCompra)) {
                    subtotal[cont] = cantidad * precioCompra;
                    sumas += subtotal[cont];
                    iva = sumas * (currentImpuesto / 100);
                    total = sumas + iva;

                    let fila = '<tr id="fila' + cont + '">' +
                        '<th>' + (cont + 1) + '</th>' +
                        '<td><input type="hidden" name="arrayidproducto[]" value="' + idProducto + '">' + nameProducto + '</td>' +
                        '<td><input type="hidden" name="arraycantidad[]" value="' + cantidad + '">' + cantidad + '</td>' +
                        '<td><input type="hidden" name="arraypreciocompra[]" value="' + precioCompra + '">' + parseFloat(precioCompra).toFixed(2) + '</td>' +
                        '<td><input type="hidden" name="arrayprecioventa[]" value="' + precioVenta + '">' + parseFloat(precioVenta).toFixed(2) + '</td>' +
                        '<td>' + subtotal[cont].toFixed(2) + '</td>' +
                        '<td><button class="btn btn-sm btn-danger-custom" type="button" onClick="eliminarProducto(' + cont + ')"><i class="fa-solid fa-trash"></i></button></td>' +
                        '</tr>';

                    $('#tabla_detalle tbody').append(fila);
                    limpiarCampos();
                    cont++;
                    disableButtons();

                    $('#sumas').html(sumas.toFixed(2));
                    $('#iva').html(iva.toFixed(2));
                    $('#total').html(total.toFixed(2));
                    $('#inputTotal').val(total.toFixed(2));
                } else {
                    showModal('El precio de venta debe ser mayor al precio de compra');
                }
            } else {
                showModal('Valores incorrectos. La cantidad debe ser entera y los precios mayores a cero');
            }
        } else {
            showModal('Complete todos los campos requeridos');
        }
    }

    function eliminarProducto(indice) {
        sumas -= subtotal[indice];
        let currentImpuesto = parseFloat($('#impuesto').val()) || impuesto;
        iva = sumas * (currentImpuesto / 100);
        total = sumas + iva;

        $('#sumas').html(sumas.toFixed(2));
        $('#iva').html(iva.toFixed(2));
        $('#total').html(total.toFixed(2));
        $('#inputTotal').val(total.toFixed(2));

        $('#fila' + indice).remove();
        disableButtons();
    }

    function limpiarCampos() {
        $('#cantidad').val('');
        $('#precio_compra').val('');
        $('#precio_venta').val('');
        $('#producto_id').focus();
    }

    function showModal(message, icon = 'error') {
        Swal.fire({
            icon: icon,
            title: 'Advertencia',
            text: message,
            confirmButtonColor: '#667eea'
        });
    }
</script>
@endpush