@extends('layouts.app')

@section('title','Editar marca')

@push('css')
<style>
    #descripcion {
        resize: none;
    }
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
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
    .form-label {
        font-weight: 600;
        color: #4a5568;
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
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Editar Marca</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('marcas.index')}}">Marcas</a></li>
        <li class="breadcrumb-item active">Editar</li>
    </ol>

    <div class="card shadow-sm mb-4">
        <div class="card-header card-header-custom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Editar Información de la Marca</h5>
                <a href="{{ route('marcas.index') }}" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-arrow-left me-1"></i> Regresar
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('marcas.update', $marca) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Marca</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" name="nombre" value="{{ old('nombre', $marca->caracteristica->nombre) }}" 
                           placeholder="Ingrese el nombre de la marca" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                              id="descripcion" name="descripcion" rows="3"
                              placeholder="Ingrese una descripción opcional">{{ old('descripcion', $marca->caracteristica->descripcion) }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                        <option value="1" {{ old('estado', $marca->caracteristica->estado) == 1 ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado', $marca->caracteristica->estado) == 0 ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary-custom me-md-2">
                        <i class="fas fa-save me-1"></i> Actualizar Marca
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Restablecer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // Script adicional si es necesario
</script>
@endpush