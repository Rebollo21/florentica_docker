<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Florentica - Bienvenidos</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light" >



<nav class="navbar navbar-expand-lg navbar-light bg-flower-pink-light shadow-sm fixed-top">
    <div class="container">
        
        <a class="navbar-brand fw-bold text-flower-pink" href="/">🌸 Florentica</a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navFlorentica">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navFlorentica">
            <div class="ms-auto pt-3 pt-lg-0">
                <div class="d-grid d-lg-flex gap-3 align-items-center">
                    <a href="{{ route('login') }}" 
                       class="btn-florentica border-flower-pink px-4 py-2 rounded-full font-bold text-center no-underline">
                       Entrar
                    </a>
                    <a href="/register" 
                       class="btn-florentica bg-flower-pink px-4 py-2 rounded-full font-bold text-center no-underline shadow-sm">
                       Ser cliente
                    </a>
                    <a href="/join-delivery" 
                       class="btn-florentica bg-flower-purple px-4 py-2 rounded-full font-bold text-center no-underline shadow-sm">
                       Ser Repartidor
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>



<section class="py-5 mt-4 text-center bg-light" style="background: linear-gradient(rgba(255,255,255,0.8), rgba(255,255,255,0.8)), url('https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&w=1350&q=80'); background-size: cover; background-position: center;">
    
    <div class="container py-5 ">
        
        <h1 class="display-4 fw-bold text-flower-dark">Dilo con Flores, <br><span class="text-flower-pink">Dilo con Florentica</span></h1>
        
        <p class=" text-flower-dark  mb-4">Entregas el mismo día con la frescura que tus momentos especiales merecen.</p>
        
    </div>

</section>



{{-- 3 Empieza catalogo --}}
<section class="py-5 bg-flower-pink-light">
@php
    $configCategorias = [
        'clasicas' => [
            'titulo' => '🌹 Elegancia Clásica',
            'subtitulo' => 'Belleza atemporal para cada rincón',
            'clase_bg' => 'bg-flower-pink-light',
            'card_bg' => 'bg-light',
            'color' => '#d63384'
        ],
        'temporada' => [
            'titulo' => '🌿 Temporada',
            'subtitulo' => 'Detalles únicos para días especiales',
            'clase_bg' => 'bg-flower-pink-light',
            'card_bg' => 'bg-white',
            'color' => '#198754'
        ],
        'ocasion' => [
            'titulo' => '❤️ Amor y Pasión',
            'subtitulo' => 'Rosas que expresan lo que sientes',
            'clase_bg' => 'bg-flower-pink-light',
            'card_bg' => 'bg-light',
            'color' => '#dc3545'
        ],
    ];

    // Iniciamos un contador para saber si mostramos algo en total
    $totalMostrados = 0;
@endphp

@foreach($configCategorias as $slug => $info)
@php
    $productosCat = $productos->filter(function($p) use ($slug) {
        return Str::slug($p->categoria) === $slug 
               && $p->activo == 1 
               && $p->calcularStockDisponible() > 0;
    });

    if($productosCat->isNotEmpty()) {
        $totalMostrados += $productosCat->count();
    }
@endphp

@if($productosCat->isNotEmpty())
    <section class="py-5 {{ $info['clase_bg'] }}" id="catalogo-{{ $slug }}">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold display-6" style="color: {{ $info['color'] }} !important;">
                    {{ $info['titulo'] }}
                </h2>
                <p class="text-muted">{{ $info['subtitulo'] }}</p>
            </div>

            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($productosCat as $producto)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 {{ $info['card_bg'] }} overflow-hidden card-standard-effect">
                            <img src="{{ $producto->portada_url }}" class="card-img-top" style="height: 320px; object-fit: cover;">
                            <div class="card-body p-4 text-center">
                                <h5 class="fw-bold text-pink">{{ $producto->nombre_ramo }}</h5>
                                <p class="small fw-bold text-muted mb-2">{{ $producto->descripcion }}</p>
                                <p class="text-flower-green fw-bold fs-4 mb-3">${{ number_format($producto->precio_venta, 2) }}</p>
                                
                                <div class="d-grid gap-2">
                                    <form action="{{ route('cart.add', $producto->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold shadow-sm">
                                            <i class="fas fa-cart-plus me-2"></i>Agregar al Carrito
                                        </button>
                                    </form>
                                    <a href="{{ route('shop.show', $producto->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill border-0 fw-bold">
                                        Ver detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
