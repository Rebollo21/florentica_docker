<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Restringido - Florentica</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
    <style>
        :root {
            --florentica-brown: #F2A2C0; /* El color del profe */
            --florentica-cream: #FFF9F5;
            --florentica-gold: #D4AF37;
        }
        body { background-color: var(--florentica-cream); font-family: 'Poppins', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; color: #333; }
        .card { background: white; padding: 50px; border-radius: 20px; box-shadow: 0 15px 35px rgba(139, 69, 19, 0.1); text-align: center; max-width: 500px; border-top: 5px solid var(--florentica-brown); }
        h1 { font-family: 'Playfair Display', serif; color: var(--florentica-brown); font-size: 64px; margin: 0; }
        .mt-code { font-weight: bold; letter-spacing: 2px; color: var(--florentica-gold); margin-bottom: 20px; display: block; }
        p { line-height: 1.6; color: #666; }
        .user-tag { background: #fdf2f2; color: var(--florentica-brown); padding: 5px 15px; border-radius: 50px; font-size: 0.9em; font-weight: 600; }
        .divider { height: 1px; background: #eee; margin: 25px 0; }
        .btn { display: inline-block; padding: 12px 30px; background-color: var(--florentica-brown); color: white; text-decoration: none; border-radius: 50px; transition: 0.3s; font-weight: 500; }
        .btn:hover { background-color: #F2A2C0; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="card">
        <span class="mt-code">MENSAJE DE SISTEMA: MT296</span>
        <h1>Acceso Privado</h1>
        <div class="divider"></div>
        <p>Lo sentimos, <strong>{{ auth()->user()->name }}</strong>.</p>
<p>La sesión actual no cuenta con los activos de seguridad necesarios para liquidar esta vista.</p>
<p style="font-size: 0.8em; color: #999;">ID de Seguimiento: MT296-{{ strtoupper(Str::random(5)) }}</p>        <p style="font-size: 0.8em; color: #999;">Error: Lack of Privileges - Transacción Abortada.</p>
        <div class="divider"></div>
        <a href="/" class="btn">Volver al Inicio</a>
    </div>
</body>
</html>