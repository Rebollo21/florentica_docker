<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de Control - Florentica</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
    /* 1. LIBERAR ESPACIO LATERAL */
    .container, .container-fluid {
    max-width: 900px;
    }

    /* 2. EVITAR QUE LAS TARJETAS SE AMONTONEN */
    .card {
        margin-bottom: 15px !important;
        border: none !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important;
    }

    .card-header {
        padding: 10px 15px !important;
    }

    /* 3. TABLA ULTRA-COMPACTA (Para que no se vea amontonada) */
    @media (max-width: 768px) {
        /* Reducimos el tamaño de letra de toda la tabla */
        .table {
            font-size: 0.75rem !important;
        }

        /* Quitamos el padding excesivo de las celdas */
        .table td, .table th {
            padding: 8px 4px !important;
            vertical-align: middle;
        }

        /* Ocultamos correos largos en móvil para dar espacio al nombre */
        .d-mobile-none {
            display: none !important;
        }

        /* Ajustamos el buscador para que no tape el título */
        .input-group {
            height: 35px !important;
        }
        
        .btn-sm-custom {
            padding: 2px 6px !important;
            font-size: 0.7rem !important;
        }
    }

    /* 4. COLORES FLORENTICA */
    .text-flower-pink { color: #f2a2c0 !important; }
    .bg-flower-pink { background-color: #f2a2c0 !important; color: white !important; }
                     
                     
                     
.modal-content {
    background-color: #ffffff !important; /* Fondo blanco para que resalte */
    color: #333333 !important; /* Letras oscuras */
    border-radius: 15px !important;
    border: 2px solid #f2a2c0 !important; /* Borde rosa Florentica */
}

.modal-header {
    border-bottom: 1px solid #eee !important;
}

.modal-footer {
    border-top: 1px solid #eee !important;
}

/* Forzar que los inputs se vean bien */
.modal-body input, .modal-body select {
    background-color: #f9f9f9 !important;
    color: #333 !important;
    border: 1px solid #ddd !important;
}

/* Ajuste para que el modal no se amontone en móvil */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 10px !important;
    }
}
                     
                     
                     .modal fade {
    z-index: 9999 !important; /* Asegura que esté al frente de todo */
}
modal fade {
    z-index: 9998 !important; /* La sombra negra también va al frente */
}

.btn-florentica3 {
    
}

