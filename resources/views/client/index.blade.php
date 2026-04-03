<!DOCTYPE html>
<html lang="es">

<head>
    
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Tienda - Florentica</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

@extends('layouts.app')

{{-- El nav esta en layout  --}}


@section('content')



{{-- 2 Empieza Categorias --}}



            @php
                $esPremium = auth()->check() && auth()->user()->es_premium;
            @endphp
            
            {{-- PERFIL PREMIUM --}}
            @if($esPremium)

{{-- Sección de Alertas Elite / Premium --}}

    {{-- Alerta de Éxito Premium --}}
    @if (session('success'))
        <div class="alert border-0 shadow-lg position-relative overflow-hidden mb-4" 
             style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); border-left: 4px solid #d4af37 !important; border-radius: 15px;">
            {{-- Efecto de brillo dorado al fondo --}}
            <div class="position-absolute top-0 end-0 p-3 opacity-10">
                <i class="fas fa-crown fa-3x text-gold"></i>
            </div>
            
            <div class="d-flex align-items-center">
                <div class="bg-gold-premium rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" 
                     style="width: 40px; height: 40px; background: #d4af37;">
                    <i class="fas fa-check text-dark"></i>
                </div>
                <div>
                    <strong class="text-flower-gold d-block uppercase tracking-wider small">Operación Exitosa</strong>
                    <p class="mb-0 text-white fw-medium">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Alerta de Error Premium --}}
    @if (session('error'))
        <div class="alert border-0 shadow-lg mb-4" 
             style="background: rgba(220, 53, 69, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(220, 53, 69, 0.2) !important; border-radius: 15px;">
            
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center">
                    <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 28px; height: 28px;">
                        <i class="fas fa-times text-white small"></i>
                    </div>
                    <strong class="text-danger small fw-bold">INCIDENCIA DE INVENTARIO</strong>
                </div>
                <span class="badge rounded-pill bg-danger-soft text-danger border border-danger px-3 py-2" style="font-size: 0.7rem; background: rgba(220, 53, 69, 0.1);">
                    CÓDIGO: {{ session('error_code') }}
                </span>
            </div>

            <hr class="my-2 opacity-10 bg-danger">

            <p class="mb-0 text-dark fw-bold d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2 text-danger"></i>
                {{ session('error') }}
            </p>
        </div>
    @endif

            






{{-- ==========================================
     LÓGICA DE NEGOCIO BOLLOTECH (Segmentación)
     ========================================== --}}
@php
    $esPremium = auth()->check() && auth()->user()->es_premium;

    $configCategorias = [
        'premium' => [
            'titulo'    => '👑 Colección Elite (Exclusiva)',
            'subtitulo' => 'Arreglos de flores nivel premium.',
            'clase_bg'  => ' nav-premium-dark py-5', 
            'card_class' => 'card-standard-effect',
            'color'     => '#d4af37',
            'solo_premium' => true
        ],
        'clasicas' => [
            'titulo'    => '🌹 Elegancia Clásica',
            'subtitulo' => 'Belleza atemporal para cada rincón.',
            'clase_bg'  => 'nav-premium-dark py-5',
            'card_class' => 'card-standard-effect',
            'color'     => '#d63384',
            'solo_premium' => true
        ],
        'temporada' => [
            'titulo'    => '🌿 Temporada',
            'subtitulo' => 'Detalles únicos para días especiales.',
            'clase_bg'  => 'nav-premium-dark py-5',
            'card_class' => 'card-standard-effect',
            'color'     => '#198754',
            'solo_premium' => true
        ],
        'ocasion' => [
            'titulo'    => '❤️ Amor y Pasión',
            'subtitulo' => 'Rosas que expresan lo que realmente sientes.',
            'clase_bg'  => 'nav-premium-dark py-5',
            'card_class' => 'card-standard-effect',
            'color'     => 'red',
            'solo_premium' => true
        ],
    ];

    $totalMostrados = 0;
@endphp


