@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-flower-pink text-white p-4 rounded-top-4">
                    <h4 class="mb-0 uppercase fw-black ">🌿 Registro de Catálogo (Insumos)</h4>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('insumos.store') }}" method="POST">
                        @csrf

                        {{-- SECCIÓN DE ALERTAS --}}
                        @if ($errors->any())
                            <div class="alert alert-danger shadow-sm border-0 rounded-4 animate__animated animate__shakeX">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li><i class="fas fa-exclamation-triangle me-2"></i> {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success shadow-sm border-0 rounded-4 animate__animated animate__fadeIn">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            </div>
                        @endif
                                
                        {{-- 1. IDENTIDAD DEL PRODUCTO --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Nombre de la Flor o Material</label>
                            <input type="text" name="nombre_insumo" value="{{ old('nombre_insumo') }}" 
                                   class="form-control form-control-lg rounded-3 @error('nombre_insumo') is-invalid @enderror" 
                                   placeholder="Ej: Rosa Roja Exportación" required>
                        </div>

                        <div class="row">
                            {{-- 2. CATEGORIZACIÓN ACTUALIZADA --}}
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-secondary">Categoría</label>
                                <select name="tipo" class="form-select form-select-lg rounded-3" required>
                                    <option value="">Seleccionar...</option>
                                     <option value="flor" {{ old('tipo') == 'flor' ? 'selected' : '' }}>🌸 Flor (Perecedero)</option>

                                    <option value="materia_prima" {{ old('tipo') == 'materia_prima' ? 'selected' : '' }}>🎀 Material (No 									 perecedero)</option>

                                    <option value="accesorio" {{ old('tipo') == 'accesorio' ? 'selected' : '' }}>🏺 Accesorio</option>
                                </select>
                            </div>

                            {{-- 3. UNIDAD DE MEDIDA --}}
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-secondary">Unidad de Medida</label>
                                <select name="unidad_medida" class="form-select form-select-lg rounded-3" required>
                                    <option value="tallos" {{ old('unidad_medida') == 'tallos' ? 'selected' : '' }}>Tallos (Flores)</option>
                                    <option value="paquetes" {{ old('unidad_medida') == 'paquetes' ? 'selected' : '' }}>Paquetes</option>
                                    <option value="metros" {{ old('unidad_medida') == 'metros' ? 'selected' : '' }}>Metros (Listones)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-text mb-4 text-muted text-center">
                            <i class="fas fa-info-circle me-1"></i> 
                            Los precios y el stock se gestionan desde el módulo de <strong>Lotes</strong>.
                        </div>

                        <hr class="my-4 text-secondary opacity-25">

                        <div class="d-grid gap-2b">
                            <button type="submit" class="btn btn-primary  bg-flower-pink btn-lg py-3 fw-bold shadow-sm uppercase tracking-wider">
                                ✨ Guardar en Catálogo
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-link  text-flower-pink text-decoration-none text-center">
                                <i class="fas fa-arrow-left me-1 text-flower-pink"></i> Cancelar y volver
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection