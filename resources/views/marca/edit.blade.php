@extends('layouts.app')

@section('title','Editar marca')

@push('css')
<style>
    #descripcion {
        resize: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Editar Marca</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('marcas.index')}}">Marca</a></li>
        <li class="breadcrumb-item active">Editar Marca</li>
    </ol>

    <div class="card">
    <form action="{{ route('marcas.update', $marca) }}" method="POST">
    @csrf
    @method('PUT')
    
    <!-- Campos de la marca -->
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $marca->caracteristica->nombre }}" required>
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea class="form-control" id="descripcion" name="descripcion">{{ $marca->caracteristica->descripcion }}</textarea>
    </div>

    <!-- Campo de estado -->
    <div class="mb-3">
        <label for="estado" class="form-label">Estado</label>
        <select class="form-control" id="estado" name="estado" required>
            <option value="1" {{ $marca->caracteristica->estado == 1 ? 'selected' : '' }}>Activo</option>
            <option value="0" {{ $marca->caracteristica->estado == 0 ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Actualizar</button>
</form>

    </div>

</div>
@endsection

@push('js')

@endpush