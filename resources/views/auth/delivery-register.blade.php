<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - {{ $appName }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">Crear Cuenta de Repartidor</h2>
                        <p class="text-center text-muted mb-4">Únete a la comunidad de {{ $appName }}</p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li><small>{{ $error }}</small></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ url('/register') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nombre Completo</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ej. Oswaldo" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="usuario@ejemplo.com" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="password" name="password" class="form-control" placeholder="Mínimo 8 caracteres, letras y números" required>
                                <div class="form-text">La seguridad es prioridad en Florentica.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Repite tu contraseña" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2 fw-bold">Unirme al equipo de Repartidores</button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-decoration-none text-muted small">¿Ya tienes cuenta? Inicia sesión aquí</a>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="/" class="text-muted small text-decoration-none">← Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>