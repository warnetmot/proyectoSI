@extends('layouts.app')

@section('title','Crear Presentación')

@push('css')
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
    .btn-secondary-custom {
        background: #6c757d;
        border: none;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-secondary-custom:hover {
        background: #5a6268;
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
    h1 {
        color: #4a5568;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
    #descripcion {
        resize: vertical;
        min-height: 100px;
    }
    .form-label {
        font-weight: 500;
        color: #4a5568;
    }
    .card {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    .invalid-feedback {
        display: block;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Nueva Presentación</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('presentaciones.index')}}">Presentaciones</a></li>
        <li class="breadcrumb-item active">Crear presentación</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header-custom p-3">
            <h5 class="mb-0"><i class="fas fa-box-open me-2"></i>Información de la Presentación</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('presentaciones.store') }}" method="post">
                @csrf
                <div class="row g-3">

                    <!-- Campo Nombre -->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" 
                               class="form-control @error('nombre') is-invalid @enderror" 
                               value="{{ old('nombre') }}"
                               placeholder="Ej: Botella, Caja, Bolsa, etc.">
                        @error('nombre')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Campo Descripción -->
                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" rows="3" 
                                  class="form-control @error('descripcion') is-invalid @enderror"
                                  placeholder="Describa las características de esta presentación">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                        @enderror
                    </div>

                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('presentaciones.index') }}" class="btn btn-secondary-custom">
                        <i class="fas fa-arrow-left me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fas fa-save me-1"></i> Guardar Presentación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // Script para mejorar la experiencia del usuario
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-enfoque en el campo nombre al cargar la página
        document.getElementById('nombre').focus();
        
        // Validación en tiempo real
        const nombreInput = document.getElementById('nombre');
        nombreInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                this.classList.remove('is-invalid');
            }
        });
    });
</script>
@endpush