@endforeach
{{-- MENSAJE CUANDO TODO ESTÁ AGOTADO --}}
@if($totalMostrados == 0)
    <div class="container py-5 text-center">
        <div class="py-5 shadow-sm rounded-4 bg-white border">
            {{-- MODIFICACIÓN AQUÍ: Cambiamos text-muted por text-pink --}}
            <i class="fas fa-seedling fa-4x mb-3 text-pink"></i>
            <h2 class="fw-bold text-dark">¡Lo sentimos!</h2>
            <p class="fs-5 text-muted">Actualmente no tenemos ramos en existencia.</p>
            <p class="small text-muted">Estamos preparando flores frescas para ti. ¡Vuelve pronto!</p>
            
        </div>
    </div>
@endif

</section>   
{{-- 3 Termina catalogo --}}


{{-- Inicio de la sección comentarios, con relleno vertical (py-5) y fondo blanco --}}
<section class="py-5 bg-flower-pink-light">
    {{-- Contenedor de Bootstrap para centrar el contenido y dar márgenes laterales --}}
    <div class="container text-center">
        {{-- Título principal con fuente negra, margen inferior y color personalizado del proyecto --}}
        <h2 class="fw-bold mb-5 text-flower-dark">Lo que dicen nuestros clientes</h2>
        
        {{-- Fila de la rejilla con espacio entre columnas (g-4) y centrada horizontalmente --}}
        <div class="row g-4 justify-content-center">
            
            {{-- Directiva Blade para iterar sobre la colección de comentarios enviada desde el controlador --}}
            @foreach($comments as $comment)
                {{-- Columna que ocupa 4 de 12 espacios en pantallas medianas (3 tarjetas por fila) --}}
                <div class="col-md-4">
                    {{-- Tarjeta sin bordes, con sombra suave, padding interno y esquinas muy redondeadas (rounded-4) --}}
                    {{-- La clase h-100 asegura que todas las tarjetas tengan la misma altura --}}
                    <div class="card border-0 shadow-sm p-4 rounded-4 h-100">
                        
                        {{-- Contenedor para las estrellas con color de advertencia (amarillo/dorado) --}}
                        <div class="text-warning mb-2">
                            {{-- Repite el carácter ★ según el número guardado en la columna 'stars' --}}
                            {{-- Luego repite ☆ para completar las 5 estrellas (5 menos el valor real) --}}
                            {{ str_repeat('★', $comment->stars) }}{{ str_repeat('☆', 5 - $comment->stars) }}
                        </div>
                        
                        {{-- Párrafo con texto en cursiva y color gris tenue para el cuerpo del comentario --}}
                        <p class="fst-italic text-muted">"{{ $comment->comment }}"</p>
                        
                        {{-- Condicional: solo renderiza el bloque de imagen si el registro tiene una ruta en 'photo' --}}
                        @if($comment->photo)
                            {{-- Contenedor de imagen con margen, recorte de exceso (overflow-hidden) y altura fija --}}
                            <div class="mb-3 overflow-hidden rounded-3" style="height: 150px;">
                                {{-- Helper asset() genera la URL pública; object-fit: cover evita que la foto se deforme --}}
                                <img src="{{ asset($comment->photo) }}" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                        @endif
                        
                        {{-- Nombre del usuario en negrita precedido por un guion largo --}}
                        {{-- Usamos el operador ?? para poner un nombre por defecto si el usuario falla --}}
<h6 class="fw-bold mb-0">— {{ $comment->user->name ?? 'Cliente Satisfecho' }}</h6>
                    </div>
                </div>
            {{-- Fin del ciclo de repetición --}}
            @endforeach

        </div> {{-- Fin de la fila (row) --}}
    </div> {{-- Fin del contenedor --}}
</section> {{-- Fin de la sección --}}





<footer class="bg-dark text-white pt-5 pb-4 mt-5">
    <div class="container text-md-start">
        <div class="row text-md-start">
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold text-flower-pink">Florentica</h5>
                <p>Tu florería boutique de confianza en la Ciudad de México. Calidad y frescura garantizada.</p>
            </div>
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold">Servicios</h5>
                <p><a href="/register" class="text-white text-decoration-none">Suscripciones</a></p>                
                <p><a href="/register" class="text-white text-decoration-none">Envíos a domicilio</a></p>
            </div>
            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-3 fw-bold">Contacto</h5>
                <p><i class="bi bi-house me-3"></i> CDMX, México</p>
                <p><i class="bi bi-envelope me-3"></i> contacto@florentica.com</p>
                <p><i class="bi bi-envelope me-3"></i> contacto@gmail.com</p>
                <p><i class="bi bi-phone me-3"></i> +52 56 54 02 74 43</p>
            </div>
        </div>
        <hr class="mb-4">
        <div class="row align-items-center text-center">
            <p>© 2026 Florentica. Desarrollado por <strong>Bollotech</strong>.</p>
        </div>
    </div>
</footer>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

