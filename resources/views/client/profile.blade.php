<!DOCTYPE html>
<html lang="es">

<head>
    
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Tienda - Florentica</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<!DOCTYPE html>
<html lang="es">

<head>
    
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Tienda - Florentica</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

@extends('layouts.app')

@section('content')


@auth
    @if(auth()->user()->es_premium)
        {{-- Contenido EXCLUSIVO para Socios Elite --}}
        <div class="card border-gold bg-dark text-white">
            <div class="card-body">
                <h5 class="text-flower-gold"><i class="fas fa-crown"></i> Beneficio Elite Activo</h5>
                <p>Tienes 20% de descuento en toda la tienda por ser Premium.</p>
                <span class="badge bg-success">Envío Gratis Aplicado</span>
            </div>
        </div>
    @else
        {{-- Vista para el plan basico --}}
    @endif
@endauth


<div class="container mt-5 py-5">
    <div class="row justify-content-center g-4">
        
        {{-- 1. SECCIÓN DE PERFIL --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold text-flower-pink m-0"><i class="fas fa-user-circle me-2"></i> Mi Perfil</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item border-0 px-4 py-3">
                            <small class="text-muted d-block fw-bold uppercase">Nombre</small>
                            <span class="text-dark">{{ auth()->user()->name }}</span>
                        </li>
                        <li class="list-group-item border-0 px-4 py-3">
                            <small class="text-muted d-block fw-bold">Correo</small>
                            <span>{{ auth()->user()->email }}</span>
                        </li>
                        <li class="list-group-item border-0 px-4 py-3">
                            <small class="text-muted d-block fw-bold">Miembro desde</small>
                            <span>{{ auth()->user()->created_at->format('d/m/Y') }}</span>
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    <button class="btn btn-florenticaPerfil w-100 rounded-pill btn-sm">Editar Información</button>
                </div>
            </div>
        </div>

        {{-- 2. SECCIÓN DE MIS COMPRAS (HISTORIAL REAL) --}}
<div class="col-md-8">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-flower-dark m-0">🛍️ Historial de Pedidos</h5>
            <span class="badge bg-flower-pink-light text-flower-pink rounded-pill">Total: {{ $detalle_ventas->count() }}</span>
        </div>
        
        
        
        <div class="card-body">
    {{-- USAMOS $detalle_ventas PORQUE ASÍ VIENE DESDE EL CONTROLADOR --}}
    @if($detalle_ventas->isEmpty())
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="fas fa-shopping-bag fa-3x text-light"></i>
            </div>
            <h6 class="text-muted fw-bold">Aún no has realizado pedidos</h6>
            <p class="small text-muted">¡Tus flores favoritas aparecerán aquí!</p>
            <a href="{{ route('shop.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4 text-decoration-none">Ir a la tienda</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th>Pedido #</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- CAMBIADO A $detalle_ventas --}}
                    @foreach($detalle_ventas as $pedido)
                        <tr>
                            <td class="fw-bold text-flower-pink">
                                {{ number_format($pedido->id) }}
                            </td>
                            <td class="text-muted">
                                {{ $pedido->created_at->format('d M, Y') }}
                            </td>
                            <td class="fw-bold text-dark">
                                ${{ number_format($pedido->total, 2) }}
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($pedido->status) {
                                        'entregado' => 'bg-success',
                                        'pendiente' => 'bg-warning text-dark',
                                        'cancelado' => 'bg-danger',
                                        'en camino' => 'bg-info text-white',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} rounded-pill px-3 text-capitalize">
                                    {{ $pedido->status ?? 'Pagado' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Si usas ->paginate() en el controlador, esto mostrará los números de página --}}
        @if(method_exists($detalle_ventas, 'links'))
            <div class="mt-3">
                {{ $detalle_ventas->links() }}
            </div>
        @endif
    @endif
</div>

</div>

    </div>
</div>



@endsection