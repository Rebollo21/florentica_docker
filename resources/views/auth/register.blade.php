<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - {{ $appName }}</title>
    
    {{-- Assets de Laravel + Bootstrap --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- IMPORTANTE: Font Awesome para los iconos --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .bg-florentica { background-color: #fdf2f8; }
        .text-flower-pink { color: #f472b6 !important; }
        .btn-register {
            background-color: #f472b6;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-register:hover {
            background-color: #db2777;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(244, 114, 182, 0.4);
        }
        .form-control:focus {
            border-color: #f472b6;
            box-shadow: 0 0 0 0.25rem rgba(244, 114, 182, 0.25);
        }
        /* Estilo para los botones de ojo */
        .input-group-text-eye {
            background-color: white;
            border-left: none;
            cursor: pointer;
            color: #f472b6;
            border-radius: 0 0.75rem 0.75rem 0 !important;
        }
        .input-group-text-eye:hover {
            color: #db2777;
        }
        .input-with-eye {
            border-right: none;
            border-radius: 0.75rem 0 0 0.75rem !important;
        }
    </style>
</head>

<body class="bg-florentica">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <h1 class="fw-bold text-flower-pink">🌸 Florentica</h1>
                </div>

                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <h2 class="text-center fw-bold mb-2">Crear Cuenta</h2>
                        <p class="text-center text-muted mb-4 small">Únete a la florería más exclusiva de México</p>

                        @if ($errors->any())
                            <div class="alert alert-danger border-0 rounded-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li><small>{{ $error }}</small></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Nombre Completo</label>
                                <input type="text" name="name" class="form-control rounded-3" value="{{ old('name') }}" placeholder="Ej. Oswaldo" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control rounded-3" value="{{ old('email') }}" placeholder="usuario@ejemplo.com" required>
                            </div>

                            {{-- Contraseña 1 --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Contraseña</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control input-with-eye" placeholder="Mínimo 8 caracteres" required>
                                    <span class="input-group-text input-group-text-eye toggle-password" data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                                <div class="form-text mt-1" style="font-size: 0.75rem;">Usa letras y números para mayor seguridad.</div>
                            </div>

                            {{-- Confirmar Contraseña --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Confirmar Contraseña</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control input-with-eye" placeholder="Repite tu contraseña" required>
                                    <span class="input-group-text input-group-text-eye toggle-password" data-target="password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-register w-100 py-3 text-white fw-bold rounded-pill">
                                Crear Cuenta
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <span class="text-muted small">¿Ya tienes cuenta?</span>
                            <a href="{{ route('login') }}" class="text-flower-pink fw-bold text-decoration-none small"> Inicia sesión aquí</a>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="/" class="text-muted small text-decoration-none">← Regresar</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Script para manejar ambos ojos dinámicamente --}}
    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    </script>
</body>
</html>