@extends('layouts.app')

@section('content')
@php
    // 1. Inversión (Costo Compra de Lotes)
    $totalCostoCompra = $producto->insumos->sum(fn($i) => $i->pivot->cantidad * ($i->ultimoLote->costo_unitario ?? 0));
    
    // 2. Venta de Materiales (Precio de Venta Sugerido en Lotes)
    $subtotalVentaInsumos = $producto->insumos->sum(fn($i) => $i->pivot->cantidad * ($i->ultimoLote->precio_venta_lote ?? 0));
    
    // 3. Estrategia Logística
    $tarifaLogisticaFija = 150;
    $precioSugeridoFinal = $subtotalVentaInsumos + $tarifaLogisticaFija;
@endphp

<div class="container py-5">
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        {{-- HEADER DEL PANEL --}}
        <div class="card-header bg-dark py-3 border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center text-white">
                    <img src="{{ asset($producto->primera_imagen ?? 'img/no-image.png') }}" class="rounded-3 me-3" style="width: 60px; height: 60px; object-fit: cover;">
                    <div>
                        <h3 class="fw-bold mb-0 text-white">{{ $producto->nombre_ramo }}</h3>
                        <small class="opacity-75">Gestión de Insumos y Costos Operativos</small>
                    </div>
                </div>
                <div class="text-end text-white">
                    <span class="d-block small opacity-75">Subtotal Materiales</span>
                    <h4 class="fw-bold mb-0 text-success">${{ number_format($subtotalVentaInsumos, 2) }}</h4>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="row g-0">
                {{-- COLUMNA IZQUIERDA: CONFIGURACIÓN DE PRECIO --}}
                <div class="col-lg-4 bg-light p-4 border-end">
                    <h5 class="fw-bold mb-3 small text-muted text-uppercase">1. Añadir al Diseño</h5>
                    <form action="{{ route('productos.receta.guardar') }}" method="POST" class="mb-4">
                        @csrf
                        <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                        <div class="mb-2">
                            <select name="insumo_id" class="form-select border-0 shadow-sm" required>
                                <option value="" disabled selected>Seleccionar Insumo...</option>
                                @foreach($insumos as $insumo)
                                    <option value="{{ $insumo->id }}">{{ $insumo->nombre_insumo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="number" name="cantidad" step="0.1" class="form-control border-0 shadow-sm" placeholder="Cantidad (ej. 12)" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm">
                            <i class="fas fa-plus me-1"></i> Agregar Insumo
                        </button>
                    </form>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3 small text-muted text-uppercase">2. Estrategia de Venta</h5>
                    <div class="p-3 bg-white rounded-4 shadow-sm border border-primary-subtle">
                        <div class="alert alert-info py-2 px-3 border-0 small mb-3">
                            <i class="fas fa-truck-loading me-1"></i> 
                            <b>Gastos Fijos:</b> +$150.00 <br>
                            <span class="fw-bold">Precio Sugerido: ${{ number_format($precioSugeridoFinal, 2) }}</span>
                        </div>

                        <form action="{{ route('productos.update.precio', $producto->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="mb-3">
                                <label class="small text-muted fw-bold">Precio Final Definido</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 fw-bold">$</span>
                                    <input type="number" name="precio_venta" class="form-control border-0 bg-light fw-bold text-primary" 
                                           value="{{ $producto->precio_venta > 0 ? $producto->precio_venta : number_format($precioSugeridoFinal, 2, '.', '') }}" 
                                           step="0.01" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold shadow-sm">
                                <i class="fas fa-save me-1"></i> Guardar Precio Final
                            </button>
                        </form>
                    </div>
                </div>

                {{-- COLUMNA DERECHA: TABLA DE INSUMOS --}}
                <div class="col-lg-8 p-4 bg-white">
                    <h5 class="fw-bold mb-3 small text-muted text-uppercase">Desglose de la Receta</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light small">
                                <tr>
                                    <th>Insumo</th>
                                    <th class="text-center">Cant.</th>
                                    <th>Costo Unit.</th>
                                    <th>Venta Unit.</th>
                                    <th>Subtotal</th>
                                    <th class="text-end">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($producto->insumos as $item)
                                @php
                                    $costoUnit = $item->ultimoLote->costo_unitario ?? 0;
                                    $ventaUnit = $item->ultimoLote->precio_venta_lote ?? 0;
                                    $subtotalFila = $item->pivot->cantidad * $ventaUnit;
                                @endphp
                                <tr>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $item->nombre_insumo }}</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" 
                                                class="btn btn-sm btn-light border rounded-pill px-3 fw-bold btn-editar-cantidad"
                                                data-insumo-id="{{ $item->id }}"
                                                data-cantidad="{{ $item->pivot->cantidad }}"
                                                data-nombre="{{ $item->nombre_insumo }}">
                                            {{ $item->pivot->cantidad }} <i class="fas fa-edit ms-1 text-primary small"></i>
                                        </button>
                                    </td>
                                    <td class="text-muted small">${{ number_format($costoUnit, 2) }}</td>
                                    <td class="text-primary fw-bold">${{ number_format($ventaUnit, 2) }}</td>
                                    <td class="fw-bold">${{ number_format($subtotalFila, 2) }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('productos.receta.eliminar', [$producto->id, $item->id]) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('¿Quitar insumo?')">
                                                <i class="fas fa-minus-circle fa-lg"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No hay materiales en este diseño.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- RESUMEN DE RENTABILIDAD --}}
                    @if($producto->insumos->count() > 0)
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="p-3 border-start border-4 border-secondary bg-light rounded-3">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Inversión Total (Compra)</small>
                                <span class="h5 fw-bold text-dark">${{ number_format($totalCostoCompra, 2) }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border-start border-4 border-primary bg-primary bg-opacity-10 rounded-3">
                                <small class="text-primary d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Ganancia Estimada (Materiales)</small>
                                <span class="h5 fw-bold text-primary">${{ number_format($subtotalVentaInsumos - $totalCostoCompra, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DE EDICIÓN --}}
<div class="modal fade" id="modalEditarInsumo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white border-0 py-2">
                <h6 class="modal-title fw-bold" id="labelInsumo">Editar</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formActualizarReceta">
                    <input type="hidden" id="modal_insumo_id">
                    <div class="mb-3">
                        <label class="small text-muted fw-bold mb-1">Cantidad Necesaria</label>
                        <input type="number" id="modal_cantidad" step="0.1" class="form-control form-control-lg border-0 bg-light fw-bold text-center" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow">
                        Actualizar Cambios
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editModal = new bootstrap.Modal(document.getElementById('modalEditarInsumo'));
    const editForm = document.getElementById('formActualizarReceta');

    // 1. Abrir modal y cargar datos
    document.querySelectorAll('.btn-editar-cantidad').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('labelInsumo').innerText = this.dataset.nombre;
            document.getElementById('modal_insumo_id').value = this.dataset.insumoId;
            document.getElementById('modal_cantidad').value = this.dataset.cantidad;
            editModal.show();
        });
    });

    // 2. Enviar actualización vía AJAX
    editForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const payload = {
            producto_id: "{{ $producto->id }}",
            insumo_id: document.getElementById('modal_insumo_id').value,
            cantidad: document.getElementById('modal_cantidad').value
        };

        try {
            const response = await fetch("{{ route('productos.actualizarReceta') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                location.reload(); // Éxito total
            } else {
                alert('Error de Servidor: ' + (data.message || 'No se pudo actualizar'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error crítico de conexión en Bollotech System');
        }
    });
});
</script>
@endsection