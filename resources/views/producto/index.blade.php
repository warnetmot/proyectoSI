@extends('layouts.app')

@section('title','Productos')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

@include('layouts.partials.alert')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Productos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Productos</li>
    </ol>

    @can('crear-producto')
    <div class="mb-4 text-center">
        <a href="{{route('productos.create')}}">
            <button type="button" class="btn btn-primary">Añadir nuevo registro</button>
        </a>
    </div>
    @endcan

    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla productos
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped fs-6">
                <thead>
                    <tr class="text-center">
                        <th>Código</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Presentación</th>
                        <th>Categorías</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $item)
                    <tr class="text-center align-middle">
                        <td class="align-middle">{{ $item->codigo }}</td>
                        <td class="align-middle">
                            @if ($item->img_path && file_exists(storage_path('app/public/productos/' . $item->img_path)))
                            <img src="{{ asset('storage/productos/'.$item->img_path) }}"
                                alt="{{ $item->nombre }}"
                                class="img-thumbnail"
                                style="max-width: 70px; max-height: 70px; display: block; margin: auto;">
                            @else
                            <span class="text-muted">Sin imagen</span>
                            @endif
                        </td>

                        <td class="align-middle">{{ $item->nombre }}</td>
                        <td class="align-middle">{{ $item->marca->caracteristica->nombre }}</td>
                        <td class="align-middle">{{ $item->presentacione->caracteristica->nombre }}</td>
                        <td class="align-middle">
                            @foreach ($item->categorias as $category)
                            <div class="container" style="font-size: small;">
                                <div class="row justify-content-center">
                                    <span class="m-1 rounded-pill p-1 bg-secondary text-white text-center">
                                        {{ $category->caracteristica->nombre }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </td>
                        <td class="align-middle">
                            @if ($item->estado == 1)
                            <span class="badge rounded-pill text-bg-success">activo</span>
                            @else
                            <span class="badge rounded-pill text-bg-danger">eliminado</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            <div class="d-flex justify-content-center">
                                
                                <!-- Botón para editar -->
                                <a href="{{ route('productos.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Botón para eliminar -->
                                <form action="{{ route('productos.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Estás segura de que deseas eliminar este producto?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
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
@endpush