/* Ajuste para que el texto no rompa el diseño */
.table td, .table th {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Permitir que el email se vea pequeño pero legible */
.text-truncate-custom {
    max-width: 120px;
    display: inline-block;
    vertical-align: bottom;
}

/* Mejorar el aspecto de los badges de error */
.badge-error {
    font-size: 0.65rem;
    padding: 0.4em 0.6em;
    text-transform: uppercase;
}

</style>
    
</head>

<body class="bg-dark text-white">

@if (session('success'))

    <div class="alert" style="background-color: #d4edda; color: #155724; padding: 15px; border-left: 5px solid #28a745; border-radius: 4px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">

        <hr style="border: 0; border-top: 1px solid #c3e6cb; margin: 10px 0;">

        {{ session('success') }}

    </div>

@endif


<div class="container mt-5 text-center">

    <h1>Bienvenido, Jefe de Florentica: {{ Auth::user()->name }}</h1>        
    
    <p class="lead">Desde aquí podrás gestionar el inventario de flores y las ventas.</p>
        
    <form action="{{ route('logout') }}" method="POST">

        @csrf

        <button type="submit" class="btn btn-danger">Cerrar Sesión</button>

    </form>

</div>


<div class="container mt-5">
    <div class="">
        <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
            <div class="card-body">
                <div class="icon-shape bg-soft-pink text-pink rounded-circle mb-3 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                🌸
                </div>
            <h5 class="fw-bold">Nuevo Diseño</h5>
            <p class="small text-muted">Registra un nuevo ramo con galería de fotos.</p>
            <a href="{{ route('productos.index') }}" class="btn btn-pink w-100 rounded-pill">
                <i class="bi bi-plus-lg me-2"></i>Crear Ramo
            </a>
            </div>
        </div>
    </div>
</div>



<div class="container-fluid py-5 bg-dark min-vh-100">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-pink fw-bold mb-0"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Administrativo</h2>
            <span class="badge bg-secondary px-3 py-2 rounded-pill">Corte al: {{ now()->format('d/m/Y H:i') }}</span>
        </div>

        {{-- 1. Widgets de Indicadores (KPIs) --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card bg-secondary text-white border-0 shadow-sm p-3 rounded-4">
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Ingresos Totales</small>
                    <h3 class="fw-bold text-success mb-0">${{ number_format($ingresosTotales, 2) }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-secondary text-white border-0 shadow-sm p-3 rounded-4">
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Ventas Hoy</small>
                    <h3 class="fw-bold text-info mb-0">{{ $ventasHoy }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-secondary text-white border-0 shadow-sm p-3 rounded-4">
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Alertas de Pago</small>
                    <h3 class="fw-bold text-danger mb-0">{{ $fallosHoy }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-secondary text-white border-0 shadow-sm p-3 rounded-4">
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Limpieza Lotes</small>
                    <h3 class="fw-bold text-warning mb-0">{{ $cantidadArchivados }} <small class="fs-6">archivados</small></h3>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- 2. Tabla de Ventas Recientes --}}
            <div class="col-lg-4">
                <div class="card bg-dark border-secondary shadow-lg rounded-4 h-100 overflow-hidden">
                    <div class="card-header bg-secondary border-0 p-3">
                        <h6 class="mb-0 text-white"><i class="fas fa-check-circle me-2 text-success"></i>Ventas Exitosas</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0" style="font-size: 0.85rem;">
                            <thead>
                                <tr class="text-muted small">
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ventasRecientes as $venta)
                                <tr>
                                    <td><div class="fw-bold">{{ Str::limit($venta->cliente, 15) }}</div><small class="text-muted">#{{ $venta->id }}</small></td>
                                    <td class="text-success fw-bold">${{ number_format($venta->total, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($venta->created_at)->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 3. Tabla de Pagos Fallidos --}}
            <div class="col-lg-4">
                <div class="card bg-dark border-danger shadow-lg rounded-4 h-100 overflow-hidden">
                    <div class="card-header border-0 p-3" style="background-color: rgba(220, 53, 69, 0.1);">
                        <h6 class="mb-0 text-danger"><i class="fas fa-times-circle me-2"></i>Intentos Fallidos</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0" style="font-size: 0.85rem;">
                            <thead>
                                <tr class="text-muted small">
                                    <th>Email</th>
                                    <th>Monto</th>
                                    <th>Error</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pagosFallidos as $fallo)
                                <tr>
                                    <td><span class="small text-truncate d-block" style="max-width: 100px;">{{ $fallo->email_cliente }}</span></td>
                                    <td>${{ number_format($fallo->monto, 2) }}</td>
                                    <td><span class="badge bg-danger" style="font-size: 0.6rem;">{{ Str::limit($fallo->error_mensaje, 20) }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 4. Tabla de Movimientos FIFO (Lo que pediste) --}}
            <div class="col-lg-4">
                <div class="card bg-dark border-info shadow-lg rounded-4 h-100 overflow-hidden">
                    <div class="card-header border-0 p-3" style="background-color: rgba(13, 202, 240, 0.1);">
                        <h6 class="mb-0 text-info"><i class="fas fa-history me-2"></i>Trazabilidad FIFO</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0" style="font-size: 0.85rem;">
                            <thead>
                                <tr class="text-muted small">
                                    <th>Insumo</th>
                                    <th>Lote</th>
                                    <th>Cant.</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach($movimientosLotes as $mov)
<tr>
    <td>
        <div class="fw-bold text-pink">{{ $mov->nombre_insumo }}</div>
        <small class="text-muted">Venta #{{ $mov->folio_venta }}</small>
    </td>
    <td>
        {{-- CAMBIO AQUÍ: Usamos ident_lote que es el alias que definimos --}}
        <code class="text-info">LOTE-{{ str_pad($mov->ident_lote, 4, '0', STR_PAD_LEFT) }}</code>
    </td>
    <td class="text-warning fw-bold">
        -{{ $mov->cantidad_descontada }}
    </td>
</tr>
@endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. Sección de Inventario General y Usuarios --}}
        <div class="mt-5">
            <div class="card bg-dark border-secondary p-4 rounded-4 shadow">
                 <h4 class="text-white mb-4">Métricas de Sistema</h4>
                 <div class="row text-center">
                     <div class="col-md-4 mb-3">
                         <h2 class="text-pink fw-bold">{{ $totalUsuarios }}</h2>
                         <p class="text-muted">Usuarios Activos</p>
                     </div>
                     <div class="col-md-4 mb-3">
                         <h2 class="text-info fw-bold">{{ $totalInsumos }}</h2>
                         <p class="text-muted">Insumos en Catálogo</p>
                     </div>
                     <div class="col-md-4 mb-3">
                         <h2 class="text-danger fw-bold">{{ $totalInactivos }}</h2>
                         <p class="text-muted">Cuentas Suspendidas</p>
                     </div>
                 </div>
            </div>
        </div>
    </div>
</div>




