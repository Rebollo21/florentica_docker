@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card bg-dark text-white rounded-4 border-0 shadow-lg">
        <div class="card-body p-4">
            <a href="{{ route('delivery.index') }}" class="text-secondary mb-3 d-inline-block text-decoration-none">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
            
            <h3 class="fw-bold mb-4">Detalle de Entrega #{{ $delivery->venta_id }}</h3>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="text-secondary small text-uppercase fw-bold">Receptor</label>
                    <p class="fs-5">{{ $delivery->nombre_receptor }}</p>
                    
                    <label class="text-secondary small text-uppercase fw-bold mt-2">Teléfono</label>
                    <p><a href="tel:{{ $delivery->telefono }}" class="btn btn-sm btn-outline-info rounded-pill">
                        <i class="fas fa-phone me-1"></i> {{ $delivery->telefono }}
                    </a></p>
                </div>
                
                <div class="col-md-6 mb-4">
                    <label class="text-secondary small text-uppercase fw-bold">Dirección</label>
                    <p>{{ $delivery->calle }} #{{ $delivery->num_ext }}, Col. {{ $delivery->colonia }}</p>
                    
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($delivery->calle . ' ' . $delivery->num_ext . ' ' . $delivery->colonia . ' ' . $delivery->municipio) }}" 
                       target="_blank" class="btn btn-primary rounded-pill w-100">
                        <i class="fas fa-directions me-2"></i> Abrir en Maps
                    </a>
                </div>
            </div>

            <hr class="border-secondary">
            
            <div class="text-center mt-4">
                <form action="{{ route('delivery.update', $delivery->id) }}" method="POST">
                    @csrf @method('PATCH')
                    @if($delivery->status_entrega == 'preparacion')
                        <input type="hidden" name="status" value="en_ruta">
                        <button class="btn btn-lg btn-warning rounded-pill px-5">Iniciar Entrega Now 🚀</button>
                    @else
                        <input type="hidden" name="status" value="entregado">
                        <button class="btn btn-lg btn-success rounded-pill px-5">¡Misión Cumplida! Entregado</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection