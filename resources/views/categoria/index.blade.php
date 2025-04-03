@extends('layouts.app')

@section('title','Categorías')

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
    .dropdown-menu {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #667eea;
    }
    .btn-icon {
        transition: all 0.3s ease;
    }
    .btn-icon:hover {
        transform: scale(1.1);
    }
    .table-responsive {
        overflow-x: auto;
    }
    .modal-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
</style>
@endpush

@section('content')

@include('layouts.partials.alert')
 
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Gestión de Categorías</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Categorías</li>
    </ol>

    @can('crear-categoria')
    <div class="mb-4">
        <a href="{{route('categorias.create')}}">
            <button type="button" class="btn btn-primary-custom">
                <i class="fas fa-plus-circle me-2"></i> Nueva Categoría
            </button>
        </a>
    </div>
    @endcan

    <div class="card shadow-sm">
        <div class="card-header-custom p-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Listado de Categorías</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#">Exportar a Excel</a></li>
                        <li><a class="dropdown-item" href="#">Imprimir listado</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-hover fs-6">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categorias as $categoria)
                        <tr>
                            <td class="fw-bold">{{$categoria->caracteristica->nombre}}</td>
                            <td>{{ Str::limit($categoria->caracteristica->descripcion, 50) }}</td>
                            <td>
                                @if ($categoria->caracteristica->estado == 1)
                                <span class="badge badge-active rounded-pill">Activo</span>
                                @else
                                <span class="badge badge-inactive rounded-pill">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">

                                    @can('editar-categoria')
                                    <a href="{{route('categorias.edit',['categoria'=>$categoria])}}" class="btn btn-sm btn-outline-primary btn-icon" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan

                                    @can('eliminar-categoria')
                                    @if ($categoria->caracteristica->estado == 1)
                                    <button class="btn btn-sm btn-outline-danger btn-icon" title="Desactivar" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$categoria->id}}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    @else
                                    <button class="btn btn-sm btn-outline-success btn-icon" title="Activar" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$categoria->id}}">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    @endif
                                    @endcan

                                </div>
                            </td>
                        </tr>

                        <!-- Modal de Confirmación -->
                        <div class="modal fade" id="confirmModal-{{$categoria->id}}" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmModalLabel">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Confirmar acción
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>{{ $categoria->caracteristica->estado == 1 ? '¿Está seguro que desea desactivar la categoría "' . $categoria->caracteristica->nombre . '"?' : '¿Está seguro que desea reactivar la categoría "' . $categoria->caracteristica->nombre . '"?' }}</p>
                                        <p class="small text-muted">Esta acción {{ $categoria->caracteristica->estado == 1 ? 'ocultará' : 'mostrará' }} la categoría en el sistema.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i> Cancelar
                                        </button>
                                        <form action="{{ route('categorias.destroy',['categoria'=>$categoria->id]) }}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn {{ $categoria->caracteristica->estado == 1 ? 'btn-danger' : 'btn-success' }}">
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
                placeholder: "Buscar categoría...",
                searchTitle: "Buscar en la tabla",
                perPage: "registros por página",
                noRows: "No se encontraron categorías",
                info: "Mostrando {start} a {end} de {rows} categorías",
            },
            columns: [
                { select: 0, sort: "asc" } // Ordenar por nombre ascendente por defecto
            ]
        });
    });
</script>
@endpush