<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - {{ $appName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .bg-florentica { background: linear-gradient(135deg, #fdf2f8 0%, #ffffff 100%); min-height: 100vh; }
        .card-recovery { border: none; border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(244, 114, 182, 0.1); }
        .btn-pink { background-color: #f472b6; border: none; border-radius: 50px; padding: 0.8rem; font-weight: bold; transition: all 0.3s ease; color: white; }
        .btn-pink:hover { background-color: #db2777; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(244, 114, 182, 0.3); color: white; }
        .text-flower-pink { color: #f472b6 !important; }
        .form-control { border-radius: 0.75rem; padding: 0.75rem 1rem; }
    </style>
</head>

<body class="bg-florentica d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                
                <div class="text-center mb-4">
                    <h1 class="fw-bold text-flower-pink">🌸 Florentica</h1>
                </div>

                <div class="card card-recovery overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold text-center mb-3">¿Olvidaste tu acceso?</h4>
                        <p class="text-muted text-center small mb-4">
                            No te preocupes. Ingresa tu correo y te enviaremos un enlace para que elijas una nueva contraseña.
                        </p>

                        @if (session('status'))
                            <div class="alert alert-success border-0 small rounded-3">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com" required autofocus>
                                @error('email')
                                    <span class="text-danger small mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-pink w-100 shadow-sm">
                                ENVIAR ENLACE DE RECUPERACIÓN
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="small text-decoration-none text-flower-pink fw-bold">
                                ← Volver al inicio de sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>