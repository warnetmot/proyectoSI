@extends('layouts.app')

@section('title','Productos')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Consistent styling with your brand management */
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
    .badge-active {
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
    }
    .badge-inactive {
        background: linear-gradient(135deg, #f56565, #e53e3e);
        color: white;
    }
    .action-buttons {
        min-width: 120px;
    }
    .category-badge {
        margin: 2px;
        padding: 4px 8px;
        font-size: 0.75rem;
    }
    .img-thumbnail {
        max-width: 70px;
        max-height: 70px;
        transition: transform 0.3s ease;
    }
    .img-thumbnail:hover {
        transform: scale(1.5);
        z-index: 10;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 2px;
    }
</style>
@endpush

@section('content')

@include('layouts.partials.alert')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Gestión de Productos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Productos</li>
    </ol>

    @can('crear-producto')
    <div class="mb-4">
        <a href="{{route('productos.create')}}">
            <button type="button" class="btn btn-primary-custom">
                <i class="fas fa-plus-circle me-2"></i> Nuevo Producto
            </button>
        </a>
    </div>
    @endcan

    <div class="card shadow-sm">
        <div class="card-header card-header-custom p-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Listado de Productos</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#">Activos</a></li>
                        <li><a class="dropdown-item" href="#">Inactivos</a></li>
                        <li><a class="dropdown-item" href="#">Todos</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Presentación</th>
                            <th>Categorías</th>
                            <th>Estado</th>
                            <th class="action-buttons">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $item)
                        <tr>
                            <td class="fw-bold">{{ $item->codigo }}</td>
                            <td class="align-middle">
                                @if($item->img_path)
                                    @php
                                        $imagePath = Storage::disk('public')->exists('productos/'.$item->img_path) 
                                            ? asset('storage/productos/'.$item->img_path)
                                            : asset('img/default-product.png');
                                    @endphp
                                    <img src="{{ $imagePath }}" 
                                        alt="{{ $item->nombre }}"
                                        class="img-thumbnail"
                                        style="max-width: 70px; max-height: 70px;">
                                @else
                                    <span class="text-muted">Sin imagen</span>
                                @endif
                            </td>

                            <td>{{ $item->nombre }}</td>
                            <td>{{ $item->marca->caracteristica->nombre }}</td>
                            <td>{{ $item->presentacione->caracteristica->nombre }}</td>
                            <td>
                                @foreach ($item->categorias as $category)
                                <span class="badge category-badge rounded-pill bg-secondary">
                                    {{ $category->caracteristica->nombre }}
                                </span>
                                @endforeach
                            </td>
                            <td>
                                @if ($item->estado == 1)
                                <span class="badge badge-active rounded-pill">Activo</span>
                                @else
                                <span class="badge badge-inactive rounded-pill">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    @can('editar-producto')
                                    <a href="{{ route('productos.edit', $item->id) }}" 
                                       class="btn btn-sm btn-outline-primary btn-icon" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan

                                    @can('eliminar-producto')
                                    <form action="{{ route('productos.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger btn-icon" 
                                                title="Eliminar"
                                                onclick="return confirmDelete()">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
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
        // Initialize DataTable
        const dataTable = new simpleDatatables.DataTable("#datatablesSimple", {
            perPage: 10,
            labels: {
                placeholder: "Buscar productos...",
                searchTitle: "Buscar en la tabla",
                perPage: "registros por página",
                noRows: "No se encontraron productos",
                info: "Mostrando {start} a {end} de {rows} productos",
            }
        });

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // SweetAlert for delete confirmation
        window.confirmDelete = function() {
            return Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                return result.isConfirmed;
            });
        };
    });
</script>
@endpush