@extends('layouts.app')

@section('title', 'Compras')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@push('css')
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
    .btn-warning-custom {
        background: linear-gradient(135deg, #ed8936, #dd6b20);
        border: none;
        color: white;
    }
    .btn-warning-custom:hover {
        background: linear-gradient(135deg, #e07d2b, #c85f1a);
    }
    .btn-danger-custom {
        background: linear-gradient(135deg, #f56565, #e53e3e);
        border: none;
        color: white;
    }
    .btn-danger-custom:hover {
        background: linear-gradient(135deg, #e04f4f, #d12e2e);
    }
    .card-header-custom {
        background: linear-gradient(135deg, #667eea, #764ba2);
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
    .table thead {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    .badge-comprobante {
        background-color: #e9ecef;
        color: #495057;
        font-weight: 600;
    }
    .text-currency {
        font-weight: 600;
        color: #2d8a39;
    }
    .text-warning-currency {
        font-weight: 600;
        color: #d97706;
    }
    .datetime-cell {
        min-width: 120px;
    }
    .product-list {
        max-height: 100px;
        overflow-y: auto;
    }
    .product-item {
        border-bottom: 1px solid #eee;
        padding: 2px 0;
    }
    .stock-info {
        font-weight: 500;
    }
    .stock-low {
        color: #dc3545;
    }
    .stock-medium {
        color: #fd7e14;
    }
    .stock-high {
        color: #28a745;
    }
    .modal-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    .action-buttons {
        min-width: 180px;
    }
</style>
@endpush

@section('content')

@include('layouts.partials.alert')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Historial de Compras</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Compras</li>
    </ol>

    @can('crear-compra')
    <div class="mb-4">
        <a href="{{ route('compras.create') }}">
            <button type="button" class="btn btn-primary-custom">
                <i class="fas fa-plus-circle me-2"></i> Nueva Compra
            </button>
        </a>
    </div>
    @endcan

    <div class="card shadow-sm mb-4">
        <div class="card-header-custom p-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Registro de Compras</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#">Hoy</a></li>
                        <li><a class="dropdown-item" href="#">Esta semana</a></li>
                        <li><a class="dropdown-item" href="#">Este mes</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Comprobante</th>
                            <th>Productos</th>
                            <th>Stock</th>
                            <th>Proveedor</th>
                            <th class="datetime-cell">Fecha/Hora</th>
                            <th>Cantidad</th>
                            <th>Total Compra</th>
                            <th>Total Venta</th>
                            <th>Total</th>
                            <th class="action-buttons">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($compras as $item)
                        <tr>
                            <td>
                                <span class="badge badge-comprobante mb-1">{{ $item->comprobante->tipo_comprobante }}</span>
                                <p class="small text-muted mb-0">{{ $item->numero_comprobante }}</p>
                            </td>
                            <td>
                                <div class="product-list">
                                    @foreach ($item->productos as $producto)
                                    <div class="product-item">
                                        <span class="small">{{ $producto->nombre }}</span>
                                        <span class="badge bg-secondary float-end">{{ $producto->pivot->cantidad }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div class="product-list">
                                    @foreach ($item->productos as $producto)
                                    <div class="product-item">
                                        @php
                                            $stockClass = 'stock-high';
                                            if($producto->stock < 10) {
                                                $stockClass = 'stock-low';
                                            } elseif($producto->stock < 20) {
                                                $stockClass = 'stock-medium';
                                            }
                                        @endphp
                                        <span class="small stock-info {{ $stockClass }}">{{ $producto->stock }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <p class="fw-semibold mb-1">{{ ucfirst($item->proveedore->persona->tipo_persona) }}</p>
                                <p class="small text-muted mb-0">{{ $item->proveedore->persona->razon_social }}</p>
                            </td>
                            <td class="datetime-cell">
                                <p class="small mb-1"><i class="fas fa-calendar-day me-1"></i> {{ \Carbon\Carbon::parse($item->fecha_hora)->format('d/m/Y') }}</p>
                                <p class="small mb-0"><i class="fas fa-clock me-1"></i> {{ \Carbon\Carbon::parse($item->fecha_hora)->format('H:i') }}</p>
                            </td>
                            <td>
                                {{ $item->productos->sum('pivot.cantidad') }}
                            </td>
                            <td class="text-warning-currency">
                                ${{ number_format($item->productos->sum(function($producto) {
                                    return $producto->pivot->cantidad * $producto->pivot->precio_compra;
                                }), 2) }}
                            </td>
                            <td class="text-currency">
                                ${{ number_format($item->productos->sum(function($producto) {
                                    return $producto->pivot->cantidad * $producto->pivot->precio_venta;
                                }), 2) }}
                            </td>
                            <td class="text-currency">
                                ${{ number_format($item->total, 2) }}
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    @can('mostrar-compra')
                                    <a href="{{ route('compras.show', ['compra' => $item]) }}" class="btn btn-sm btn-success-custom" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endcan

                                    @can('editar-compra')
                                    <a href="{{ route('compras.edit', $item->id) }}" class="btn btn-sm btn-warning-custom" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan

                                    @can('eliminar-compra')
                                    <button class="btn btn-sm btn-danger-custom" title="Eliminar" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>

                        <!-- Modal de confirmación-->
                        <div class="modal fade" id="confirmModal-{{$item->id}}" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmModalLabel">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Confirmar Eliminación
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Está seguro que desea eliminar esta compra?</p>
                                        <p class="small text-muted">Compra #{{$item->numero_comprobante}} - Total: ${{ number_format($item->total, 2) }}</p>
                                        <p class="small text-danger">Esta acción también afectará el stock de los productos.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i> Cancelar
                                        </button>
                                        <form action="{{ route('compras.destroy', ['compra' => $item->id]) }}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-danger-custom">
                                                <i class="fas fa-check me-1"></i> Confirmar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dataTable = new simpleDatatables.DataTable("#datatablesSimple", {
            perPage: 10,
            labels: {
                placeholder: "Buscar compras...",
                searchTitle: "Buscar en la tabla",
                perPage: "registros por página",
                noRows: "No se encontraron compras",
                info: "Mostrando {start} a {end} de {rows} compras",
            },
            columns: [
                { select: 4, sort: "desc" } // Ordenar por fecha descendente por defecto
            ]
        });
    });
</script>
@endpush