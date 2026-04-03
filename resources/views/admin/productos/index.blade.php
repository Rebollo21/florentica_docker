@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-flower-pink text-flower-pink">
            <i class="bi bi-arrow-left me-1"></i> Regresar
        </a>
        <h2 class="fw-bold text-flower-pink">🌹 Catálogo de Ramos</h2>
        <button class="btn btn-dark  rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoRamo">
            <i class="bi bi-plus-lg me-1 text-flower-pink"></i> Nuevo Diseño
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light text-muted small text-uppercase">
    <tr>
        <th class="ps-4 text-flower-pink">Nombre / Descripción</th>
        <th class="text-flower-pink">Precio</th>
        <th  class="text-flower-pink">Categoría</th>
        <th  class="text-flower-pink">Imagen</th>
        <th  class="text-flower-pink">Stock (Ramos Armables)</th> {{-- NUEVA COLUMNA --}}
        <th class="text-flower-pink" >Estado</th>
        <th class="text-end pe-4 text-flower-pink text-center">Acciones</th>
    </tr>
</thead>
                <tbody>
                    @foreach($productos as $producto)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $producto->nombre_ramo }}</div>
                            <small class="text-muted">{{ Str::limit($producto->descripcion, 30) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-success-soft text-success border border-success-subtle">
                                ${{ number_format($producto->precio_venta, 2) }}
                            </span>
                        </td>
                        <td><span class="text-muted small">{{ $producto->categoria }}</span></td>
                        <td>
    @php
        // 1. Convertimos la cadena de la BD en un array
        $fotos = $producto->imagen_url ? explode(',', $producto->imagen_url) : [];
        // 2. Tomamos la primera posición [0]
        $fotoPrincipal = count($fotos) > 0 ? $fotos[0] : 'img/no-image.png';
    @endphp

    <div class="position-relative d-inline-block">
        <img src="{{ asset($fotoPrincipal) }}" 
             class="rounded-3 shadow-sm border" 
             style="width: 50px; height: 50px; object-fit: cover;"
             onerror="this.src='{{ asset('img/no-image.png') }}'">
        
        {{-- Indicador de galería si hay más de una foto --}}
        @if(count($fotos) > 1)
            <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-dark opacity-75" style="font-size: 0.6rem;">
                +{{ count($fotos) - 1 }}
            </span>
        @endif
    </div>
</td>
{{-- Busca la fila después de la imagen y antes del estado --}}
<td>
    @php
        $stockArmable = $producto->calcularStockDisponible(); // Llamada al método
    @endphp

    @if($stockArmable > 5)
        <span class="text-success fw-bold">
            <i class="bi bi-check-circle-fill me-1"></i> {{ $stockArmable }} disp.
        </span>
    @elseif($stockArmable > 0 && $stockArmable <= 5)
        <span class="text-warning fw-bold">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> Solo {{ $stockArmable }}
        </span>
    @else
        <span class="text-danger fw-bold">
            <i class="bi bi-x-octagon-fill me-1"></i> Agotado
        </span>
    @endif
    <br>
    <small class="text-muted" style="font-size: 0.65rem;">Según insumos en lote</small>
</td>
                        <td>
                            <span class="badge rounded-pill {{ $producto->activo ? 'bg-flower-pink' : 'bg-secondary' }} px-3" style="font-size: 0.7rem;">
                                {{ $producto->activo ? 'Activo' : 'Desactivado' }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
    <div class="btn-group shadow-sm rounded-pill overflow-hidden border">
        {{-- BOTÓN RECETA: Color Negro para resaltar --}}
        <a href="{{ route('productos.receta', $producto->id) }}" 
           class="btn btn-sm bg-flower-pink px-3" 
           title="Gestionar Insumos">
            <i class="bi bi-tools"> Receta</i>
        </a>

        {{-- BOTÓN EDITAR: Color Gris claro con icono azul --}}
        <button type="button" 
                class="btn btn-sm btn-light border-start border-end px-3" 
                data-bs-toggle="modal" 
                data-bs-target="#modalEditar{{ $producto->id }}">
            <i class="bi bi-pencil-square text-flower-pink">Editar</i>
        </button>

        {{-- BOTÓN ELIMINAR: Color Blanco con icono rojo --}}
        <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button type="submit" 
                    class="btn btn-sm btn-light px-3" 
                    onclick="return confirm('¿Eliminar diseño?')">
                <i class="bi bi-trash text-danger"></i>
            </button>
        </form>
    </div>
</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- SECCIÓN DE MODALES (FUERA DE LA TABLA PARA EVITAR BLOQUEOS) --}}
@foreach($productos as $producto)
    <div class="modal fade" id="modalEditar{{ $producto->id }}" tabindex="-1" aria-labelledby="modalEditarLabel{{ $producto->id }}" aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 rounded-4 shadow-lg">
                @csrf 
                @method('PUT')
                <div class="modal-header border-0 bg-light p-3">
                    <h5 class="fw-bold mb-0" id="modalEditarLabel{{ $producto->id }}">Editar Diseño</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    
                    {{-- GESTIÓN DE GALERÍA INTERACTIVA --}}
                    <div class="mb-4 text-center">
                        <label class="small fw-bold text-muted d-block mb-3 text-uppercase">Galería Actual (Clic en <i class="bi bi-x-circle text-danger"></i> para borrar)</label>
                        
                        @php
                            $fotos = $producto->imagen_url ? explode(',', $producto->imagen_url) : [];
                        @endphp

                        <div class="d-flex flex-wrap gap-2 justify-content-center mb-3">
                            @forelse($fotos as $indice => $foto)
                                <div class="position-relative shadow-sm rounded-3 border overflow-hidden" style="width: 80px; height: 80px;">
                                    <img src="{{ asset($foto) }}" class="w-100 h-100" style="object-fit: cover;">
                                    
                                    {{-- Botón para eliminar foto individual --}}
                                    <button type="button" 
                                            class="position-absolute top-0 end-0 btn btn-danger btn-sm p-0 d-flex align-items-center justify-content-center btn-eliminar-foto" 
                                            style="width: 22px; height: 22px; border-radius: 0 0 0 8px;"
                                            data-producto-id="{{ $producto->id }}"
                                            data-indice="{{ $indice }}"
                                            data-foto-url="{{ $foto }}">
                                        <i class="bi bi-x" style="font-size: 1.2rem;"></i>
                                    </button>
                                </div>
                            @empty
                                <div class="text-muted small p-3 bg-light rounded-3 w-100">Sin imágenes registradas</div>
                            @endforelse
                        </div>

                        <label class="small fw-bold text-muted d-block mb-2 text-uppercase text-start">Añadir más fotos</label>
                        <input type="file" name="imagenes[]" class="form-control form-control-sm border-0 bg-light shadow-sm" accept="image/*" multiple>
                    </div>

                    {{-- INFORMACIÓN BÁSICA --}}
                    <div class="mb-3">
                        <label class="small fw-bold text-muted text-uppercase">Nombre del Ramo</label>
                        <input type="text" name="nombre_ramo" class="form-control border-0 bg-light shadow-sm" value="{{ $producto->nombre_ramo }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="small fw-bold text-muted text-uppercase">Categoría</label>
                            <select name="categoria" class="form-select border-0 bg-light shadow-sm">
                                @foreach(['Temporada', 'Clasicas', 'Ocasión', 'Cumpleaños', 'Premium'] as $cat)
                                    <option value="{{ $cat }}" {{ $producto->categoria == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small fw-bold text-muted text-uppercase">Precio de Venta</label>
                            <div class="input-group shadow-sm rounded">
                                <span class="input-group-text border-0 bg-white text-success fw-bold">$</span>
                                <input type="number" step="0.01" name="precio_venta" class="form-control border-0 bg-light" value="{{ $producto->precio_venta }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted text-uppercase">Descripción Corta</label>
                        <textarea name="description" class="form-control border-0 bg-light shadow-sm" rows="2">{{ $producto->description ?? $producto->descripcion }}</textarea>
                    </div>

                    {{-- SWITCH DE ESTADO --}}
                    <div class="mb-3">
                        <div class="form-check form-switch p-3 bg-light rounded-3 shadow-sm d-flex align-items-center justify-content-between">
                            <label class="form-check-label fw-bold text-dark mb-0" for="switchActivo{{ $producto->id }}">
                                <i class="bi bi-megaphone-fill me-2 text-primary"></i> Visible en Catálogo
                            </label>
                            <input class="form-check-input h4 mb-0" type="checkbox" role="switch" name="activo" id="switchActivo{{ $producto->id }}" value="1" {{ $producto->activo ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4 shadow">Actualizar Producto</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- MODAL NUEVO RAMO (CON  ESTADO) --}}
<div class="modal fade" id="modalNuevoRamo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 rounded-4 shadow-lg">
            @csrf
            <div class="modal-header border-0 bg-dark text-white p-3">
                <h5 class="fw-bold mb-0">Nuevo Diseño Florentica</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase">Nombre del Ramo</label>
                    <input type="text" name="nombre_ramo" class="form-control border-0 bg-light shadow-sm" placeholder="Ej: Ramo Buchón 100 Rosas" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small fw-bold text-muted text-uppercase">Categoría</label>
                        <select name="categoria" class="form-select border-0 bg-light shadow-sm">
                            <option value="Temporada">Temporada</option>
                            <option value="Ocasión">Ocasión</option>
                            <option value="Cumpleaños">Cumpleaños</option>
                            <option value="Premium">Premium</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                </div>

                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase d-block mb-2">Fotos del Diseño</label>
                    <input type="file" name="imagenes[]" class="form-control border-0 bg-light shadow-sm" accept="image/*" required multiple>
                </div>

                <input type="hidden" name="activo" value="1"> {{-- Por defecto se crea activo --}}
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-dark rounded-pill px-4 shadow">Siguiente: Definir Receta</button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- 1. LÓGICA PARA ELIMINAR FOTOS EXISTENTES ---
    const botonesEliminar = document.querySelectorAll('.btn-eliminar-foto');
    
    botonesEliminar.forEach(boton => {
        boton.addEventListener('click', async function() {
            if (!confirm('¿Estás seguro de eliminar esta imagen?')) return;

            const data = {
                producto_id: this.dataset.productoId,
                indice: this.dataset.indice,
                foto_url: this.dataset.fotoUrl,
                _token: '{{ csrf_token() }}'
            };

            try {
                const response = await fetch('{{ route("productos.eliminarFoto") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': data._token },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    // Animación simple para remover el cuadro
                    this.parentElement.style.transition = 'all 0.3s ease';
                    this.parentElement.style.opacity = '0';
                    this.parentElement.style.transform = 'scale(0.5)';
                    setTimeout(() => this.parentElement.remove(), 300);
                } else {
                    alert('Error al eliminar: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error en el servidor.');
            }
        });
    });

    // --- 2. LÓGICA PARA PREVISUALIZAR FOTOS NUEVAS ---
    const inputsFotos = document.querySelectorAll('input[type="file"][name="imagenes[]"]');
    
    inputsFotos.forEach(input => {
        input.addEventListener('change', function() {
            const contenedor = this.closest('.modal-body').querySelector('.d-flex.flex-wrap');
            
            // Si quieres que las nuevas reemplacen la vista previa de las viejas, 
            // podrías limpiar el contenedor, pero aquí las añadiremos al final.
            
            if (this.files) {
                Array.from(this.files).forEach(file => {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'position-relative shadow-sm rounded-3 border overflow-hidden preview-nueva';
                        div.style.width = '80px';
                        div.style.height = '80px';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-100 h-100" style="object-fit: cover; opacity: 0.6;">
                            <span class="position-absolute top-50 start-50 translate-middle badge rounded-pill bg-primary" style="font-size: 0.5rem;">NUEVA</span>
                        `;
                        contenedor.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        });
    });
});
</script>

<style>
    /* Estilo extra para que las fotos nuevas se vean distintas antes de guardar */
    .preview-nueva {
        border: 2px dashed #0d6efd !important;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .btn-eliminar-foto:hover {
        transform: scale(1.1);
        background-color: #ff0000 !important;
    }
</style>