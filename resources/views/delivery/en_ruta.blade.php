@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning fw-bold m-0">🚚 Mis Entregas Activas</h2>
        <span class="badge bg-dark border border-warning text-warning px-3 py-2 rounded-pill">
            {{ $enRuta->count() }} Pedidos
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        @forelse($enRuta as $pedido)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card bg-dark border-warning border-2 rounded-4 shadow-lg overflow-hidden h-100">
                    <div class="card-header bg-warning text-dark fw-bold py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-box-open me-2"></i>Entrega #{{ $pedido->id }}</span>
                            <small class="fw-bold">ID Venta: {{ $pedido->detalle_venta_id }}</small>
                        </div>
                    </div>
                    
                    <div class="card-body text-white">
                        <h5 class="fw-bold mb-3 text-truncate">{{ $pedido->nombre_receptor ?? 'Cliente sin nombre' }}</h5>
                        
                        <div class="mb-3">
                            <p class="mb-1 text-secondary small text-uppercase fw-bold">Dirección de Entrega:</p>
                            <p class="mb-0 small">
                                <i class="fas fa-map-marker-alt text-warning me-2"></i>
                                {{ $pedido->calle }} {{ $pedido->num_ext }}, {{ $pedido->colonia }}
                            </p>
                        </div>

                        <div class="d-grid gap-2">
                            {{-- BOTÓN HACIA LA PÁGINA 3 (MAPA) --}}
                            <a href="{{ route('delivery.mapa', $pedido->id) }}" class="btn btn-primary btn-lg rounded-pill fw-bold shadow">
                                <i class="fas fa-route me-2"></i> INICIAR GPS
                            </a>

                            {{-- FORMULARIO PARA FINALIZAR ENTREGA CON FOTO Y PIN --}}
<form action="{{ route('delivery.update', $pedido->id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="entregado">

    {{-- 1. Captura de Foto --}}
    <div class="mb-2">
        <label class="text-secondary small fw-bold mb-1"><i class="fas fa-camera me-1"></i> FOTO DE EVIDENCIA:</label>
        <input type="file" name="foto_evidencia" accept="image/*" capture="environment" 
               class="form-control form-control-sm bg-dark text-white border-secondary" required>
    </div>

    {{-- 2. Código de Seguridad (PIN) --}}
    <div class="mb-3">
        <label class="text-secondary small fw-bold mb-1"><i class="fas fa-key me-1"></i> PIN DEL CLIENTE:</label>
        <input type="text" name="pin_confirmacion" 
               class="form-control bg-dark text-warning border-warning text-center fw-bold" 
               placeholder="Ingrese el PIN de 14 dígitos" maxlength="14" required>
    </div>

    <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold shadow-sm">
        <i class="fas fa-check-circle me-1"></i> Finalizar Entrega Segura
    </button>
</form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="card bg-dark border-secondary p-5 rounded-5 border-dashed">
                    <i class="fas fa-truck-loading fa-4x text-secondary mb-3"></i>
                    <h4 class="text-white">No tienes pedidos en ruta</h4>
                    <p class="text-muted mb-4">Ve al panel de despacho para escanear nuevos pedidos.</p>
                    <a href="{{ route('delivery.index') }}" class="btn btn-info rounded-pill px-4 fw-bold">
                        <i class="fas fa-boxes me-2"></i> Ir a Despacho
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    .card { transition: transform 0.3s ease; }
    .card:hover { transform: translateY(-5px); }
    .border-dashed { border-style: dashed !important; border-width: 2px !important; }
    .text-truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
</style>
@endsection