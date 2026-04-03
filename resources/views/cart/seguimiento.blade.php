@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h2 class="fw-bold text-flower-pink mb-4">
                <i class="fas fa-box-open me-2"></i>Estado de tu Pedido #{{ $pedido->id }}
            </h2>

            <div class="card card-carrito border-0 shadow-lg rounded-4 p-5" style="background: #111;">
                {{-- Barra de Progreso --}}
<div class="position-relative mb-5 mt-4 px-lg-4"> {{-- Añadido padding lateral para más aire --}}
    <div class="progress bg-dark" style="height: 8px;"> {{-- Un poco más delgada para móvil --}}
        @php
            $progressMap = [
                'preparacion' => 15, 
                'Pagado'      => 15, 
                'en_ruta'     => 50, 
                'entregado'   => 100
            ];
            $statusActual = $entrega->status_entrega ?? $pedido->estatus;
        @endphp
        <div id="progress-bar" class="progress-bar bg-compra shadow-sm" role="progressbar" 
             style="width: {{ $progressMap[$statusActual] ?? 10 }}%; transition: 1.5s ease-in-out;">
        </div>
    </div>

    {{-- Iconos de Estado --}}
    <div class="d-flex justify-content-between position-absolute top-50 start-0 w-100 translate-middle-y px-0">
        
        {{-- Paso 1: Preparación --}}
        <div class="step text-center">
            <div id="step-preparacion" class="step-icon rounded-circle {{ in_array($statusActual, ['preparacion', 'Pagado', 'en_ruta', 'entregado']) ? 'bg-compra text-white' : 'bg-dark text-muted border border-secondary' }}">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <small class="step-text text-flower-pink fw-bold d-block mt-2">Preparando</small>
        </div>
        
        {{-- Paso 2: En Ruta --}}
        <div class="step text-center">
            <div id="step-en_ruta" class="step-icon rounded-circle {{ in_array($statusActual, ['en_ruta', 'entregado']) ? 'bg-compra text-white' : 'bg-dark text-muted border border-secondary' }}">
                <i class="fas fa-motorcycle"></i>
            </div>
            <small class="step-text text-flower-pink fw-bold d-block mt-2">En Ruta</small>
        </div>

        {{-- Paso 3: Entregado --}}
        <div class="step text-center">
            <div id="step-entregado" class="step-icon rounded-circle {{ $statusActual == 'entregado' ? 'bg-success text-white' : 'bg-dark text-muted border border-secondary' }}">
                <i class="fas fa-check-double"></i>
            </div>
            <small class="step-text text-flower-pink fw-bold d-block mt-2">Entregado</small>
        </div>
    </div>
</div>

                <div class="mt-5 pt-4">
                    <div id="status-badge" class="badge bg-compra border border-flower-pink p-3 mb-3" style="font-size: 1.1rem;">
                        Estado: {{ strtoupper(str_replace('_', ' ', $statusActual)) }}
                    </div>
                    <h4 class="text-flower-pink mt-2">Estimado de Entrega: <span class="text-flower-dark">Hoy mismo</span></h4>
                    <p class="text-muted small">Tu ramo de <strong>Florentica</strong> está en proceso logístico.</p>
                </div>

                <a href="{{ url('/shop') }}" class="btn btn-regresar btn-outline-pink rounded-pill mt-4 px-4">
                    Volver a la Tienda
                </a>
            </div>
        </div>
    </div>
</div>



{{-- Script de actualización en tiempo real --}}
<script>
    const pedidoId = {{ $pedido->id }};
    const pMap = { 'preparacion': 15, 'Pagado': 15, 'en_ruta': 50, 'entregado': 100 };

    function fetchStatus() {
        fetch(`/api/pedido/status/${pedidoId}`)
            .then(res => res.json())
            .then(data => {
                const s = data.status;
                
                // Actualizar barra y texto
                document.getElementById('progress-bar').style.width = (pMap[s] || 10) + '%';
                document.getElementById('status-badge').innerText = 'Estado: ' + s.toUpperCase().replace('_', ' ');

                // Iluminar círculos dinámicamente
                if (['en_ruta', 'entregado'].includes(s)) {
                    const el = document.getElementById('step-en_ruta');
                    el.classList.add('bg-compra', 'text-white');
                    el.classList.remove('bg-dark', 'text-muted');
                }
                if (s === 'entregado') {
                    const el = document.getElementById('step-entregado');
                    el.classList.add('bg-success', 'text-white');
                    el.classList.remove('bg-dark', 'text-muted');
                }
            })
            .catch(e => console.error("Error rastreo:", e));
    }

    // Consultar cada 5 segundos
    setInterval(fetchStatus, 5000);
</script>
@endsection