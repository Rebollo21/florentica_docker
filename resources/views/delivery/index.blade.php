@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    {{-- 1. NAVBAR REINICIADA (Glassmorphism sutil) --}}
    <nav class="navbar navbar-expand-lg fixed-top shadow-sm border-bottom border-pink-light" style="background: rgba(255, 240, 246, 0.8); backdrop-filter: blur(15px);">
        <div class="container py-2">
            <a class="navbar-brand fw-bold text-pink" href="#">
                🌸 Florentica <span class="fw-light">Go</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navFlorentica">
                <i class="fas fa-bars text-pink"></i>
            </button>

            <div class="collapse navbar-collapse" id="navFlorentica">
                <div class="ms-auto pt-3 pt-lg-0 d-flex gap-2">
                    <a href="/mi_perfil" class="btn btn-f-pink rounded-pill px-4">
                       <i class="fas fa-user-circle me-1"></i> Perfil
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div style="margin-top: 100px;"></div>

    {{-- 2. ENCABEZADO LOGÍSTICO --}}
    <div class="text-center mb-5">
        <div class="badge bg-pink-light text-pink rounded-pill px-4 py-2 mb-3 text-uppercase fw-bold" style="letter-spacing: 2px;">
            Panel de Logística
        </div>
        <h1 class="display-6 fw-bold text-dark">Control de Envíos</h1>
        <div class="mx-auto bg-pink" style="width: 40px; height: 4px; border-radius: 2px;"></div>
    </div>

    {{-- 3. DASHBOARD DE ESTADOS --}}
    <div class="row g-4 mb-5">
        <div class="col-6 col-md-6" onclick="focoSeccion('listado-pedidos')">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-3 card-f-hover">
                <div class="text-pink mb-2"><i class="fas fa-boxes fs-2"></i></div>
                <div class="display-5 fw-bold text-dark">{{ count($nuevos) }}</div>
                <div class="small fw-bold text-secondary">EN BODEGA</div>
            </div>
        </div>
        
        <div class="col-6 col-md-6" onclick="location.href='{{ route('delivery.enRuta') }}'">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-3 card-f-hover bg-pink text-white">
                <div class="mb-2"><i class="fas fa-motorcycle fs-2"></i></div>
                <div class="display-5 fw-bold">{{ count($enRuta) }}</div>
                <div class="small fw-bold opacity-75">EN RUTA</div>
            </div>
        </div>
    </div>

    {{-- 4. LISTADO DE SALIDAS --}}
    <div id="listado-pedidos">
        <div class="d-flex justify-content-between align-items-center mb-4 px-2">
            <h5 class="fw-bold m-0 text-dark">Próximas Salidas</h5>
            <span class="badge rounded-pill bg-white text-pink shadow-sm border border-pink-light px-3 py-2">
                {{ count($nuevos) }} pendientes
            </span>
        </div>
        
        <div class="row g-3">
            @forelse($nuevos as $item)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 card-f-hover" onclick="abrirScanner('{{ $item->id }}')">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="icon-shape bg-pink-light text-pink rounded-4 me-3">
                                <i class="fas fa-qrcode fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark mb-0">{{ $item->nombre_receptor }}</div>
                                <div class="text-pink small fw-bold">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $item->colonia }}
                                </div>
                            </div>
                            <div class="text-muted opacity-50"><i class="fas fa-chevron-right"></i></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="opacity-25 mb-3"><i class="fas fa-box-open display-1 text-pink"></i></div>
                    <p class="text-secondary fw-bold">Todo despejado por ahora</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

<style>

</style>
@endsection