<div class="container mt-5 mb-5">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="fw-bold text-flower-dark m-0">
                <i class="bi bi-currency-dollar me-2 text-success"></i>Resumen Financiero de Existencias
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: #fff0f5;">
                    <tr class="small text-muted text-uppercase">
                        <th class="ps-4">Producto</th>
                        <th class="text-center">Stock Actual</th>
                        <th class="text-center">Valor en Estante (Activo)</th>
                        <th class="text-center">Inversión Histórica (Total)</th>
                        <th class="text-center">Estatus Financiero</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $granTotalActivo = 0; 
                        $granTotalHistorico = 0;
                    @endphp

                    @foreach($insumos as $insumo)
                        @php
                            $lotes = $insumo->lotes()->withTrashed()->get();
                            
                            // Lo que tienes hoy para vender
                            $dineroActivo = $lotes->whereNull('deleted_at')->sum(fn($l) => $l->cantidad_actual * $l->precio_venta_lote);
                            
                            // Todo lo que ha pasado por el sistema
                            $dineroHistorico = $lotes->sum(fn($l) => $l->cantidad_actual * $l->precio_venta_lote);

                            $granTotalActivo += $dineroActivo;
                            $granTotalHistorico += $dineroHistorico;
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark">{{ str_replace('_', ' ', $insumo->nombre_insumo) }}</span>
                                <br>
                                <small class="text-muted text-uppercase" style="font-size: 0.6rem;">{{ $insumo->tipo }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-light text-dark border px-3">
                                    {{ $lotes->whereNull('deleted_at')->sum('cantidad_actual') }} pzas
                                </span>
                            </td>
                            <td class="text-center fw-bold text-success">
                                $ {{ number_format($dineroActivo, 2) }}
                            </td>
                            <td class="text-center text-muted">
                                $ {{ number_format($dineroHistorico, 2) }}
                            </td>
                            <td class="text-center">
                                @if($dineroActivo > 1000)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3">Alto Valor</span>
                                @elseif($dineroActivo > 0)
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-3">En Rotación</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3">Sin Stock</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-light fw-bold">
                    <tr>
                        <td colspan="2" class="text-end ps-4 py-3 text-uppercase">Totales Globales Florentica:</td>
                        <td class="text-center text-success fs-5 py-3">
                            $ {{ number_format($granTotalActivo, 2) }}
                        </td>
                        <td class="text-center text-muted py-3">
                            $ {{ number_format($granTotalHistorico, 2) }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

                            
<div class="container mt-5 mb-5">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-flower-dark m-0">
                <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Análisis de Inversión y Retorno
            </h5>
            <span class="badge bg-light text-dark border">Cifras en MXN</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: #f8f9fa;">
                    <tr class="small text-muted text-uppercase text-center">
                        <th class="ps-4 text-start">Producto</th>
                        <th>Total Invertido</th>
                        <th>Proyección Venta</th>
                        <th>Dinero en Riesgo (Vencidos/Mañana)</th> 
                        <th class="pe-4">Margen Neto</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $globalInversion = 0; 
                        $globalVenta = 0;
                        $globalRiesgo = 0;
                        
                        // Límite: Mañana al final del día
                        $hoy = \Carbon\Carbon::today();
                        $limiteRiesgo = \Carbon\Carbon::tomorrow()->endOfDay();
                    @endphp

                    @foreach($insumos as $insumo)
                        @php
                            // Solo trabajamos con lotes que NO han sido eliminados (trashed)
                            $lotesActivos = $insumo->lotes()->whereNull('deleted_at')->get();

                            // 1. Cálculos base de inversión y venta
                            $inversionSuma = $lotesActivos->sum(fn($l) => $l->cantidad_inicial * ($l->costo_unitario ?? 0));
                            $ventaSuma = $lotesActivos->sum(fn($l) => $l->cantidad_inicial * ($l->precio_venta_lote ?? 0));

                            // 2. Filtrar lotes que vencen entre hoy y mañana
                            $lotesCriticos = $lotesActivos->filter(function($lote) use ($limiteRiesgo) {
                                if (!$lote->fecha_vencimiento) return false;
                                $venc = \Carbon\Carbon::parse($lote->fecha_vencimiento)->startOfDay();
                                return $venc->lessThanOrEqualTo($limiteRiesgo);
                            });

                            // 3. Cálculo de DINERO EN RIESGO: 
                            // Si el costo_unitario es 0, usamos el precio de venta para que NO marque $0.00
                            $dineroEnRiesgo = $lotesCriticos->sum(function($l) {
                                $valorRef = ($l->costo_unitario > 0) ? $l->costo_unitario : $l->precio_venta_lote;
                                return $l->cantidad_actual * ($valorRef ?? 0);
                            });

                            $unidadesEnRiesgo = $lotesCriticos->sum('cantidad_actual');

                            // Acumuladores globales
                            $globalInversion += $inversionSuma;
                            $globalVenta += $ventaSuma;
                            $globalRiesgo += $dineroEnRiesgo;
                            
                            $ganancia = $ventaSuma - $inversionSuma;
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ str_replace('_', ' ', $insumo->nombre_insumo) }}</div>
                                @if($unidadesEnRiesgo > 0)
                                    <div class="badge bg-danger-subtle text-danger p-1 px-2" style="font-size: 0.65rem;">
                                        <i class="bi bi-calendar-x me-1"></i> {{ $unidadesEnRiesgo }} pzas críticas
                                    </div>
                                @endif
                            </td>
                            <td class="text-center text-muted">$ {{ number_format($inversionSuma, 2) }}</td>
                            <td class="text-center text-success fw-bold">$ {{ number_format($ventaSuma, 2) }}</td>
                            
                            <td class="text-center">
                                @if($dineroEnRiesgo > 0)
                                    <div class="d-inline-block p-2 rounded-3 bg-danger text-white shadow-sm animate__animated animate__pulse animate__infinite" style="min-width: 110px;">
                                        <small class="d-block opacity-75" style="font-size: 0.6rem;">PÉRDIDA ESTIMADA</small>
                                        <span class="fw-bold">$ {{ number_format($dineroEnRiesgo, 2) }}</span>
                                    </div>
                                @else
                                    <span class="text-muted small opacity-50"><i class="bi bi-check2-circle"></i> Seguro</span>
                                @endif
                            </td>

                            <td class="text-center pe-4">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="badge {{ $ganancia >= 0 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3">
                                        $ {{ number_format($ganancia, 2) }}
                                    </span>
                                    @php $utilidad = $ventaSuma > 0 ? ($ganancia / $ventaSuma) * 100 : 0; @endphp
                                    <small class="text-muted mt-1" style="font-size: 0.7rem;">{{ number_format($utilidad, 1) }}% margen</small>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-dark text-white border-top border-3 border-flower-pink">
                    <tr class="align-middle">
                        <td class="ps-4 py-3">
                            <span class="text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.8rem;">Balance Global</span>
                        </td>
                        <td class="text-center py-3 text-white-50">$ {{ number_format($globalInversion, 2) }}</td>
                        <td class="text-center py-3 text-success fs-5 fw-bold">$ {{ number_format($globalVenta, 2) }}</td>
                        <td class="text-center py-3">
                            <div class="text-warning small text-uppercase fw-bold">Riesgo Total</div>
                            <div class="fs-5">$ {{ number_format($globalRiesgo, 2) }}</div>
                        </td>
                        <td class="text-center pe-4 py-3">
                            <div class="text-info small text-uppercase fw-bold">Utilidad Neta</div>
                            <div class="fs-5 fw-bold">$ {{ number_format($globalVenta - $globalInversion, 2) }}</div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>     
                                
