@extends('layouts.app')

@section('title','Editar usuario')

@push('css')
<style>
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
    .form-text {
        color: #6c757d;
        font-size: 0.875rem;
    }
    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
    }
    .password-input-group {
        position: relative;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Editar Usuario</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index')}}">Usuarios</a></li>
        <li class="breadcrumb-item active">Editar</li>
    </ol>

    <div class="card shadow-sm mb-4">
        <div class="card-header card-header-custom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Editar Usuario: {{ $user->name }}</h5>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-arrow-left me-1"></i> Regresar
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', ['user' => $user]) }}" method="post">
                @method('PATCH')
                @csrf
                
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i> Los usuarios son los que pueden ingresar al sistema
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nombres</label>
                        <input type="text" name="name" id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $user->name) }}"
                               placeholder="Ingrese el nombre del usuario">
                        <div class="form-text">Escriba un solo nombre</div>
                        @error('name')
                            <div class="error-message text-danger">{{ '*'.$message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}"
                               placeholder="Ingrese el correo electrónico">
                        <div class="form-text">Dirección de correo electrónico</div>
                        @error('email')
                            <div class="error-message text-danger">{{ '*'.$message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 password-input-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" name="password" id="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Ingrese una nueva contraseña (opcional)">
                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                        <div class="form-text">Escriba una contraseña segura. Debe incluir números.</div>
                        @error('password')
                            <div class="error-message text-danger">{{ '*'.$message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 password-input-group">
                        <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirm" 
                               class="form-control" 
                               placeholder="Confirme la nueva contraseña">
                        <i class="fas fa-eye password-toggle" id="togglePasswordConfirm"></i>
                        <div class="form-text">Vuelva a escribir su contraseña.</div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="role" class="form-label">Rol</label>
                        <select name="role" id="role" 
                                class="form-select @error('role') is-invalid @enderror">
                            @foreach ($roles as $item)
                            <option value="{{ $item->name }}" 
                                {{ in_array($item->name, $user->roles->pluck('name')->toArray()) ? 'selected' : '' }}
                                {{ old('role') == $item->name ? 'selected' : '' }}>
                                {{ ucfirst($item->name) }}
                            </option>
                            @endforeach
                        </select>
                        <div class="form-text">Escoja un rol para el usuario.</div>
                        @error('role')
                            <div class="error-message text-danger">{{ '*'.$message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer text-center py-3">
                    <button type="submit" class="btn btn-primary-custom me-2">
                        <i class="fas fa-save me-1"></i> Actualizar Usuario
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
    document.addEventListener("DOMContentLoaded", function() {
        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const togglePasswordConfirm = document.querySelector('#togglePasswordConfirm');
        const password = document.querySelector('#password');
        const passwordConfirm = document.querySelector('#password_confirm');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        togglePasswordConfirm.addEventListener('click', function() {
            const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirm.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        // Password strength indicator (optional)
        password.addEventListener('input', function() {
            // Add password strength validation if needed
        });
    });
</script>
@endpush