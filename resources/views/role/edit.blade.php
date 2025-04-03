@extends('layouts.app')

@section('title','Editar rol')

@push('css')
<style>
    /* New interface styles - added without modifying existing functionality */
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
    .info-note {
        background-color: #f8f9fa;
        border-left: 4px solid #667eea;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    .permissions-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }
    .permission-item {
        background: white;
        border-radius: 0.375rem;
        padding: 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
    }
    .permission-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Editar Rol</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('roles.index')}}">Roles</a></li>
        <li class="breadcrumb-item active">Editar rol</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header card-header-custom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-user-tag me-2"></i>Editar Rol: {{ $role->name }}</h5>
                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-arrow-left me-1"></i> Regresar
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="info-note">
                <i class="fas fa-info-circle me-2"></i> Los roles son un conjunto de permisos
            </div>

            <form action="{{ route('roles.update',['role'=>$role]) }}" method="post">
                @method('PATCH')
                @csrf
                
                <!---Nombre de rol---->
                <div class="row mb-4 align-items-center">
                    <label for="name" class="col-md-2 col-form-label">Nombre del rol:</label>
                    <div class="col-md-6">
                        <input type="text" name="name" id="name" class="form-control" value="{{old('name',$role->name)}}">
                        @error('name')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>

                <!---Permisos---->
                <div class="mb-4">
                    <h6 class="mb-3">Permisos para el rol:</h6>
                    <div class="permissions-container">
                        @foreach ($permisos as $item)
                        <div class="permission-item">
                            <div class="form-check">
                                <input 
                                    type="checkbox" 
                                    name="permission[]" 
                                    id="permission-{{$item->id}}" 
                                    class="form-check-input" 
                                    value="{{$item->id}}"
                                    {{ in_array($item->id, $role->permissions->pluck('id')->toArray()) ? 'checked' : '' }}
                                >
                                <label for="permission-{{$item->id}}" class="form-check-label">{{$item->name}}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('permission')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary-custom me-2">
                        <i class="fas fa-save me-1"></i> Actualizar
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Reiniciar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // Additional JavaScript can be added here if needed
</script>
@endpush