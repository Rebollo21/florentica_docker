@extends('layouts.app')

@section('content')
{{--1- Empieza nav --}}
<nav class="navbar navbar-expand-lg navbar-light bg-flower-pink-light shadow-sm fixed-top">
    
    <div class="container">
        
        <a class="navbar-brand fw-bold text-flower-pink" href="/shop">🌸 Florentica</a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navFlorentica">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navFlorentica">
            <div class="ms-auto pt-3 pt-lg-0">
                <div class="d-grid d-lg-flex gap-3 align-items-center">
                    <a href="/profile" 
                       class="btn-florentica bg-flower-pink px-4 py-2 rounded-full font-bold text-center no-underline shadow-sm">
                    Mi perfil
                    </a>

                    <a href="/profile" 
                       class="btn-florentica bg-flower-gold px-4 py-2 rounded-full font-bold text-center no-underline shadow-sm text-white">
                    Premium
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn-florentica text-white bg-flower-red px-4 py-2 rounded-full font-bold text-center no-underline shadow-sm">Cerrar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
{{-- 1 termina nav --}}


<div class="container py-5 mt-4 bg-flower-pink-light">
    <div class="row">
        <div class="col-md-6 mb-4">
            @php
                // Convertimos el string de la BD en un array de rutas
                $fotos = explode(',', $producto->imagen_url);
                $fotoPrincipal = $fotos[0];
            @endphp
  

            <div class="main-image-container mb-3">
                <img src="{{ asset($fotoPrincipal) }}" 
                     id="mainImage"
                     class="img-fluid rounded-5 shadow-lg" 
                     alt="{{ $producto->nombre_ramo }}"
                     style="width: 100%; height: 500px; object-fit: cover; transition: 0.3s;">
            </div>

            @if(count($fotos) > 1)
            <div class="d-flex gap-2 overflow-auto pb-2">
                @foreach($fotos as $foto)
                    <img src="{{ asset(trim($foto)) }}" 
                         class="rounded-3 shadow-sm thumbnail-gallery" 
                         style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 2px solid transparent;"
                         onclick="changeImage('{{ asset(trim($foto)) }}', this)">
                @endforeach
            </div>
            @endif
        </div>

        <div class="col-md-6 ps-md-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="/shop" class="text-decoration-none text-muted">Tienda</a></li>
                    <li class="breadcrumb-item active text-pink">{{ $producto->categoria }}</li>
                </ol>
            </nav>
            
            <h1 class="display-5 fw-bold text-dark">{{ $producto->nombre_ramo }}</h1>
            <p class="fs-5 text-muted mb-4">{{ $producto->descripcion }}</p>
            
            <h2 class="text-pink fw-bold mb-4 display-6">${{ number_format($producto->precio_venta, 2) }}</h2>
            
            <div class="d-grid gap-2">
                <button class="btn btn-pink btn-lg rounded-pill shadow-sm py-3 fw-bold">
                    <i class="bi bi-cart-plus-fill me-2"></i> Agregar al carrito
                </button>
            </div>
            
            <hr class="my-4">
            <div class="d-flex align-items-center p-3 bg-light rounded-4">
                <i class="bi bi-truck fs-3 text-pink me-3"></i>
                <div>
                    <p class="mb-0 fw-bold small">Envío Express</p>
                    <p class="small text-muted mb-0">Disponible para entrega hoy mismo en CDMX.</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT PARA INTERCAMBIAR IMÁGENES --}}
<script>
function changeImage(src, element) {
    // Cambiar la imagen principal
    const mainImg = document.getElementById('mainImage');
    mainImg.style.opacity = '0';
    
    setTimeout(() => {
        mainImg.src = src;
        mainImg.style.opacity = '1';
    }, 200);

    // Resaltar miniatura activa
    document.querySelectorAll('.thumbnail-gallery').forEach(img => {
        img.style.borderColor = 'transparent';
    });
    element.style.borderColor = '#ff69b4'; // Color pink de tu marca
}
</script>

<style>
    .text-pink { color: #ff69b4; }
    .btn-pink { background-color: #ff69b4; color: white; border: none; }
    .btn-pink:hover { background-color: #e05da3; color: white; }
    .thumbnail-gallery:hover { border-color: #ff69b4 !important; }
</style>
@endsection