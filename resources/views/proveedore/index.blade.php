@extends('layouts.app')

@section('title','Proveedores')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Consistent styling with your system */
    .card-header-custom {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 0.375rem 0.375rem 0 0 !important;
    }
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
        background: linear-gradient(135deg, #f6ad55, #ed8936);
        color: white;
    }
    .dropdown-menu {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #667eea;
    }
    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 2px;
        transition: all 0.3s ease;
    }
    .btn-icon:hover {
        transform: scale(1.1);
    }
    .table-responsive {
        overflow-x: auto;
    }
    .action-buttons {
        min-width: 120px;
    }
    .document-info {
        font-size: 0.875rem;
    }
    .document-type {
        font-weight: 600;
    }
    .document-number {
        color: #6c757d;
    }
    .vr {
        height: 24px;
        opacity: 0.5;
    }
    .toggle-btn {
        font-size: 1.25rem;
    }
</style>
@endpush

@section('content')

@include('layouts.partials.alert')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Gestión de Proveedores</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Proveedores</li>
    </ol>

    @can('crear-proveedore')
    <div class="mb-4">
        <a href="{{route('proveedores.create')}}">
            <button type="button" class="btn btn-primary-custom">
                <i class="fas fa-plus-circle me-2"></i> Nuevo Proveedor
            </button>
        </a>
    </div>
    @endcan

    <div class="card shadow-sm">
        <div class="card-header card-header-custom p-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Listado de Proveedores</h5>
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
                <table id="datatablesSimple" class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Documento</th>
                            <th>Tipo de persona</th>
                            <th>Estado</th>
                            <th class="action-buttons">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proveedores as $item)
                        <tr>
                            <td>{{ $item->persona->razon_social }}</td>
                            <td>{{ $item->persona->direccion }}</td>
                            <td>
                                <div class="document-info">
                                    <div class="document-type">{{ $item->persona->documento->tipo_documento }}</div>
                                    <div class="document-number">{{ $item->persona->numero_documento }}</div>
                                </div>
                            </td>
                            <td>{{ $item->persona->tipo_persona }}</td>
                            <td>
                                @if ($item->persona->estado == 1)
                                <span class="badge badge-active rounded-pill">Activo</span>
                                @else
                                <span class="badge badge-inactive rounded-pill">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <div>
                                        @can('editar-proveedore')
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary btn-icon" 
                                                    data-bs-toggle="dropdown" 
                                                    aria-expanded="false"
                                                    title="Opciones">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{route('proveedores.edit',['proveedore'=>$item])}}">
                                                        <i class="fas fa-edit me-2"></i>Editar
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        @endcan
                                    </div>

                                    <div class="vr"></div>

                                    <div>
                                        <form action="{{ route('proveedores.toggle', ['proveedore' => $item->persona->id]) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-icon btn-transparent-dark" 
                                                    title="{{ $item->persona->estado == 1 ? 'Desactivar' : 'Activar' }}">
                                                @if ($item->persona->estado == 1)
                                                <i class="fas fa-toggle-on toggle-btn text-success"></i>
                                                @else
                                                <i class="fas fa-toggle-off toggle-btn text-warning"></i>
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal de Confirmación -->
                        <div class="modal fade" id="confirmModal-{{$item->id}}" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmModalLabel">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Confirmar Acción
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>{{ $item->persona->estado == 1 ? '¿Está seguro que desea desactivar este proveedor?' : '¿Está seguro que desea reactivar este proveedor?' }}</p>
                                        <p class="small text-muted">{{ $item->persona->estado == 1 ? 'El proveedor no podrá ser seleccionado para nuevas compras.' : 'El proveedor podrá volver a ser seleccionado para compras.' }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i> Cancelar
                                        </button>
                                        <form action="{{ route('proveedores.destroy',['proveedore'=>$item->persona->id]) }}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn {{ $item->persona->estado == 1 ? 'btn-danger' : 'btn-success' }}">
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
                placeholder: "Buscar proveedores...",
                searchTitle: "Buscar en la tabla",
                perPage: "registros por página",
                noRows: "No se encontraron proveedores",
                info: "Mostrando {start} a {end} de {rows} proveedores",
            },
            columns: [
                { select: 0, sort: "asc" } // Ordenar por nombre ascendente por defecto
            ]
        });
    });
</script>
@endpush