<section class="py-5 mt-5  nav-premium-dark">
    <div class="container text-center">
        <h2 class="fw-bold text-flower-pink mb-4">Explora nuestras colecciones</h2>

            <div class="row g-4 justify-content-center">

                <div class="col-6 col-md-3">
                    <div class="card card-categorias border-0 shadow-sm p-3 rounded-4 h-100 card-brincar bg-dark border-gold-elite">
                        <a href="#section-premium" class="card-categoria-link text-decoration-none">
                            <div class="card-categoria">
                                <span class="fs-1">👑</span>
                                <h5 class="mt-2 fw-bold text-flower-gold">Colección Elite</h5>
                            </div>
                        </a>
                    </div>
                </div>

            
            {{-- Tarjeta 1: Temporada --}}
            <div class="col-6 col-md-3">
                <div class="card card-categorias border-0 shadow-sm p-3 rounded-4 h-100 card-brincar">
                    <a href="#section-temporada" class="card-categoria-link text-decoration-none">
                        <div class="card-categoria {{ request('categoria') == 'temporada' ? 'active' : '' }}">
                            <span class="fs-1">🌿</span>
                            <h5 class="mt-2 fw-bold text-flower-pink">Temporada</h5>
                        </div>
                    </a>
                </div>
            </div>
            
            {{-- Tarjeta 2: Clásicas --}}
            <div class="col-6 col-md-3">
                <div class="card card-categorias border-0 shadow-sm p-3 rounded-4 h-100 card-brincar">
                    <a href="#section-clasicas" class="card-categoria-link text-decoration-none">
                        <div class="card-categoria {{ request('categoria') == 'clasicas' ? 'active' : '' }}">
                            <span class="fs-1">🌹</span>
                            <h5 class="mt-2 fw-bold text-flower-pink">Clásicas </h5>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Tarjeta 3: Ocasión (Cumpleaños) --}}
            <div class="col-6 col-md-3">
                <div class="card card-categorias border-0 shadow-sm p-3 rounded-4 h-100 card-brincar">
                    <a href="#section-ocasion" class="card-categoria-link text-decoration-none">
                        <div class="card-categoria {{ request('categoria') == 'ocasion' ? 'active' : '' }}">
                            <span class="fs-1">🎂</span>
                            <h5 class="mt-2 fw-bold text-flower-pink">Cumpleaños</h5>
                        </div>
                    </a>
                </div>
            </div>
        </div> 
    </div> 
</section>


<section class="catalogo-main">
    @foreach($configCategorias as $slug => $info)
        @php
            // FILTRO DE SEGURIDAD: Si la categoría es premium y el usuario no, saltamos al siguiente
            if ($info['solo_premium'] && !$esPremium) continue;

            $productosCat = $productos->filter(function($p) use ($slug) {
                return Str::slug($p->categoria) === $slug 
                       && $p->activo == 1 
                       && $p->calcularStockDisponible() > 0;
            });

            if($productosCat->isNotEmpty()) $totalMostrados += $productosCat->count();
        @endphp

        @if($productosCat->isNotEmpty())
            <div class="{{ $info['clase_bg'] }}" id="section-{{ $slug }}">
                <div class="container">
                    {{-- Encabezado de Categoría --}}
                    <div class="text-center mb-5">
                        <h2 class="fw-bold display-5" style="color: {{ $info['color'] }}">
                            {{ $info['titulo'] }}
                        </h2>
                        <p class="{{ $info['solo_premium'] ? 'text-white-50' : 'text-muted' }} fs-5">
                            {{ $info['subtitulo'] }}
                        </p>
                    </div>

                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        @foreach($productosCat as $producto)
                            <div class="col">
                                <div class="card h-100 border-0 shadow-sm overflow-hidden card-standard-effect-gold">


                                    <img src="{{ $producto->portada_url }}" class="card-img-top" alt="{{ $producto->nombre_ramo }}" style="height: 350px; object-fit: cover;">
                                    
                                    <div class="card-body p-4 text-center">
                                        <h4 class="fw-bold mb-2 text-flower-gold">{{ $producto->nombre_ramo }}</h4>
                                        <p class="text-muted small mb-3">{{ Str::limit($producto->descripcion, 80) }}</p>
                                        <h3 class="fw-bold {{ $info['solo_premium'] ? 'text-flower-gold' : 'text-flower-green' }} mb-4">
                                            ${{ number_format($producto->precio_venta, 2) }}
                                        </h3>

                                        <div class="d-grid gap-2">
                                            <form action="{{ route('cart.add', $producto->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn  btn-premium-track {{ $info['solo_premium'] ? 'btn-elite-gold' : 'btn-florenticaPerfil' }} rounded-pill py-2 fw-bold">
                                                    <i class="fas fa-shopping-bag me-2 text-flower-gold"></i> Agregar al carrito
                                                </button>
                                            </form>
                                            <a href="{{ route('shop.show', $producto->id) }}" class=" text-white link-det">
                                                Ver detalles 
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    {{-- EMPTY STATE --}}
    @if($totalMostrados == 0)
        <div class="container py-5 text-center">
            <div class="p-5 bg-white rounded-4 shadow-sm border">
                <i class="fas fa-ghost fa-4x text-flower-pink mb-3"></i>
                <h2 class="fw-bold">No hay flores disponibles por ahora</h2>
                <p class="text-muted">Estamos reabasteciendo nuestro inventario con flores frescas.</p>
            </div>
        </div>
    @endif
