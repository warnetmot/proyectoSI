@extends('layouts.app')

@section('title','Crear rol')

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
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
    .permissions-container {
        max-height: 400px;
        overflow-y: auto;
        padding: 15px;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }
    .permission-group {
        margin-bottom: 1rem;
    }
    .permission-group-title {
        font-weight: 600;
        color: #4a5568;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 0.5rem;
        margin-bottom: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Nuevo Rol</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('roles.index')}}">Roles</a></li>
        <li class="breadcrumb-item active">Crear rol</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header-custom p-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Configuración del Rol</h5>
                <small class="text-white-50">Los roles son conjuntos de permisos</small>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="post">
                @csrf
                
                <!-- Nombre del Rol -->
                <div class="row mb-4 align-items-center">
                    <label for="name" class="col-md-2 col-form-label fw-bold">Nombre del Rol:</label>
                    <div class="col-md-6">
                        <input autocomplete="off" type="text" name="name" id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}"
                               placeholder="Ej: Administrador, Supervisor, etc.">
                        @error('name')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <!-- Permisos -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3"><i class="fas fa-key me-2"></i>Permisos para el Rol:</h6>
                    
                    @error('permission')
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                    </div>
                    @enderror
                    
                    <div class="permissions-container">
                        @php
                            // Agrupar permisos por su prefijo (antes del primer guión)
                            $groupedPermissions = [];
                            foreach ($permisos as $permission) {
                                $parts = explode('-', $permission->name);
                                $group = $parts[0];
                                $groupedPermissions[$group][] = $permission;
                            }
                        @endphp
                        
                        @foreach($groupedPermissions as $group => $permissions)
                        <div class="permission-group">
                            <div class="permission-group-title">
                                {{ ucfirst($group) }} Permissions
                            </div>
                            <div class="row">
                                @foreach($permissions as $item)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" name="permission[]" id="perm_{{ $item->id }}" 
                                               class="form-check-input" value="{{ $item->id }}"
                                               @if(old('permission') && in_array($item->id, old('permission'))) checked @endif>
                                        <label for="perm_{{ $item->id }}" class="form-check-label">
                                            {{ ucwords(str_replace('-', ' ', $item->name)) }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Botón de envío -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary me-md-2">
                        <i class="fas fa-arrow-left me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fas fa-save me-1"></i> Guardar Rol
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // Script para selección rápida de permisos
    document.addEventListener('DOMContentLoaded', function() {
        // Seleccionar/deseleccionar todos los permisos de un grupo
        document.querySelectorAll('.permission-group-title').forEach(title => {
            title.style.cursor = 'pointer';
            title.addEventListener('click', function() {
                const group = this.parentElement;
                const checkboxes = group.querySelectorAll('input[type="checkbox"]');
                const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                
                checkboxes.forEach(checkbox => {
                    checkbox.checked = !allChecked;
                });
            });
        });
    });
</script>
@endpush