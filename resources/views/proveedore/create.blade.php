@extends('layouts.app')

@section('title','Crear proveedor')

@push('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<style>
    #box-razon-social {
        display: none;
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
    .form-control:focus, .form-select:focus {
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
    .card {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    .supplier-type-badge {
        font-size: 0.9rem;
        padding: 0.35rem 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Nuevo Proveedor</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('proveedores.index')}}">Proveedores</a></li>
        <li class="breadcrumb-item active">Nuevo</li>
    </ol>

    <div class="card shadow-sm mb-4">
        <div class="card-header card-header-custom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Registro de Proveedor</h5>
                <a href="{{ route('proveedores.index') }}" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-arrow-left me-1"></i> Regresar
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('proveedores.store') }}" method="post">
                @csrf
                
                <div class="row g-3 mb-4">
                    <!-- Tipo de persona -->
                    <div class="col-md-6">
                        <label for="tipo_persona" class="form-label">Tipo de proveedor</label>
                        <select class="form-select @error('tipo_persona') is-invalid @enderror" 
                                name="tipo_persona" id="tipo_persona">
                            <option value="" selected disabled>Seleccione una opción</option>
                            <option value="natural" {{ old('tipo_persona') == 'natural' ? 'selected' : '' }}>Persona natural</option>
                            <option value="juridica" {{ old('tipo_persona') == 'juridica' ? 'selected' : '' }}>Persona jurídica</option>
                        </select>
                        @error('tipo_persona')
                            <div class="error-message text-danger">{{ '*'.$message }}</div>
                        @enderror
                    </div>

                    <!-- Razón social (hidden by default) -->
                    <div class="col-12" id="box-razon-social">
                        <label id="label-natural" for="razon_social" class="form-label">Nombres y apellidos</label>
                        <label id="label-juridica" for="razon_social" class="form-label">Nombre de la empresa</label>
                        <input required type="text" name="razon_social" id="razon_social" 
                               class="form-control @error('razon_social') is-invalid @enderror" 
                               value="{{ old('razon_social') }}"
                               placeholder="Ingrese los datos según el tipo de proveedor">
                        @error('razon_social')
                            <div class="error-message text-danger">{{ '*'.$message }}</div>
                        @enderror
                    </div>

                    <!-- Dirección -->
                    <div class="col-12">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input required type="text" name="direccion" id="direccion" 
                               class="form-control @error('direccion') is-invalid @enderror" 
                               value="{{ old('direccion') }}"
                               placeholder="Ingrese la dirección completa">
                        @error('direccion')
                            <div class="error-message text-danger">{{ '*'.$message }}</div>
                        @enderror
                    </div>

                    <!-- Documento -->
                    <div class="col-md-6">
                        <label for="documento_id" class="form-label">Tipo de documento</label>
                        <select class="form-select @error('documento_id') is-invalid @enderror" 
                                name="documento_id" id="documento_id">
                            <option value="" selected disabled>Seleccione una opción</option>
                            @foreach ($documentos as $item)
                            <option value="{{ $item->id }}" {{ old('documento_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->tipo_documento }}
                            </option>
                            @endforeach
                        </select>
                        @error('documento_id')
                            <div class="error-message text-danger">{{ '*'.$message }}</div>
                        @enderror
                    </div>

                    <!-- Número de documento -->
                    <div class="col-md-6">
                        <label for="numero_documento" class="form-label">Número de documento</label>
                        <input required type="text" name="numero_documento" id="numero_documento" 
                               class="form-control @error('numero_documento') is-invalid @enderror" 
                               value="{{ old('numero_documento') }}"
                               placeholder="Ingrese el número de documento">
                        @error('numero_documento')
                            <div class="error-message text-danger">{{ '*'.$message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer text-center py-3">
                    <button type="submit" class="btn btn-primary-custom me-2">
                        <i class="fas fa-save me-1"></i> Guardar Proveedor
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
<script>
    $(document).ready(function() {
        // Show/hide razón social fields based on persona type
        $('#tipo_persona').on('change', function() {
            let selectValue = $(this).val();
            
            if (selectValue == 'natural') {
                $('#label-juridica').hide();
                $('#label-natural').show();
                $('#razon_social').attr('placeholder', 'Ej: Juan Pérez López');
            } else if (selectValue == 'juridica') {
                $('#label-natural').hide();
                $('#label-juridica').show();
                $('#razon_social').attr('placeholder', 'Ej: Empresa XYZ S.A.C.');
            }

            $('#box-razon-social').show();
        });

        // Trigger change event if there's already a selected value (form validation failed)
        @if(old('tipo_persona'))
            $('#tipo_persona').trigger('change');
        @endif
    });
</script>
@endpush