</section>        


{{-- Inicio de la sección comentarios, con relleno vertical (py-5) y fondo blanco --}}
<section class="py-5 nav-premium-dark">
    {{-- Contenedor de Bootstrap para centrar el contenido y dar márgenes laterales --}}
    <div class="container text-center ">
        {{-- Título principal con fuente negra, margen inferior y color personalizado del proyecto --}}
        <h2 class="fw-bold mb-5 text-flower-pink">Lo que dicen nuestros clientes</h2>
        
        {{-- Fila de la rejilla con espacio entre columnas (g-4) y centrada horizontalmente --}}
        <div class="row g-4 justify-content-center ">
            
            {{-- Directiva Blade para iterar sobre la colección de comentarios enviada desde el controlador --}}
            @foreach($comments as $comment)
                {{-- Columna que ocupa 4 de 12 espacios en pantallas medianas (3 tarjetas por fila) --}}
                <div class="col-md-4">
                    {{-- Tarjeta sin bordes, con sombra suave, padding interno y esquinas muy redondeadas (rounded-4) --}}
                    {{-- La clase h-100 asegura que todas las tarjetas tengan la misma altura --}}
                    <div class="card  card-comentarios border-0 shadow-sm p-4 rounded-4 h-100">
                        
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
                                    
                                    
{{-- 4. Empieza comentarios --}}

 {{-- Sección para dejar una reseña, con un formulario que envía los datos a la ruta 'comments.store' utilizando el método POST. El formulario incluye un campo de selección para la calificación (estrellas), un área de texto para el comentario y un campo opcional para subir una foto. Además, se muestra un mensaje de éxito si la sesión contiene una variable 'success', lo que indica que el comentario se ha enviado correctamente. El mensaje de éxito se muestra dentro de una alerta de Bootstrap con un diseño personalizado que incluye un ícono de verificación y un botón para cerrar la alerta. --}}
@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 d-flex align-items-center mb-4 fade show" role="alert">
        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
            <i class="fas fa-check"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0">¡Envío exitoso!</h6>
            <small>{{ session('success') }}</small>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
 {{-- Formulario para dejar una reseña, con un diseño de tarjeta que incluye un título, campos para la calificación, comentario y foto, y un botón para enviar el formulario. El formulario utiliza la clase 'btn-pink' personalizada para el estilo del botón de envío, además de ser redondeado, tener un padding vertical y una sombra para mejorar su apariencia. El formulario también incluye un token CSRF para proteger contra ataques de falsificación de solicitudes entre sitios, lo que es obligatorio en Laravel para cualquier formulario que envíe datos al servidor. --}}

 {{-- Formulario de Reseña Premium --}}
<section class="py-5 nav-premium-dark border-top border-gold-elite">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                {{-- Tarjeta de Formulario con Glassmorphism --}}
                <div class="card shadow-lg border-0 p-4 rounded-4" 
                     style="background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(15px); border: 1px solid rgba(212, 175, 55, 0.3) !important;">

                    <h3 class="fw-bold text-center mb-4 text-flower-gold uppercase tracking-wider">
                        Comparte tu Experiencia
                    </h3>
                    
                    <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold text-white-50 small">NIVEL DE SATISFACCIÓN</label>
                            <select name="stars" class="form-select bg-dark text-white border-secondary rounded-pill py-2 px-3 focus-gold" required>
                                <option value="5">⭐⭐⭐⭐⭐ Excelencia Total</option>
                                <option value="4">⭐⭐⭐⭐ Muy Satisfecho</option>
                                <option value="3">⭐⭐⭐ Cumple Expectativas</option>
                                <option value="2">⭐⭐ Podría Mejorar</option>
                                <option value="1">⭐ No recomendado</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-white-50 small">SU COMENTARIO</label>
                            <textarea name="comment" class="form-control bg-dark text-white border-secondary rounded-4 p-3 focus-gold" 
                                      rows="4" placeholder="Describa la calidad de sus flores..." required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-white-50 small">ADJUNTAR EVIDENCIA (OPCIONAL)</label>
                            <input type="file" name="photo" class="form-control bg-dark text-white border-secondary rounded-pill" accept="image/*">
                        </div>

                        <button type="submit" id="btnEnviar" 
                                class="btn-premium-action  w-100 py-3 rounded-pill fw-bold shadow-gold-glow">
                            PUBLICAR RESEÑA 
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>












            @else
               
