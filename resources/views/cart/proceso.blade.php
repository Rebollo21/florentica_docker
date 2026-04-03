@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h2 class="fw-bold text-flower-pink mb-4">
                <i class="fas fa-box-open me-2"></i>Estado de tu Pedido #{{ $pedido->id }}
            </h2>

            <div class="card card-carrito border-0 shadow-lg rounded-4 p-5">
                {{-- Barra de Progreso --}}
                <div class="position-relative mb-5">
                    <div class="progress bg-dark" style="height: 10px;">
                        @php
                            $progress = ['preparacion' => 25, 'en_camino' => 60, 'entregado' => 100];
                        @endphp
                        <div class="progress-bar bg-success shadow-sm" role="progressbar" 
                             style="width: {{ $progress[$pedido->status] ?? 10 }}%; transition: 1s;">
                        </div>
                    </div>

                    {{-- Iconos de Estado --}}
                    <div class="d-flex justify-content-between position-absolute top-50 start-0 w-100 translate-middle-y px-1">
                        <div class="step">
                            <div class="rounded-circle {{ $pedido->status == 'preparacion' ? 'bg-success' : 'bg-dark border border-secondary' }} p-3">
                                <i class="fas fa-hand-holding-heart {{ $pedido->status == 'preparacion' ? 'text-white' : 'text-muted' }}"></i>
                            </div>
                            <small class="text-white mt-2 d-block">Preparando</small>
                        </div>
                        <div class="step">
                            <div class="rounded-circle {{ $pedido->status == 'en_camino' ? 'bg-success' : 'bg-dark border border-secondary' }} p-3">
                                <i class="fas fa-motorcycle {{ $pedido->status == 'en_camino' ? 'text-white' : 'text-muted' }}"></i>
                            </div>
                            <small class="text-white mt-2 d-block">En Camino</small>
                        </div>
                        <div class="step">
                            <div class="rounded-circle {{ $pedido->status == 'entregado' ? 'bg-success' : 'bg-dark border border-secondary' }} p-3">
                                <i class="fas fa-check-double {{ $pedido->status == 'entregado' ? 'text-white' : 'text-muted' }}"></i>
                            </div>
                            <small class="text-white mt-2 d-block">Entregado</small>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4">
                    <h4 class="text-white">Estimado de Entrega: <span class="text-flower-green">Hoy mismo</span></h4>
                    <p class="text-muted small">Tu ramo está siendo elaborado con las flores más frescas de nuestro inventario.</p>
                </div>

                <a href="{{ url('/shop') }}" class="btn btn-outline-pink rounded-pill mt-4 px-4">
                    Volver a la Tienda
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .step div { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; z-index: 2; transition: 0.5s; }
</style>
@endsection