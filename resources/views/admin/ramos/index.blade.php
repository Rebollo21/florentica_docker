@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-black uppercase mb-4">🎨 Mesa de Armado de Ramos</h2>

    <div class="row">
        @foreach($productos as $producto)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <img src="{{ asset('storage/' . $producto->imagen) }}" class="card-img-top rounded-top-4" alt="{{ $producto->nombre }}">
                <div class="card-body">
                    <h5 class="fw-bold">{{ $producto->nombre }}</h5>
                    <p class="text-muted small">{{ $producto->descripcion }}</p>
                    
                    <hr>
                    
                    <form action="{{ route('ramos.armar') }}" method="POST">
    @csrf <input type="hidden" name="producto_id" value="{{ $producto->id }}">
    
    <div class="mb-3">
        <label class="small fw-bold">Cantidad:</label>
        <input type="number" name="cantidad" value="1" min="1" class="form-control">
    </div>

    <button type="submit" class="btn btn-dark w-100">
        ✨ Armar y Descontar Stock
    </button>
</form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection