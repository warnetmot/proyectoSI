@extends('layouts.app')

@section('title','Crear Producto')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
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
    .form-control:focus, .bootstrap-select .dropdown-toggle:focus {
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
    .error-message {
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid rgba(0,0,0,.125);
    }
    .img-preview-container {
        margin-top: 10px;
        text-align: center;
    }
    .img-preview {
        max-width: 150px;
        max-height: 150px;
        border-radius: 0.375rem;
        border: 1px solid #dee2e6;
        display: none;
    }
    .selectpicker {
        border-radius: 0.375rem !important;
    }
    .card {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Nuevo Producto</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('productos.index')}}">Productos</a></li>
        <li class="breadcrumb-item active">Nuevo</li>
    </ol>

    <div class="card shadow-sm mb-4">
        <div class="card-header card-header-custom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Nuevo Producto</h5>
                <a href="{{ route('productos.index') }}" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-arrow-left me-1"></i> Regresar
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('productos.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                
                <div class="row g-3 mb-4">
                    <!-- Código -->
                    <div class="col-md-6">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" name="codigo" id="codigo" 
                               class="form-control @error('codigo') is-invalid @enderror" 
                               value="{{ old('codigo') }}"
                               placeholder="Ingrese el código del producto">
                        @error('codigo')
                            <div class="error-message text-danger">{{ '*'.$message }}</div>
                        @enderror
                    </div>

                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombre" 
                               class="form-control @error('nombre') is-invalid @enderror" 
                               value="{{ old('nombre') }}"
                               placeholder="Ingrese el nombre del producto">
                        @error('nombre')
                            <div class="error-message text-danger">{{ '*'.$message }}</div>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                                  class="form-control @error('descripcion') is-invalid @enderror"
                                  placeholder="Ingrese una descripción del producto">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="error-message text-danger">{{ '*'.$message }}</div>
                        @enderror
                    </div>

                    <!-- Fecha de ingreso -->
                    <div class="col-md-6">
                        <label for="fecha_vencimiento" class="form-label">Fecha de ingreso</label>
                        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento"
                               class="form-control @error('fecha_vencimiento') is-invalid @enderror"
                               value="{{ old('fecha_vencimiento') }}">
                        @error('fecha_vencimiento')
                            <div class="error-message text-danger">{{ '*'.$message }}</div>
                        @enderror
                    </div>

                    <!-- Imagen -->
                    <div class="col-md-6">
                        <label for="img_path" class="form-label">Imagen del producto</label>
                        <input type="file" name="img_path" id="img_path"
                               class="form-control @error('img_path') is-invalid @enderror"
                               accept="image/*">
                        @error('img_path')
                            <div class="error-message text-danger">{{ '*'.$message }}</div>
                        @enderror
                        
                        <div class="img-preview-container mt-2">
                            <img id="imagePreview" class="img-preview" alt="Vista previa de la imagen">
                        </div>
                    </div>

                    <!-- Marca -->
                    <div class="col-md-6">
                        <label for="marca_id" class="form-label">Marca</label>
                        <select name="marca_id" id="marca_id" 
                                class="form-control selectpicker show-tick @error('marca_id') is-invalid @enderror"
                                data-live-search="true" 
                                data-size="5"
                                title="Seleccione una marca">
                            @foreach ($marcas as $item)
                            <option value="{{ $item->id }}" {{ old('marca_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nombre }}
                            </option>
                            @endforeach
                        </select>
                        @error('marca_id')
                            <div class="error-message text-danger">{{ '*'.$message }}</div>
                        @enderror
                    </div>

                    <!-- Presentación -->
                    <div class="col-md-6">
                        <label for="presentacione_id" class="form-label">Presentación</label>
                        <select name="presentacione_id" id="presentacione_id" 
                                class="form-control selectpicker show-tick @error('presentacione_id') is-invalid @enderror"
                                data-live-search="true" 
                                data-size="5"
                                title="Seleccione una presentación">
                            @foreach ($presentaciones as $item)
                            <option value="{{ $item->id }}" {{ old('presentacione_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nombre }}
                            </option>
                            @endforeach
                        </select>
                        @error('presentacione_id')
                            <div class="error-message text-danger">{{ '*'.$message }}</div>
                        @enderror
                    </div>

                    <!-- Categorías -->
                    <div class="col-12">
                        <label for="categorias" class="form-label">Categorías</label>
                        <select name="categorias[]" id="categorias" 
                                class="form-control selectpicker show-tick @error('categorias') is-invalid @enderror"
                                data-live-search="true" 
                                data-size="5"
                                multiple
                                title="Seleccione las categorías">
                            @foreach ($categorias as $item)
                            <option value="{{ $item->id }}" {{ in_array($item->id, old('categorias', [])) ? 'selected' : '' }}>
                                {{ $item->nombre }}
                            </option>
                            @endforeach
                        </select>
                        @error('categorias')
                            <div class="error-message text-danger">{{ '*'.$message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer text-center py-3">
                    <button type="submit" class="btn btn-primary-custom me-2">
                        <i class="fas fa-save me-1"></i> Guardar Producto
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fas fa-eraser me-1"></i> Limpiar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize select pickers
        $('.selectpicker').selectpicker();
        
        // Image preview functionality
        $('#img_path').change(function() {
            const file = this.files[0];
            const preview = $('#imagePreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result).show();
                }
                reader.readAsDataURL(file);
            } else {
                preview.hide();
            }
        });
    });
</script>
@endpush