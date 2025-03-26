@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Compra</h2>

    <form action="{{ route('compras.update', $compra->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="fecha_hora" class="form-label">Fecha y Hora</label>
            <input type="datetime-local" name="fecha_hora" class="form-control" value="{{ old('fecha_hora', $compra->fecha_hora->format('Y-m-d\TH:i')) }}">
        </div>

        <div class="mb-3">
            <label for="impuesto" class="form-label">Impuesto</label>
            <input type="number" name="impuesto" class="form-control" value="{{ old('impuesto', $compra->impuesto) }}" step="0.01">
        </div>

        <div class="mb-3">
            <label for="numero_comprobante" class="form-label">Número de Comprobante</label>
            <input type="text" name="numero_comprobante" class="form-control" value="{{ old('numero_comprobante', $compra->numero_comprobante) }}">
        </div>

        <div class="mb-3">
            <label for="comprobante_id" class="form-label">Comprobante</label>
            <select name="comprobante_id" class="form-control">
                @foreach ($comprobantes as $comprobante)
                    <option value="{{ $comprobante->id }}" {{ $compra->comprobante_id == $comprobante->id ? 'selected' : '' }}>
                        {{ $comprobante->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="proveedore_id" class="form-label">Proveedor</label>
            <select name="proveedore_id" class="form-control">
                @foreach ($proveedores as $proveedore)
                    <option value="{{ $proveedore->id }}" {{ $compra->proveedore_id == $proveedore->id ? 'selected' : '' }}>
                        {{ $proveedore->persona->nombre }} {{ $proveedore->persona->apellido }}
                    </option>
                @endforeach
            </select>
        </div>

        <hr>

        <h4>Productos</h4>
        <div id="productos-container">
            @foreach ($compra->productos as $producto)
                <div class="producto-row">
                    <div class="mb-3">
                        <label for="producto_{{ $producto->id }}" class="form-label">Producto</label>
                        <select name="arrayidproducto[]" class="form-control">
                            @foreach ($productos as $prod)
                                <option value="{{ $prod->id }}" {{ $prod->id == $producto->id ? 'selected' : '' }}>
                                    {{ $prod->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="cantidad_{{ $producto->id }}" class="form-label">Cantidad</label>
                        <input type="number" name="arraycantidad[]" class="form-control" value="{{ $producto->pivot->cantidad }}" step="1" min="1">
                    </div>

                    <div class="mb-3">
                        <label for="precio_compra_{{ $producto->id }}" class="form-label">Precio de Compra</label>
                        <input type="number" name="arraypreciocompra[]" class="form-control" value="{{ $producto->pivot->precio_compra }}" step="0.01">
                    </div>

                    <div class="mb-3">
                        <label for="precio_venta_{{ $producto->id }}" class="form-label">Precio de Venta</label>
                        <input type="number" name="arrayprecioventa[]" class="form-control" value="{{ $producto->pivot->precio_venta }}" step="0.01">
                    </div>

                    <hr>
                </div>
            @endforeach
        </div>

        <div class="mb-3">
            <button type="button" id="add-product" class="btn btn-secondary">Añadir Producto</button>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Actualizar Compra</button>
        </div>

    </form>
</div>

@push('scripts')
<script>
    document.getElementById('add-product').addEventListener('click', function() {
        let container = document.getElementById('productos-container');
        let newProductRow = `
            <div class="producto-row">
                <div class="mb-3">
                    <label for="producto" class="form-label">Producto</label>
                    <select name="arrayidproducto[]" class="form-control">
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" name="arraycantidad[]" class="form-control" step="1" min="1">
                </div>

                <div class="mb-3">
                    <label for="precio_compra" class="form-label">Precio de Compra</label>
                    <input type="number" name="arraypreciocompra[]" class="form-control" step="0.01">
                </div>

                <div class="mb-3">
                    <label for="precio_venta" class="form-label">Precio de Venta</label>
                    <input type="number" name="arrayprecioventa[]" class="form-control" step="0.01">
                </div>

                <hr>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newProductRow);
    });
</script>
@endpush

@endsection
