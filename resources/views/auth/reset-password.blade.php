<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - Florentica</title>
    
    {{-- Assets de Laravel + Bootstrap --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Iconos Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .bg-login-florentica { 
            background: linear-gradient(135deg, #fdf2f8 0%, #ffffff 100%);
            min-height: 100vh;
        }
        .card-login {
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px rgba(244, 114, 182, 0.1);
        }
        .btn-login {
            background-color: #f472b6; /* flower-pink */
            border: none;
            border-radius: 50px;
            padding: 0.8rem;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background-color: #db2777;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(244, 114, 182, 0.3);
        }
        .form-control {
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
        }
        .form-control:focus {
            border-color: #f472b6;
            box-shadow: 0 0 0 0.25rem rgba(244, 114, 182, 0.15);
        }
        .text-flower-pink { color: #f472b6 !important; }
        
        /* Estilo para los iconos de validación */
        .input-group-text {
            background-color: white;
            border-radius: 0.75rem 0 0 0.75rem;
            border-right: none;
            color: #f472b6;
        }
        .input-with-icon {
            border-left: none;
            border-radius: 0 0.75rem 0.75rem 0 !important;
        }
    </style>
</head>

<body class="bg-login-florentica d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                
                {{-- Branding --}}
                <div class="text-center mb-4">
                    <h1 class="fw-bold text-flower-pink">🌸 Florentica</h1>
                </div>

                <div class="card card-login overflow-hidden">
                    <div class="text-center py-4 bg-white border-bottom">
                        <h4 class="fw-bold mb-0">Nueva Contraseña</h4>
                        <p class="small text-muted mb-0">Protege tu cuenta con una clave maestra</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        {{-- Errores de Validación --}}
                        @if ($errors->any())
                            <div class="alert alert-danger border-0 small rounded-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            {{-- Email (Informativo) --}}
<div class="mb-3">
    <label class="form-label small fw-bold text-secondary">Correo Confirmado</label>
    <div class="input-group">
        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
        <input type="email" name="email" class="form-control input-with-icon bg-light" 
               value="{{ request()->query('email') ?? $email ?? old('email') }}" 
               readonly required>
    </div>
</div>

                            {{-- Nueva Contraseña --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Nueva Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control input-with-icon" 
                                           placeholder="••••••••" required autofocus>
                                </div>
                            </div>

                            {{-- Confirmar Contraseña --}}
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary">Confirmar Nueva Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-shield-heart"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control input-with-icon" 
                                           placeholder="••••••••" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-login w-100 text-white shadow-sm uppercase">
                                <i class="fas fa-key me-2"></i> ACTUALIZAR CREDENCIALES
                            </button>
                        </form>





                        