<div class="container mt-4 mt-md-5">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        
        {{-- Header: Responsivo (Columna en móvil, Fila en PC) --}}
        <div class="card-header bg-white py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <h5 class="fw-bold text-flower-dark m-0">🌸 Control de usuarios Florentica</h5>

                {{-- Buscador con ancho inteligente --}}
                <form action="{{ route('admin.dashboard') }}" method="GET" class="w-100" style="max-width: 400px;">
                    <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                        <input type="text" name="search_user" 
                               class="form-control border-0 ps-3" 
                               placeholder="Nombre, correo o rol..." 
                               value="{{ request('search_user') }}"
                               style="height: 45px; box-shadow: none;">
                        
                        <button class="btn btn-flower-pink px-4 border-0" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    {{-- Link para limpiar búsqueda en móvil --}}
                    @if(request('search'))
                        <div class="text-center mt-2 d-md-none">
                            <a href="{{ route('admin.dashboard') }}" class="text-muted small text-decoration-none">
                                <i class="bi bi-x-circle"></i> Limpiar filtro
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Nombre</th>
                        <th>Gmail</th>
                        <th>Rol</th>   
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($usuarios as $u)
                    <tr class="{{ $u->trashed() ? 'opacity-50 bg-light' : '' }}">
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-flower-pink d-flex align-items-center justify-content-center text-white fw-bold me-2"
                                     style="width: 40px; height: 40px; min-width: 40px; font-size: 0.9rem;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="fw-bold d-block text-truncate" style="max-width: 150px;">{{ $u->name }}</span>
                                    @if($u->trashed())
                                        <small class="text-danger fw-bold">INACTIVO</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="small">{{ $u->email }}</td>
                        <td>
                            <span class="badge rounded-pill px-3 py-2 fw-bold 
                                {{ $u->role->value == 'admin' ? 'bg-danger text-white' : ($u->role->value == 'delivery' ? 'bg-warning text-dark' : 'bg-info text-dark') }}">
                                {{ strtoupper($u->role->value == 'buyer' ? 'cliente' : ($u->role->value == 'delivery' ? 'repartidor' : 'administrador')) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            @if(!$u->trashed())
                                <button type="button" class="btn btn-sm btn-light rounded-circle shadow-sm border" data-bs-toggle="modal" data-bs-target="#editModal{{ $u->id }}">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                <form action="{{ route('usuarios.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Desactivar acceso?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light rounded-circle shadow-sm border">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('usuarios.restore', $u->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                                    </button>
                                </form>
                            @endif
                        </td>

                        {{-- MODAL DE EDICIÓN (Ajustado para no bloquear el fondo) --}}
                        <div class="modal fade" id="editModal{{ $u->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 border-0 shadow-lg">
                                    <div class="modal-header border-0">
                                        <h5 class="fw-bold text-flower-dark m-0">Editar: {{ $u->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('usuarios.update', $u->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3 text-start">
                                                <label class="form-label small fw-bold">Nombre Completo</label>
                                                <input type="text" name="name" class="form-control rounded-3" value="{{ $u->name }}" required>
                                            </div>
                                            <div class="mb-3 text-start">
    <label class="form-label small fw-bold text-muted">Correo Electrónico (No editable)</label>
    <input type="email" 
           name="email" 
           class="form-control rounded-3 bg-light text-muted" 
           value="{{ $u->email }}" 
           readonly 
           style="cursor: not-allowed; border-style: dashed;">
</div>
                                            <div class="mb-3 text-start">
                                                <label class="form-label small fw-bold">Asignar Rol</label>
                                                <select name="role" class="form-select rounded-3">
                                                    <option value="admin" {{ $u->role->value == 'admin' ? 'selected' : '' }}>Administrador</option>
                                                    <option value="delivery" {{ $u->role->value == 'delivery' ? 'selected' : '' }}>Repartidor</option>
                                                    <option value="buyer" {{ $u->role->value == 'buyer' ? 'selected' : '' }}>Cliente</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-flower-pink rounded-pill px-4">Guardar Cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-person-x d-block fs-1 mb-2"></i>
                            No se encontraron resultados para <span class="fw-bold">"{{ request('search') }}"</span>
                            <br>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-link mt-2">Ver todos los usuarios</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>   








<div class="container mt-5">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="d-flex flex-column flex-sm-row align-items-center gap-3 w-100 w-md-auto">
                    <h5 class="fw-bold text-flower-dark m-0">📦 Control de lotes - Florentica</h5>
                    <a href="/admin/lotes/nuevo" class="btn-florentica3 border-flower-pink px-4 py-2 shadow-sm text-center text-decoration-none">
                        <i class="bi bi-plus-circle me-1"></i> Crear lote
                    </a>
                </div>

                <form action="{{ route('admin.dashboard') }}" method="GET" class="w-100" style="max-width: 400px;">
                    <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                        <input type="text" name="search_lote" class="form-control border-0 ps-3" 
                               placeholder="Nombre, lote o fecha (DD/MM/AAAA)..." value="{{ request('search_lote') }}">
                        <button class="btn btn-flower-pink px-4 border-0" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                    @if(request('search_lote'))
                        <div class="text-center mt-1">
                            <a href="{{ route('admin.dashboard') }}" class="text-muted extra-small text-decoration-none" style="font-size: 0.7rem;">
                                <i class="bi bi-x-circle"></i> Limpiar filtro
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-muted text-uppercase">
                        <th class="text-start ps-4" style="width: 40%;">Producto y Detalle de Lotes</th>
                        <th class="text-center">Fecha Ingreso</th>
                        <th class="text-center">Stock total</th>
                        <th class="text-center" style="width: 20%;">Estado / Vida</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($insumos as $insumo)
                        @php 
                            $originalSearch = request('search_lote');
                            $lotesParaMostrar = $insumo->lotes()->withTrashed()->get();
                            
                            if ($originalSearch) {
                                $cleanNumber = preg_replace('/[^0-9]/', '', $originalSearch);
                                $busquedaLimpia = str_replace(['_', ' '], ' ', strtolower(trim($originalSearch)));
                                $nombreInsumoDB = str_replace(['_', ' '], ' ', strtolower($insumo->nombre_insumo));
                                
                                if (!str_contains($nombreInsumoDB, $busquedaLimpia)) {
                                    $lotesParaMostrar = $lotesParaMostrar->filter(function($lote) use ($cleanNumber) {
                                        return ($cleanNumber && $lote->id == $cleanNumber);
                                    });
                                }
                            }
                        @endphp
                        <tr>
                            <td class="align-middle ps-4">
                                <div class="fw-bold text-dark fs-5">{{ str_replace('_', ' ', $insumo->nombre_insumo) }}</div>
                                
                                <div class="mt-2">
                                    @foreach($lotesParaMostrar as $lote)
                                        @php $esBaja = $lote->trashed(); @endphp
                                        <div class="d-flex align-items-center justify-content-between p-2 mb-1 rounded border-start border-3 {{ $esBaja ? 'bg-secondary-subtle border-secondary opacity-75' : 'bg-light border-flower-pink shadow-sm' }}">
                                            
                                            <div class="d-flex flex-column">
                                                <small class="{{ $esBaja ? 'text-decoration-line-through text-muted' : '' }}">
                                                    <strong>Lote #{{ $lote->id }}</strong>: 
                                                    {{ $lote->cantidad_actual }} {{ $insumo->tipo == 'flor' ? 'flores' : $insumo->unidad_medida }}
                                                </small>
                                                <div class="mt-1 d-flex gap-1">
                                                    <span class="badge rounded-pill bg-white text-dark border px-2 py-1" style="font-size: 0.65rem;">
                                                        <i class="bi bi-tag-fill text-flower-pink me-1"></i>
                                                        $ {{ number_format($lote->precio_venta_lote ?? 0, 2) }}
                                                    </span>
                                                    @if($lote->mermas_count > 0)
                                                        <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-2 py-1" style="font-size: 0.65rem;">
                                                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Merma: {{ $lote->mermas->sum('cantidad') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="d-flex gap-2 pe-2">
                                                @if(!$esBaja && $lote->cantidad_actual > 0)
                                                    <button type="button" class="btn btn-link text-warning p-0 m-0" 
                                                            data-bs-toggle="modal" data-bs-target="#mermaLote{{ $lote->id }}" title="Reportar Merma">
                                                        <i class="bi bi-droplet-half" style="font-size: 0.9rem;"></i>
                                                    </button>
                                                @endif

                                                <button type="button" class="btn btn-link text-primary p-0 m-0" data-bs-toggle="modal" data-bs-target="#editLote{{ $lote->id }}">
                                                    <i class="bi bi-pencil-square" style="font-size: 0.9rem;"></i>
                                                </button>

                                                @if($esBaja)
                                                    <form action="{{ route('admin.lotes.restore', $lote->id) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="btn btn-link text-success p-0 m-0" title="Restaurar">
                                                            <i class="bi bi-arrow-counterclockwise" style="font-size: 0.95rem;"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.lotes.destroy', $lote->id) }}" method="POST" onsubmit="return confirm('¿Archivar Lote #{{ $lote->id }}?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger p-0 m-0">
                                                            <i class="bi bi-trash3-fill" style="font-size: 0.85rem;"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="modal fade" id="editLote{{ $lote->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content rounded-4 shadow border-0">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="fw-bold text-flower-dark">Editar Lote #{{ $lote->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.lotes.update', $lote->id) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <div class="modal-body text-start">
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold text-muted">Cantidad Actual</label>
                                                                <input type="number" name="cantidad_actual" class="form-control rounded-3" value="{{ $lote->cantidad_actual }}" required min="0">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold text-muted">Precio de Venta (c/u)</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-light border-end-0">$</span>
                                                                    <input type="number" step="0.01" name="precio_venta_lote" class="form-control rounded-3 border-start-0" value="{{ $lote->precio_venta_lote }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold text-muted">Fecha de Vencimiento</label>
                                                                <input type="date" name="fecha_vencimiento" class="form-control rounded-3" 
                                                                       value="{{ $lote->fecha_vencimiento ? \Carbon\Carbon::parse($lote->fecha_vencimiento)->format('Y-m-d') : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-flower-pink rounded-pill px-4 text-white">Guardar Cambios</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="mermaLote{{ $lote->id }}" tabindex="1000" aria-labelledby="mermaLabel{{ $lote->id }}" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header border-0 pb-0 text-center d-block">
                <div class="text-warning mb-2">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 2rem;"></i>
                </div>
                <h6 class="fw-bold m-0" id="mermaLabel{{ $lote->id }}">Registrar Merma</h6>
                <p class="text-muted small">Lote #{{ $lote->id }} - {{ $insumo->nombre_insumo }}</p>
            </div>
            
            <form action="{{ route('admin.lotes.merma', $lote->id) }}" method="POST">
                @csrf
                <div class="modal-body py-3">
                    <div class="mb-3 text-start">
                        <label class="form-label small fw-bold">Cantidad de pérdida</label>
                        <input type="number" name="cantidad_merma" class="form-control text-center fw-bold border-flower-pink" 
                               max="{{ $lote->cantidad_actual }}" min="1" 
                               step="{{ $insumo->tipo == 'flor' ? '1' : '0.1' }}" required>
                        <div class="form-text text-center extra-small">Stock disponible: {{ $lote->cantidad_actual }}</div>
                    </div>
                    
                    <div class="mb-2 text-start">
                        <label class="form-label small fw-bold">Razón del reporte</label>
                        <select name="motivo" class="form-select form-select-sm rounded-3">
                            <option value="Marchitamiento">🥀 Marchitamiento</option>
                            <option value="Daño de Transporte">🚚 Daño Transporte</option>
                            <option value="Error de Inventario">📝 Error Inventario</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold shadow-sm py-2">
                        Confirmar Reporte
                    </button>
                    <button type="button" class="btn btn-link btn-sm w-100 text-muted text-decoration-none" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
                                    @endforeach
                                </div>
                            </td>

                            <td class="align-middle text-center">
                                @foreach($lotesParaMostrar as $lote)
                                    <div class="mb-1">
                                        <span class="badge bg-white border text-dark w-75 p-2 {{ $lote->trashed() ? 'opacity-50' : '' }}" style="font-size: 0.75rem;">
                                            {{ $lote->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                @endforeach
                            </td>

                            <td class="text-center align-middle">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <div class="fw-bold lh-1" style="color: #ff69b4; font-size: 2.5rem;">
                                        {{ $lotesParaMostrar->whereNull('deleted_at')->sum('cantidad_actual') }}
                                    </div>
                                    <span class="text-uppercase fw-bold text-muted mb-2" style="font-size: 0.6rem; letter-spacing: 1px;">
                                        Piezas Disponibles
                                    </span>

                                    @php 
                                        $valorActual = $lotesParaMostrar->whereNull('deleted_at')->sum(fn($l) => $l->cantidad_actual * $l->precio_venta_lote);
                                        $valorHistorico = $lotesParaMostrar->sum(fn($l) => $l->cantidad_actual * $l->precio_venta_lote);
                                    @endphp

                                    <div class="badge rounded-pill px-3 py-1 border shadow-sm mb-1" style="background-color: #fff0f5; color: #d02a77; border-color: #ffb6c1 !important; font-size: 0.8rem; width: 140px;">
                                        <i class="bi bi-shop me-1"></i> Stock: $ {{ number_format($valorActual, 2) }}
                                    </div>

                                    <div class="badge rounded-pill px-3 py-1 border" style="background-color: #f8f9fa; color: #6c757d; border-color: #dee2e6 !important; font-size: 0.7rem; width: 140px;">
                                        <i class="bi bi-clock-history me-1"></i> Total: $ {{ number_format($valorHistorico, 2) }}
                                    </div>
                                </div>
                            </td>

                            <td class="align-middle pe-4">
                                @if($insumo->tipo === 'flor')
                                    @foreach($lotesParaMostrar as $lote)
                                        <div class="mb-1">
                                            @php 
                                                $vencimiento = \Carbon\Carbon::parse($lote->getAttributes()['fecha_vencimiento'] ?? now())->startOfDay();
                                                $hoy = \Carbon\Carbon::today()->startOfDay();
                                                $dias = (int) $hoy->diffInDays($vencimiento, false);
                                            @endphp
                                            <span class="badge w-100 p-2 {{ $lote->trashed() ? 'bg-secondary' : ($dias < 0 ? 'bg-danger' : ($dias <= 2 ? 'bg-warning text-dark' : 'bg-success')) }}">
                                                @if($lote->trashed()) BAJA 
                                                @elseif($dias < 0) 🥀 {{ abs($dias) }} d vencido
                                                @elseif($dias == 0) ⚠️ ¡Hoy!
                                                @else {{ $dias }} días restantes @endif
                                            </span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center">
                                        <span class="badge bg-info text-white p-2 w-100">📦 PERMANENTE</span>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-5">No se encontraron lotes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
       
<div class="container mt-4 mt-md-5">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        
        {{-- Header: Estilo Florentica --}}
        <div class="card-header bg-white py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <h5 class="fw-bold text-flower-dark m-0">📦 Control de Insumos Florentica</h5>
            
            	<a href="{{ route('insumos.create') }}" 
   class="btn btn-sm btn-flower-purple rounded-pill px-3 shadow-sm hover-elevate">
    <i class="bi bi-cart-plus me-1"></i> + Registrar Nuevo
</a>

                {{-- Buscador de Insumos --}}
                <form action="{{ route('admin.dashboard') }}" method="GET" class="w-100" style="max-width: 400px;">
                    <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                        <input type="text" name="search_insumo" 
                               class="form-control border-0 ps-3" 
                               placeholder="Buscar flor, material..." 
                               value="{{ request('search_insumo') }}"
                               style="height: 45px; box-shadow: none;">
                        
                        <button class="btn btn-flower-pink px-4 border-0" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabla de Insumos --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Insumo</th>
                        <th>Categoría</th>
                        <th>Unidad de medida</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
               <tbody class="bg-white">
    @forelse($insumos as $insumo)
    <tr class="{{ $insumo->trashed() ? 'opacity-50 bg-light' : '' }}">
        {{-- ID --}}
        <td class="ps-4">
            <span class="badge bg-light text-muted rounded-pill border">#{{ $insumo->id }}</span>
        </td>

        {{-- Nombre --}}
        <td>
            <div class="d-flex align-items-center">
                <span class="fw-bold text-dark">{{ ucfirst($insumo->nombre_insumo) }}</span>
            </div>
        </td>

        {{-- Categoría con Colores Dinámicos --}}
        <td>
            @php
                $colorTipo = match($insumo->tipo) {
                    'flor' => 'bg-info-soft text-info',
                    'materia_prima' => 'bg-success-soft text-success',
                    'accesorio' => 'bg-warning-soft text-warning',
                    default => 'bg-light text-dark'
                };
            @endphp
            <span class="badge rounded-pill {{ $colorTipo }} px-3 fw-bold">
                <i class="bi bi-tag-fill me-1 small"></i>
                @if($insumo->tipo == 'materia_prima') Relleno 
                @elseif($insumo->tipo == 'accesorio') Empaque 
                @else {{ ucfirst($insumo->tipo) }} @endif
            </span>
        </td>  

        {{-- Unidad de medida con Colores Dinámicos --}}
        <td>
            @php
                $colorUnidad = match($insumo->unidad_medida) {
                    'tallos' => 'bg-purple-soft text-flower-pink',
                    'Paquetes' => 'bg-info-soft text-primary',
                    'Metros' => 'bg-danger-soft text-danger',
                    default => 'bg-light text-dark border'
                };
            @endphp
            <span class="badge rounded-pill {{ $colorUnidad }} px-3">
                {{ $insumo->unidad_medida }}
            </span>
        </td> 

        {{-- Acciones --}}
        <td class="text-end pe-4">
            <div class="d-flex justify-content-end gap-2">
                @if(!$insumo->trashed())
                    {{-- Botón Editar --}}
                    <button type="button" class="btn btn-sm btn-light rounded-circle shadow-sm border" data-bs-toggle="modal" data-bs-target="#editInsumo{{ $insumo->id }}">
                        <i class="bi bi-pencil text-primary"></i>
                    </button>

                    {{-- Botón Eliminar --}}
                    <form action="{{ route('insumos.destroy', $insumo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Desactivar este insumo?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light rounded-circle shadow-sm border">
                            <i class="bi bi-trash text-danger"></i>
                        </button>
                    </form>
                @else
                    {{-- Botón Restaurar --}}
                    <form action="{{ route('insumos.restore', $insumo->id) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Restaurar
                        </button>
                    </form>
                @endif
            </div>

            {{-- 📑 MODAL DE EDICIÓN --}}
<div class="modal fade text-start" id="editInsumo{{ $insumo->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            
            {{-- Header --}}
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-flower-dark m-0">Editar Insumo: {{ $insumo->nombre_insumo }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('insumos.update', $insumo->id) }}" method="POST">
                @csrf 
                @method('PUT')
                
                <div class="modal-body py-4">
                    {{-- Campo Nombre --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nombre del Insumo</label>
                        <input type="text" name="nombre_insumo" class="form-control rounded-3 bg-light border-0" value="{{ $insumo->nombre_insumo }}" required>
                    </div>

                    <div class="row">
                        {{-- Campo Categoría --}}
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Categoría</label>
                            <select name="tipo" class="form-select rounded-3 bg-light border-0">
                                <option value="flor" {{ $insumo->tipo == 'flor' ? 'selected' : '' }}>Flor</option>
                                <option value="materia_prima" {{ $insumo->tipo == 'materia_prima' ? 'selected' : '' }}>Relleno</option>
                                <option value="accesorio" {{ $insumo->tipo == 'accesorio' ? 'selected' : '' }}>Empaque</option>
                            </select>
                        </div>

                        {{-- Campo Unidad --}}
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Unidad</label>
                            <select name="unidad_medida" class="form-select rounded-3 bg-light border-0">
                                <option value="tallos" {{ $insumo->unidad_medida == 'tallos' ? 'selected' : '' }}>Tallos</option>
                                <option value="Paquetes" {{ $insumo->unidad_medida == 'Paquetes' ? 'selected' : '' }}>Paquetes</option>
                                <option value="Metros" {{ $insumo->unidad_medida == 'Metros' ? 'selected' : '' }}>Metros</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Footer con el botón de Actualizar --}}
                <div class="modal-footer border-0 pt-0 d-flex justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                   {{-- Usamos btn-primary o btn-flower-pink para que NO sea blanco --}}
                    <button type="submit" class="btn btn-light rounded-pill px-4 shadow-sm fw-bold">
                        <i class="bi bi-check-circle me-1"></i> Actualizar Insumo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="text-center py-5 text-muted">No hay insumos.</td>
    </tr>
    @endforelse
</tbody>
            </table>
        </div>
    </div>
</div>                                   
            
                                        
                                        
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Seleccionamos todos los inputs de búsqueda que existan
        const searchInputs = document.querySelectorAll('input[name="search"], input[name="search_insumo"]');
        
        searchInputs.forEach(input => {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    // Enviamos el formulario específico al que pertenece este input
                    this.closest('form').submit();
                }
            });
        });
    });
                     
                     
</script>
<script>
    // Este script mueve el modal al final del body para que nada lo tape
    document.addEventListener('DOMContentLoaded', function() {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            document.body.appendChild(modal);
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
</html>