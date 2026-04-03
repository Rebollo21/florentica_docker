@extends('layouts.app')

@section('content')


<div class="container py-5 mt-5">
    <h2 class="fw-bold text-flower-pink mb-4 text-center"><i class="fas fa-shopping-basket me-2"></i>Tu Carrito de Flores</h2>

    {{-- Alertas de Error --}}
    @if ($errors->any() || session('error'))
        <div class="alert alert-danger bg-dark border-danger text-danger rounded-4 shadow-lg mb-4">
            <ul class="mb-0">
                @if(session('error')) <li>{{ session('error') }}</li> @endif
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('cart') && count(session('cart')) > 0)
    <div class="row g-4">
        {{-- Izquierda: Listado y Formulario --}}
        <div class="col-lg-7">
            
            {{-- Tabla de Productos del Carrito - Florentica Dark Edition --}}
<div class="card card-carrito border-0  shadow-lg rounded-4 overflow-hidden mb-4">
    <div class="table-responsive ">
        <table class="table  table-hover align-middle mb-0">
            <thead class="text-muted small text-uppercase">
                <tr>
                    <th class="text-center text-flower-pink">Diseño</th>
                    <th class="text-center text-flower-pink">Precio</th>
                    <th  class="text-center text-flower-pink">Cantidad</th>
                    <th class="text-center text-flower-pink">Subtotal</th>
                    <th class="text-center text-flower-pink">Acción</th>
                </tr>
            </thead>
            <tbody>
                {{-- 
                    1. Iteramos la sesión 'cart'. 
                    Usamos $id como llave (ID del producto) y $details como el array de datos. 
                --}}
                @foreach(session('cart', []) as $id => $details)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            {{-- 
                                2. Lógica de Imagen:
                                Si 'imagen' existe en el array de la sesión, la usamos.
                                Si no, usamos asset() con una ruta por defecto.
                            --}}
                            <img src="{{ asset($details['imagen'] ?? 'imagenes/default.png') }}" 
                                 class="rounded-3 me-3" 
                                 style="width: 50px; height: 50px; object-fit: cover;"
                                 alt="{{ $details['nombre'] }}">
                            
                            <span class="fw-bold">{{ $details['nombre'] }}</span>
                        </div>
                    </td>

                    {{-- 3. Formateo de moneda para el precio unitario --}}
                    <td class="text-success fw-bold">${{ number_format($details['precio'], 2) }}</td>

                    <td class="text-center">
    @php 
        // Buscamos el producto para conocer su stock real en este momento
        $prodModel = \App\Models\Producto::find($id);
        $stockMaximo = $prodModel ? $prodModel->calcularStockDisponible() : 0;
    @endphp

    <div class="d-flex align-items-center justify-content-center gap-2">
        {{-- Botón Restar --}}
        <form action="{{ route('cart.update', $id) }}" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" name="change" value="decrease">
            <button type="submit" class="btn btn-res btn-sm  rounded-circle" 
                    {{ $details['cantidad'] <= 1 ? 'disabled' : '' }}>
                <i class="fas fa-minus small"></i>
            </button>
        </form>

        {{-- Cantidad con aviso de límite --}}
        <div class="text-center">
            <span class="fw-bold mx-2">{{ $details['cantidad'] }}</span>
            @if($details['cantidad'] >= $stockMaximo)
                <br><small class="text-flower-pink" style="font-size: 0.7rem;">Límite alcanzado</small>
            @endif
        </div>

        {{-- Botón Sumar con Bloqueo de Stock --}}
        <form action="{{ route('cart.update', $id) }}" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" name="change" value="increase">
            <button type="submit" class="btn btn-sm btn-sum btn-outline-light rounded-circle" 
                    {{ $details['cantidad'] >= $stockMaximo ? 'disabled' : '' }}>
                <i class="fas fa-plus small"></i>
            </button>
        </form>
    </div>
