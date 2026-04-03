<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica tu Correo - Florentica</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --florentica-pink: #F2A2C0; /* El rosa de marca */
            --florentica-cream: #FFF9F5; /* Fondo suave */
            --florentica-gold: #D4AF37; /* Acento elegante */
            --text-dark: #333;
            --text-muted: #777;
        }

        body {
            background-color: var(--florentica-cream);
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: var(--text-dark);
        }

        .card {
            background: white;
            padding: 60px; /* Un poco más de aire */
            border-radius: 20px;
            /* Usamos la misma sombra sutil que en los errores */
            box-shadow: 0 15px 35px rgba(139, 69, 19, 0.08); 
            text-align: center;
            max-width: 550px;
            /* La franja rosa superior, distintiva de Florentica */
            border-top: 5px solid var(--florentica-pink); 
            position: relative;
        }

        /* Icono decorativo opcional para dar calidez */
        .icon-header {
            font-size: 50px;
            color: var(--florentica-pink);
            margin-bottom: 20px;
            display: block;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            color: var(--florentica-pink);
            font-size: 48px; /* Un poco más pequeño que el error, más amigable */
            margin: 0 0 15px 0;
            font-weight: 700;
        }

        .divider {
            height: 1px;
            background: #eee;
            margin: 25px auto;
            width: 80%;
        }

        p {
            line-height: 1.8;
            color: var(--text-dark);
            margin: 0 0 15px 0;
            font-size: 1.1em;
        }

        .text-muted {
            color: var(--text-muted);
            font-size: 0.9em;
            margin-top: 20px;
            display: block;
            font-weight: 300;
        }

        /* Botón opcional por si quieres que reenvíen el correo */
        .btn-resend {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 30px;
            background-color: transparent;
            color: var(--florentica-pink);
            border: 2px solid var(--florentica-pink);
            text-decoration: none;
            border-radius: 50px;
            transition: 0.3s;
            font-weight: 600;
            font-size: 0.9em;
            cursor: pointer;
        }

        .btn-resend:hover {
            background-color: var(--florentica-pink);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(242, 162, 192, 0.2);
        }

    </style>
</head>
<body>
    <div class="card">
        <span class="icon-header">✉️</span> 
        
        <h1>¡Casi listo!</h1>
        
        <div class="divider"></div>
        
        <p>Te hemos enviado un enlace de confirmación.</p>
        <p>Por favor, revisa tu bandeja de entrada para activar tu cuenta y comenzar tu experiencia en Florentica.</p>
        
        <div class="divider"></div>

        <p class="text-muted small">
            ¿No recibiste nada?<br>
            Por favor, revisa la carpeta de **Spam** o Correo No Deseado.
        </p>