@php
    // Forzamos la lógica a estado normal (No Premium)
    $esPremium = false; 

    $configCategorias = [
        'temporada' => [
            'titulo'    => '🌿 Colección de Temporada',
            'subtitulo' => 'Flores frescas recién llegadas para esta estación.',
            'clase_bg'  => 'bg-light py-5', 
            'color'     => '#198754', // Verde naturaleza
            'icono'     => '🌿'
        ],
        'clasicas' => [
            'titulo'    => '🌹 Elegancia Clásica',
            'subtitulo' => 'Los favoritos de siempre que nunca pasan de moda.',
            'clase_bg'  => 'bg-white py-5',
            'color'     => '#d63384', // Rosa Florentica
            'icono'     => '🌹'
        ],
        'ocasion' => [
            'titulo'    => '🎂 Celebraciones Especiales',
            'subtitulo' => 'El detalle perfecto para cumpleaños y aniversarios.',
            'clase_bg'  => 'bg-light py-5',
            'color'     => '#dc3545', // Rojo pasión
            'icono'     => '🎂'
        ],
    ];

    $totalMostrados = 0;
@endphp


<section class="py-5 mt-5  nav-normal">
    <div class="container text-center">
        <h2 class="fw-bold text-flower-pink mb-4">Explora nuestras colecciones</h2>

            <div class="row g-4 justify-content-center">

                <div class="col-6 col-md-3">
                    <div class="card card-categorias border-0 shadow-sm p-3 rounded-4 h-100 card-brincar bg-dark border-gold-elite">
                        <a href="#section-premium" class="card-categoria-link text-decoration-none">
                            <div class="card-categoria">
                                <span class="fs-1">👑</span>
                                <h5 class="mt-2 fw-bold text-flower-gold">Colección Elite</h5>
                            </div>
                        </a>
                    </div>
                </div>

            
            {{-- Tarjeta 1: Temporada --}}
            <div class="col-6 col-md-3">
                <div class="card card-categorias border-0 shadow-sm p-3 rounded-4 h-100 card-brincar">
                    <a href="#section-temporada" class="card-categoria-link text-decoration-none">
                        <div class="card-categoria {{ request('categoria') == 'temporada' ? 'active' : '' }}">
                            <span class="fs-1">🌿</span>
                            <h5 class="mt-2 fw-bold text-flower-pink">Temporada</h5>
                        </div>
                    </a>
                </div>
            </div>
            
            {{-- Tarjeta 2: Clásicas --}}
            <div class="col-6 col-md-3">
                <div class="card card-categorias border-0 shadow-sm p-3 rounded-4 h-100 card-brincar">
                    <a href="#section-clasicas" class="card-categoria-link text-decoration-none">
                        <div class="card-categoria {{ request('categoria') == 'clasicas' ? 'active' : '' }}">
                            <span class="fs-1">🌹</span>
                            <h5 class="mt-2 fw-bold text-flower-pink">Clásicas </h5>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Tarjeta 3: Ocasión (Cumpleaños) --}}
            <div class="col-6 col-md-3">
                <div class="card card-categorias border-0 shadow-sm p-3 rounded-4 h-100 card-brincar">
                    <a href="#section-ocasion" class="card-categoria-link text-decoration-none">
                        <div class="card-categoria {{ request('categoria') == 'ocasion' ? 'active' : '' }}">
                            <span class="fs-1">🎂</span>
                            <h5 class="mt-2 fw-bold text-flower-pink">Cumpleaños</h5>
                        </div>
                    </a>
                </div>
            </div>
        </div> 
    </div> 