</td>

                    {{-- 5. Cálculo matemático directo en Blade: Precio x Cantidad --}}
                    <td class="fw-bold text-success">
                        ${{ number_format($details['precio'] * $details['cantidad'], 2) }}
                    </td>

                    <td class="text-end pe-4">
                        {{-- 6. Botón de eliminación: Envía el $id al controlador para hacer session()->forget() --}}
                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger p-0 border-0 bg-transparent">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

            {{-- FORMULARIO DE LOGÍSTICA --}}
            <div class="card card-categorias border-0 bg-dark shadow-lg rounded-4 p-4 text-white">
                <h5 class="fw-bold mb-3 text-flower-pink  border-secondary pb-2">
                    <i class="fas fa-truck me-2"></i>Detalles de Entrega
                </h5>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="small text-flower-pink mb-1">Nombre de quien recibe</label>
                        <input type="text" name="nombre_receptor" form="payment-form" class="form-control shadow-sm" placeholder="Ej. Juan Pérez" required>
                    </div>
                    <div class="col-md-4">
                        <label class="small text-flower-pink mb-1">Teléfono</label>
                        <input type="tel" name="telefono" form="payment-form" class="form-control  shadow-sm" placeholder="55 1234 5678" required>
                    </div>
                    
                    {{-- Dirección Desglosada para GPS --}}
                    <div class="col-md-6">
                        <label class="small text-flower-pink mb-1">Calle</label>
                        <input type="text" name="calle" form="payment-form" class="form-control shadow-sm" required>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-flower-pink mb-1">Num. Ext</label>
                        <input type="text" name="num_ext" form="payment-form" class="form-control shadow-sm" required>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-flower-pink mb-1">Num. Int</label>
                        <input type="text" name="num_int" form="payment-form" class="form-control  shadow-sm" placeholder="Opcional">
                    </div>
                    <div class="col-md-8">
                        <label class="small text-flower-pink mb-1">Colonia</label>
                        <input type="text" name="colonia" form="payment-form" class="form-control  shadow-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label class="small text-flower-pink mb-1">C.P.</label>
                        <input type="text" name="codigo_postal" form="payment-form" class="form-control  shadow-sm" required>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-flower-pink mb-1">Municipio / Alcaldía</label>
                        <input type="text" name="municipio" form="payment-form" class="form-control  shadow-sm" required>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-flower-pink mb-1">Referencias</label>
                        <input type="text" name="referencias" form="payment-form" class="form-control  shadow-sm" placeholder="Ej. Portón azul">
                    </div>
                </div>
            </div>
        </div>

        {{-- Derecha: Resumen de Pago --}}
        <div class="col-lg-5">
            <div class="card card-catalogo border-0 bg-dark shadow-lg rounded-4 p-4  position-sticky" style="top: 100px;">
                <h5 class="fw-bold mb-4 text-flower-pink border-secondary pb-2">Resumen de Venta</h5>
                
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted text-flower-pink">Subtotal:</span>
                    <span class="fw-bold text-flower-green">${{ number_format($total, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted text-flower-pink">Envío:</span>
                    <span class="fw-bold text-flower-green">Gratis (Zona Local)</span>
                </div>
                <hr class="border-secondary">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="h5 mb-0 text-flower-pink">Total:</span>
                    <span class="h3 fw-bold text-flower-green mb-0">${{ number_format($total, 2) }}</span>
                </div>

                {{-- Formulario Stripe --}}
                <form action="{{ route('checkout.procesar') }}" method="POST" id="payment-form">
                    @csrf
                    <div class="mb-4">
                        <label class="text-flower-pink small mb-2">Datos de la Tarjeta</label>
                        <div id="card-element" class="form-control text-flower-pink border-secondary p-3 shadow-sm"></div>
                        <div id="card-errors" role="alert" class="text-danger mt-2 small"></div>
                    </div>

                    <button type="submit" id="submit-button" class="btn btn-outline-pink btn-pago w-100 rounded-pill py-3 fw-bold shadow">
                        <i class="fas fa-check-circle me-2"></i>Finalizar y Pagar Pedido
                    </button>
                </form>

                <a href="{{ url('/shop') }}" class="btn btn-outline-light btn-carrito w-100 rounded-pill mt-3 btn-sm border-0">
                    <i class="fas fa-arrow-left me-2"></i>Seguir Comprando
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fas fa-shopping-cart fa-4x text-muted opacity-25 text-flower-pink"></i>
        </div>
        <h3 class="text-flower-pink fw-bold">Tu carrito está vacío</h3>
        <p class="text-muted text-flower-pink">¡Aún no has añadido flores para esa persona especial!</p>
        <a href="{{ url('/shop') }}" class="btn btn-outline-pink  btn-florenticaPerfil rounded-pill px-5 mt-3 fw-bold">Explorar Diseños</a>
    </div>
    @endif
</div>



<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Asegúrate de usar tu llave pública de Stripe aquí
        const stripe = Stripe("{{ env('STRIPE_KEY') }}");
        const elements = stripe.elements();

        // Estilo personalizado para que se vea bien en tu interfaz rosa/oscura
        const style = {
            base: {
                color: '#ff69b4', // Color text-flower-pink
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        const card = elements.create('card', { style: style });
        
        // El ID debe coincidir con tu <div id="card-element">
        card.mount('#card-element');

        // Manejo de errores en tiempo real
        card.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Manejo del envío del formulario
        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const submitButton = document.getElementById('submit-button');
            submitButton.disabled = true;

            const { token, error } = await stripe.createToken(card);

            if (error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
                submitButton.disabled = false;
            } else {
                // Insertar el token en el formulario y enviar
                const hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);
                form.submit();
            }
        });
    });
</script>
@endsection