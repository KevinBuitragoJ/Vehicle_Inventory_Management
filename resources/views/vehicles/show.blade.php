@extends('layouts.app')

@section('title', 'Detalles del Vehículo')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Detalles del Vehículo {{ $vehicle->marca }} {{ $vehicle->modelo }}</h3>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
        {{--    <li class="list-group-item"><strong>ID:</strong> {{ $vehicle->id }}</li> --}}
            <li class="list-group-item"><strong>Marca:</strong> {{ $vehicle->make }}</li>
            <li class="list-group-item"><strong>Placa:</strong> {{ $vehicle->license_plate }}</li>
            <li class="list-group-item"><strong>Color:</strong> {{ $vehicle->color }}</li>
            <li class="list-group-item"><strong>Modelo (Año):</strong> {{ $vehicle->model }}</li>
            <li class="list-group-item"><strong>Fecha de Compra:</strong> {{ $vehicle->purchase_date->format('d/m/Y') }}</li>
            <li class="list-group-item"><strong>Ha tenido Accidente:</strong>
                @if ($vehicle->accident_report)
                    <span class="badge bg-danger">Sí</span>
                @else
                    <span class="badge bg-success">No</span>
                @endif
            </li>
            <li class="list-group-item"><strong>Creado el:</strong> {{ $vehicle->created_at->format('d/m/Y H:i') }}</li>
            <li class="list-group-item"><strong>Última Actualización:</strong> {{ $vehicle->updated_at->format('d/m/Y H:i') }}</li>
        </ul>
        <div class="mt-4">
            <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Volver a la Lista</a>
            <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-warning">Editar</a>
        </div>
    </div>
</div>
@endsection