</section>



{{-- 2. SECCIÓN: CATÁLOGO DINÁMICO --}}
<section class="catalogo-main">
    @foreach($configCategorias as $slug => $info)
        @php
            $productosCat = $productos->filter(function($p) use ($slug) {
                return Str::slug($p->categoria) === $slug 
                       && $p->activo == 1 
                       && $p->calcularStockDisponible() > 0;
            });

            if($productosCat->isNotEmpty()) $totalMostrados += $productosCat->count();
        @endphp

        @if($productosCat->isNotEmpty())
            <div class="{{ $info['clase_bg'] }}" id="section-{{ $slug }}">
                <div class="container">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold display-6" style="color: {{ $info['color'] }}">
                            {{ $info['titulo'] }}
                        </h2>
                        <p class="text-muted fs-5">{{ $info['subtitulo'] }}</p>
                    </div>

                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        @foreach($productosCat as $producto)
                            <div class="col">
                                <div class="card h-100 border-0 shadow-sm overflow-hidden rounded-4">
                                    <img src="{{ $producto->portada_url }}" class="card-img-top" style="height: 300px; object-fit: cover;">
                                    
                                    <div class="card-body p-4 text-center">
                                        <h4 class="fw-bold mb-2 text-dark">{{ $producto->nombre_ramo }}</h4>
                                        <p class="text-muted small mb-3">{{ Str::limit($producto->descripcion, 60) }}</p>
                                        <h3 class="fw-bold text-flower-green mb-4">
                                            ${{ number_format($producto->precio_venta, 2) }}
                                        </h3>

                                        <div class="d-grid gap-2">
                                            <form action="{{ route('cart.add', $producto->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-florenticaPerfil rounded-pill py-2 fw-bold w-100">
                                                    <i class="fas fa-shopping-bag me-2"></i> Agregar
                                                </button>
                                            </form>
                                            <a href="{{ route('shop.show', $producto->id) }}" class="btn btn-link text-flower-pink text-decoration-none fw-bold">
                                                Ver detalles 
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    @if($totalMostrados == 0)
        <div class="container py-5 text-center">
            <div class="p-5 bg-light rounded-4 border border-dashed">
                <i class="fas fa-leaf fa-3x text-muted mb-3"></i>
                <h2 class="fw-bold text-muted">Próximamente más flores</h2>
                <p>Estamos preparando nuevos arreglos para ti.</p>
            </div>
        </div>
    @endif
</section>

{{-- 3. SECCIÓN: TESTIMONIOS --}}
<section class="py-5 bg-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-5 text-flower-pink">Voces de nuestros clientes</h2>
        <div class="row g-4 justify-content-center">
            @foreach($comments as $comment)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-4 rounded-4 h-100" style="background: #f9f9f9;">
                        <div class="text-warning mb-2">
                            {{ str_repeat('★', $comment->stars) }}{{ str_repeat('☆', 5 - $comment->stars) }}
                        </div>
                        <p class="fst-italic text-muted small">"{{ $comment->comment }}"</p>
                        @if($comment->photo)
                            <div class="mb-3 overflow-hidden rounded-3" style="height: 120px;">
                                <img src="{{ asset($comment->photo) }}" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                        @endif
                        <h6 class="fw-bold mb-0 text-dark">— {{ $comment->user->name ?? 'Cliente Satisfecho' }}</h6>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 4. SECCIÓN: FORMULARIO DE RESEÑA --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="card shadow-sm border-0 p-4 rounded-4 bg-white">
                    <h3 class="fw-bold text-center mb-4 text-flower-pink">Comparte tu experiencia</h3>
                    <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold small">CALIFICACIÓN</label>
                            <select name="stars" class="form-select border-light-subtle" required>
                                <option value="5">Excelente ⭐⭐⭐⭐⭐</option>
                                <option value="4">Muy bueno ⭐⭐⭐⭐</option>
                                <option value="3">Bueno ⭐⭐⭐</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">TU COMENTARIO</label>
                            <textarea name="comment" class="form-control border-light-subtle" rows="3" placeholder="¿Qué te pareció el servicio?" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small">SUBIR FOTO</label>
                            <input type="file" name="photo" class="form-control border-light-subtle" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-florenticaPerfil w-100 py-3 rounded-pill fw-bold">
                            Publicar Reseña
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
  @endif

