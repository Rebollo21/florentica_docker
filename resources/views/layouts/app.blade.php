<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Florentica - @yield('title', 'Boutique Floral')</title>

    {{-- 1. ESTILOS EXTERNOS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    {{-- 2. VITE --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body>

<header>
    @auth
        @if(auth()->user()->role == 'repartidor')
            {{-- ==========================================
                NAV PARA REPARTIDOR 🚚 
            ========================================== --}}
            <nav class="navbar navbar-expand-lg bg-dark fixed-top shadow">
                <div class="container">
                    <a class="navbar-brand text-flower-pink fw-bold" href="#">🚚 Florentica Logística</a>
                    <div class="ms-auto d-flex gap-2">
                        <a href="/entregas" class="btn btn-sm bg-flower-pink text-white px-3"><i class="fas fa-truck"></i></a>
                        <a href="/scanner" class="btn btn-sm bg-flower-gold text-white px-3"><i class="fas fa-qrcode"></i></a>
                        @include('partials.logout-button')
                    </div>
                </div>
            </nav>

        @elseif(auth()->user()->role == 'admin')
            {{-- ==========================================
                NAV PARA ADMINISTRADOR ⚙️ 
            ========================================== --}}
            <nav class="navbar navbar-expand-lg bg-dark fixed-top shadow">
                <div class="container">
                    <a class="navbar-brand text-flower-pink fw-bold" href="/admin/dashboard">⚙️ Admin Panel</a>
                    <div class="ms-auto d-flex gap-2 text-white">
                        <a href="/admin/dashboard" class="btn btn-sm bg-flower-pink text-white">Dashboard</a>
                        <a href="/admin/inventario" class="btn btn-sm bg-flower-gold text-white">Inventario</a>
                        @include('partials.logout-button')
                    </div>
                </div>
            </nav>

        @elseif(auth()->user()->es_premium)
            {{-- ==========================================
                NAV PARA CLIENTE PREMIUM 👑 (Elite)
            ========================================== --}}
            <nav class="navbar navbar-expand-lg shadow-lg fixed-top nav-premium-dark">
                <div class="container">
                    <a class="navbar-brand fw-bold logo-premium" href="/shop">
                        <span class="text-flower-pink">🌸  Florentica</span> <small class="text-white-50 ms-1" style="font-size: 0.6rem;">ELITE</small>
                    </a>
                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navPremium">
                        <i class="fas fa-bars text-flower-gold"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navPremium">
                        <div class="ms-auto d-flex gap-3 align-items-center">
                            @php
                                $pedido = DB::table('ventas')->join('detalle_ventas', 'ventas.id', '=', 'detalle_ventas.venta_id')
                                    ->where('ventas.user_id', auth()->id())->where('detalle_ventas.status_entrega', '!=', 'entregado')
                                    ->select('ventas.id', 'detalle_ventas.status_entrega')->latest('ventas.created_at')->first();
                            @endphp
                            @if($pedido)
                                <a href="{{ route('carrito.seguimiento', $pedido->id) }}" class="btn btn-premium-track btn-sm rounded-pill px-3">
                                    <i class="fas fa-star animate-spin-slow"></i> En camino
                                </a>
                            @endif
                            <a href="{{ route('cart.index') }}" class="text-flower-gold position-relative mx-2">
                                <i class="fas fa-shopping-basket fa-lg"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-flower-gold text-dark" style="font-size: 0.6rem;">
                                    {{ collect(session('cart', []))->sum(fn($item) => $item['cantidad'] ?? 0) }}
                                </span>
                            </a>
                            <a href="{{ route('client.profile') }}" class="btn btn-premium-action  btn-sm rounded-pill px-3">Perfil VIP</a>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">@csrf <button type="submit" class="btn text-danger p-0 px-2"><i class="fas fa-power-off"></i></button></form>
                        </div>
                    </div>
                </div>
            </nav>

        @else
            {{-- ==========================================
                NAV PARA CLIENTE ESTÁNDAR 🌸 
            ========================================== --}}
            <nav class="navbar navbar-expand-lg shadow-sm fixed-top ">
                <div class="container">
                    {{-- Logo --}}
                    <a class="navbar-brand fw-bold logo text-flower-pink" href="/shop">🌸 Florentica</a>

                    {{-- BOTÓN TOGGLER (Mobile) --}}
                    <button class="navbar-toggler border-0" type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#navFlorentica" 
                            aria-controls="navFlorentica" 
                            aria-expanded="false" 
                            aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    {{-- CONTENEDOR COLAPSABLE --}}
                    <div class="collapse navbar-collapse" id="navFlorentica">
                        <div class="ms-auto pt-3 pt-lg-0">
                            <div class="d-grid d-lg-flex gap-3 align-items-center">

                                @auth
                                    {{-- 1. LÓGICA DE PEDIDO ACTIVO (Bollotech Logic) --}}
                                    @php
                                        $pedidoActivo = DB::table('ventas')
                                            ->join('detalle_ventas', 'ventas.id', '=', 'detalle_ventas.venta_id')
                                            ->where('ventas.user_id', auth()->id())
                                            ->where('detalle_ventas.status_entrega', '!=', 'entregado')
                                            ->select('ventas.id', 'detalle_ventas.status_entrega')
                                            ->latest('ventas.created_at')
                                            ->first();
                                    @endphp

                                    @if($pedidoActivo)
                                        <a href="{{ route('carrito.seguimiento', $pedidoActivo->id) }}" 
                                        class="btn btn-seguimiento-nav px-4 py-2 rounded-full font-bold text-center no-underline shadow-sm">
                                            <i class="fas fa-truck-moving me-2 animate-truck"></i> 
                                            Seguimiento: {{ strtoupper(str_replace('_', ' ', $pedidoActivo->status_entrega)) }}
                                        </a>
                                    @endif

                                    {{-- 2. CARRITO CON BADGE DINÁMICO --}}
                                    <a href="{{ route('cart.index') }}" class="btn btn-carrito rounded-pill position-relative text-white">
                                        <i class="fas fa-shopping-cart"></i>    
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
                                            @php
                                                $cart = session('cart', []);
                                                $totalItems = collect($cart)->sum(function($item) {
                                                    return $item['cantidad'] ?? $item['quantity'] ?? 0;
                                                });
                                            @endphp
                                            {{ $totalItems }}
                                        </span>
                                    </a>

                                    {{-- 3. BOTONES DE USUARIO --}}
                                    <a href="{{ route('client.profile') }}" class="btn-florenticaPerfil bg-flower-pink px-4 py-2 rounded-full font-bold text-center no-underline shadow-sm text-white">
                                        Mi Perfil
                                    </a>

                                    <a href="{{ route('premium.index') }}" class="btn-florenticaPremium bg-flower-gold px-4 py-2 rounded-full font-bold text-center no-underline shadow-sm text-white">
                                        Premium
                                    </a>

                                    {{-- 4. CERRAR SESIÓN --}}
                                    <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                                        @csrf
                                        <button type="submit" class="btn-florenticaSesion text-white bg-flower-red px-4 py-2 rounded-full font-bold text-center no-underline shadow-sm w-100 border-0">
                                            Salir
                                        </button>
                                    </form>
                                @else
                                    {{-- Si no está logueado --}}
                                    <a href="{{ route('login') }}" class="btn btn-outline-pink rounded-pill px-4">Iniciar Sesión</a>
                                @endauth

                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        @endif

    @else
        {{-- ==========================================
            NAV PARA INVITADOS (Guest)
        ========================================== --}}
        <nav class="navbar navbar-expand-lg fixed-top bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold text-flower-pink" href="/">🌸 Florentica</a>
                <div class="ms-auto">
                    <a href="{{ route('login') }}" class="btn btn-outline-pink rounded-pill px-4 btn-sm">Ingresar</a>
                </div>
            </div>
        </nav>
    @endauth
</header>

<main>
    @yield('content')
</main>


@php
    $esPremium = auth()->check() && auth()->user()->es_premium;
    // Definimos el color de fondo: negro intenso si es premium, bg-dark normal si no.
    $fondoFooter = $esPremium ? 'background-color: #050505 !important;' : '';
    $bordePremium = $esPremium ? 'border-top: 1px solid #d4af37 !important;' : '';
@endphp

<footer class="text-white pt-5 pb-4 {{ $esPremium ? '' : 'bg-dark' }}" 
        style="{{ $fondoFooter }} {{ $bordePremium }}">
    <div class="container">
        <div class="row">
            {{-- Sección Marca --}}
            <div class="col-md-4 mt-3">
                <h5 class="text-uppercase mb-4 fw-bold {{ $esPremium ? 'text-flower-gold' : 'text-flower-pink' }} border-bottom border-secondary pb-2">
                    Florentica
                </h5>
                <p class="small {{ $esPremium ? 'text-white-50' : 'text-white' }}">
                    Tu florería boutique de confianza en la Ciudad de México. Calidad y frescura garantizada por 
                    <strong class="{{ $esPremium ? 'text-flower-gold' : '' }}">Bollotech</strong>.
                </p>
            </div>

            {{-- Sección Contacto --}}
            <div class="col-md-4 mt-3 text-center">
                <h5 class="text-uppercase mb-4 fw-bold {{ $esPremium ? 'text-flower-gold' : 'text-flower-pink' }}">Contacto</h5>
                <p class="mb-1"><i class="fas fa-map-marker-alt me-2 {{ $esPremium ? 'text-flower-gold' : '' }}"></i> CDMX, México</p>
                <p class="mb-1"><i class="fas fa-phone me-2 {{ $esPremium ? 'text-flower-gold' : '' }}"></i> +52 56 54 02 74 43</p>
            </div>

            {{-- Sección Soporte --}}
            <div class="col-md-4 mt-3 text-md-end">
                <h5 class="text-uppercase mb-4 fw-bold {{ $esPremium ? 'text-flower-gold' : 'text-flower-pink' }}">Soporte</h5>
                <p><a href="#" class="text-white text-decoration-none small hover-gold">Preguntas Frecuentes</a></p>
                <p><a href="#" class="text-white text-decoration-none small hover-gold">Políticas de Privacidad</a></p>
            </div>
        </div>

        {{-- Línea divisoria dinámica --}}
        <hr class="my-4 {{ $esPremium ? 'opacity-25' : 'border-secondary' }}" 
            style="{{ $esPremium ? 'background-color: #d4af37 !important;' : '' }}">

        <div class="text-center">
            <p class="mb-0 small {{ $esPremium ? 'text-white-50' : '' }}">
                © 2026 Florentica. Desarrollado por 
                <strong class="{{ $esPremium ? 'text-flower-gold' : '' }}">Bollotech CEO</strong>
            </p>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>