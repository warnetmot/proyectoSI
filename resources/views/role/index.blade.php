@extends('layouts.app')

@section('title','Roles')

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
    }
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #5a6fd1, #67418f);
        color: white;
    }
    .btn-warning-custom {
        background: linear-gradient(135deg, #ed8936, #dd6b20);
        border: none;
        color: white;
    }
    .btn-warning-custom:hover {
        background: linear-gradient(135deg, #e07d2b, #c85f1a);
        color: white;
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
    .table thead {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    .modal-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    h1 {
        color: #4a5568;
    }
</style>
@endpush

@section('content')

@include('layouts.partials.alert')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Roles</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Roles</li>
    </ol>

    @can('crear-role')
    <div class="mb-4">
        <a href="{{route('roles.create')}}">
            <button type="button" class="btn btn-primary-custom">
                <i class="fas fa-plus-circle me-1"></i> Añadir nuevo rol
            </button>
        </a>
    </div>
    @endcan

    <div class="card shadow-sm">
        <div class="card-header-custom p-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-table me-2"></i>Listado de Roles</h5>
            </div>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped table-hover fs-6">
                <thead>
                    <tr>
                        <th>Rol</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $item)
                    <tr>
                        <td class="align-middle">
                            <span class="badge bg-primary" style="background: linear-gradient(135deg, #667eea, #764ba2)">
                                {{$item->name}}
                            </span>
                        </td>
                        <td class="align-middle text-center">
                            <div class="btn-group" role="group">
                                @can('editar-role')
                                <form action="{{route('roles.edit',['role'=>$item])}}" method="get">
                                    <button type="submit" class="btn btn-warning-custom btn-sm mx-1">
                                        <i class="fas fa-edit me-1"></i> Editar
                                    </button>
                                </form>
                                @endcan

                                @can('eliminar-role')
                                <button type="button" class="btn btn-danger-custom btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}">
                                    <i class="fas fa-trash-alt me-1"></i> Eliminar
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>

                    <!-- Modal de confirmación-->
                    <div class="modal fade" id="confirmModal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmación</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Estás seguro que deseas eliminar el rol <strong>{{$item->name}}</strong>?</p>
                                    <p class="text-muted">Esta acción no se puede deshacer.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i> Cancelar
                                    </button>
                                    <form action="{{ route('roles.destroy',['role'=>$item->id]) }}" method="post">
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

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
<script>
    // Configuración adicional para datatables
    document.addEventListener("DOMContentLoaded", function() {
        const dataTable = new simpleDatatables.DataTable("#datatablesSimple", {
            perPage: 10,
            labels: {
                placeholder: "Buscar...",
                searchTitle: "Buscar en la tabla",
                perPage: "registros por página",
                noRows: "No se encontraron registros",
                info: "Mostrando {start} a {end} de {rows} registros",
            },
            columns: [
                { select: 0, sort: "asc" } // Ordenar la primera columna (Rol) asc por defecto
            ]
        });
    });
</script>
@endpush