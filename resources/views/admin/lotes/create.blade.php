@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                
                {{-- Alertas de Feedback --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4 mx-4 mt-4" role="alert" style="background-color: #d1e7dd; color: #0f5132;">
                        <div class="d-flex align-items-center">
                            <span class="fs-4 me-2">✅</span>
                            <div>
                                <strong>¡Excelente, Rector!</strong> {{ session('success') }}
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 mx-4 mt-4">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li><i class="fas fa-exclamation-circle me-1"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card-header text-white p-4 rounded-top-4 bg-flower-pink">
                    <h4 class="mb-0 fw-bold uppercase">📦 Registrar Entrada de Lote</h4>
                </div>

                <div class="card-body p-5">
                    <form action="{{ route('lotes.store') }}" method="POST" id="loteForm">
                        @csrf

                        {{-- 1. Selección del Insumo --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Seleccionar Flor o Material</label>
                            <select name="insumo_id" id="insumo_id" class="form-select form-select-lg shadow-sm" required>
                                <option value="">-- Elige del catálogo --</option>
                                @foreach($insumos as $insumo)
                                    <option value="{{ $insumo->id }}" data-tipo="{{ $insumo->tipo }}">
                                        {{ $insumo->tipo == 'flor' ? '🌸' : '🎀' }} {{ $insumo->nombre_insumo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. Cantidad --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Cantidad Recibida</label>
                            <input type="number" name="cantidad_inicial" class="form-control form-control-lg" placeholder="Ej: 100" required min="1">
                        </div>

                        {{-- 3. Bloque Financiero --}}
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-danger">Costo por Unidad ($)</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="costo_unitario" id="costo_unitario" class="form-control" placeholder="0.00" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-success">Precio de Venta ($)</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" step="0.01" name="precio_venta_lote" id="precio_venta_lote" class="form-control border-success" placeholder="0.00" required>
                                    <button type="button" class="btn btn-outline-success" id="btnIA">
                                        <i class="fas fa-robot"></i> ✨ IA
                                    </button>
                                </div>
                                <small id="ia-loading" class="text-muted d-none">Consultando a Gemini...</small>
                            </div>
                        </div>

                        {{-- 4. Freshness Logic --}}
                        <div id="seccion_vida" class="mb-4 p-4 bg-light rounded-4 d-none border-start border-primary border-4">
                            <label class="form-label fw-bold text-primary">🗓️ Días de vida estimados</label>
                            <input type="number" name="vida_flor_dias" id="vida_flor_dias" class="form-control form-control-lg" placeholder="Ej: 7">
                        </div>

                        <hr class="my-4 opacity-25">

                        <div class="d-grid gap-2">
                            <button type="submit" id="btnSubmit" class="btn btn-primary btn-lg py-3 fw-bold shadow-sm uppercase bg-flower-pink">
                                🚀 Cargar al Inventario Real
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-link  text-flower-pink text-decoration-none text-center">
                                Cancelar operación
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT CORREGIDO Y DENTRO DE ETIQUETAS --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Mostrar/Ocultar vida
    document.getElementById('insumo_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const tipo = selectedOption.getAttribute('data-tipo');
        const seccionVida = document.getElementById('seccion_vida');
        const inputVida = document.getElementById('vida_flor_dias');
        
        if (tipo === 'flor') {
            seccionVida.classList.remove('d-none');
            inputVida.setAttribute('required', 'required');
        } else {
            seccionVida.classList.add('d-none');
            inputVida.removeAttribute('required');
        }
    });

    // 2. FUNCIÓN DE GEMINI IA
    document.getElementById('btnIA').addEventListener('click', function() {
        const insumoId = document.getElementById('insumo_id').value;
        const costo = document.getElementById('costo_unitario').value;
        const loader = document.getElementById('ia-loading');
        const btn = this;

        if(!insumoId || !costo || costo <= 0) {
            alert("Rector, primero elige el producto y pon un costo de compra válido.");
            return;
        }

        loader.classList.remove('d-none');
        btn.disabled = true;

        const url = "{{ route('ia.sugerir') }}?insumo_id=" + insumoId + "&costo=" + costo;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if(data.precio) {
                    document.getElementById('precio_venta_lote').value = data.precio;
                }
            })
            .catch(error => alert("Error al conectar con Gemini."))
            .finally(() => {
                loader.classList.add('d-none');
                btn.disabled = false;
            });
    });
});
</script>
@endsection