@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    {{-- Encabezado con Estilo --}}
    <div class="text-center mb-5 animate-fade-in">
        <h1 class="fw-bold display-4 text-flower-gold">Florentica <span class="text-flower-pink">Premium</span></h1>
        <p class="text-muted fs-5">Llevando el arte de regalar flores al siguiente nivel.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            {{-- Card Principal Premium --}}
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-dark text-white card-premium-glow">
                <div class="row g-0">
                    {{-- Lado Izquierdo: Precio --}}
                    <div class="col-md-5 bg-flower-gold d-flex align-items-center justify-content-center p-5">
                        <div class="text-center">
                            <div class="premium-icon-circle mb-3">
                                <i class="fas fa-crown fa-3x text-flower-gold"></i>
                            </div>
                            <h2 class="fw-bold text-white mb-0">$149</h2>
                            <p class="text-white-50 small">Pesos Mexicanos / Mes</p>
                            <span class="badge bg-white text-flower-gold rounded-pill px-3 py-2 fw-bold">PLAN ELITE</span>
                        </div>
                    </div>

                    {{-- Lado Derecho: Beneficios --}}
                    <div class="col-md-7 p-4 p-md-5">
                        <h4 class="fw-bold mb-4 text-flower-pink">Tus Beneficios Exclusivos</h4>
                        
                        <ul class="list-unstyled mb-5">
                            <li class="mb-3 d-flex align-items-start">
                                <i class="fas fa-shipping-fast text-flower-gold mt-1 me-3"></i>
                                <div>
                                    <strong class="d-block  text-flower-pink">Envíos Gratis Ilimitados</strong>
                                    <small class="text-muted  text-flower-dark">Ahorra en cada entrega sin importar la distancia.</small>
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="fas fa-bolt text-flower-gold mt-1 me-3"></i>
                                <div>
                                    <strong class="d-block  text-flower-pink " >Prioridad de Elaboración</strong>
                                    <small class="text-muted  text-flower-dark">Tus ramos pasan al frente de la fila en fechas pico.</small>
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="fas fa-gem text-flower-gold mt-1 me-3"></i>
                                <div>
                                    <strong class="d-block  text-flower-pink">Acceso a Ediciones Limitadas</strong>
                                    <small class="text-muted  text-flower-dark">Flores exóticas y diseños solo para miembros.</small>
                                </div>
                            </li>
                        </ul>

                        {{-- Lógica de Botones Dinámicos --}}
<div class="d-grid gap-2">
    @if(auth()->user()->es_premium)
        <div class="alert alert-success border-0 rounded-pill text-center py-3">
            <i class="fas fa-check-circle me-2"></i><strong>¡Suscripción Elite Activa!</strong>
        </div>
        {{-- Botón de cancelación normal --}}
        <form action="{{ route('premium.cancelar') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-link text-muted btn-sm w-100 no-underline">Cancelar beneficios</button>
        </form>
    @else
        {{-- Formulario que lanza Stripe --}}
        <form action="{{ route('premium.suscribir') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-gold-premium btn-lg rounded-pill fw-bold w-100 shadow py-3">
                <i class="fab fa-stripe me-2"></i> PAGAR $200.00 MXN
            </button>
        </form>
        <p class="text-center text-muted x-small mt-2">Pago seguro procesado por Stripe.</p>
    @endif
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Estilos Específicos para el Modo Premium --}}
<style>
    :root {
        --flower-gold: #d4af37;
        --flower-pink: #ff2d55;
    }

    .text-flower-gold { color: var(--flower-gold) !important; }
    .bg-flower-gold { background-color: var(--flower-gold) !important; }
    
    .card-premium-glow {
        border: 1px solid rgba(212, 175, 55, 0.2) !important;
        box-shadow: 0 0 30px rgba(212, 175, 55, 0.1) !important;
    }

    .premium-icon-circle {
        background: white;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .btn-gold-premium {
        background: linear-gradient(45deg, #d4af37, #f1c40f);
        color: white;
        border: none;
        padding: 15px;
        transition: 0.4s;
    }

    .btn-gold-premium:hover {
        transform: scale(1.03);
        box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
        color: white;
    }

    .x-small { font-size: 0.7rem; }

    @media (max-width: 768px) {
        .bg-flower-gold {
            padding: 30px !important;
        }
    }

    /* Animación suave */
    .animate-fade-in {
        animation: fadeIn 1s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection