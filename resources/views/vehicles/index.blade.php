@extends('layouts.app') @section('title', 'Lista de Vehículos') @section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Lista de Vehículos</h1>
    <a href="{{ route('vehicles.create') }}" class="btn btn-primary">Registrar Nuevo Vehículo</a>
</div>

<div class="card mb-4">
    <div class="card-header">
        Buscar Vehículos
    </div> <br>

    {{-- contador de vehículos--}}
    <div>
        &emsp;&emsp;Total de vehículos registrados: <strong>{{ $vehicleCount }}</strong>
    </div>
    <div class="card-body">

        {{-- Formulario de búsqueda  --}}
        <form action="{{ route('vehicles.index') }}" method="GET" class="form-inline my-2 my-lg-0">
            <div class="form-group mr-2">
                <label for="filter_by" class="sr-only">Filtrar por</label>
                <select class="form-control" id="filter_by" name="filter_by">
                    <option value="">Seleccionar Filtro</option>
                    <option value="make" {{ old('filter_by', request('filter_by')) == 'make' ? 'selected' : '' }}>Marca</option>
                    <option value="color" {{ old('filter_by', request('filter_by')) == 'color' ? 'selected' : '' }}>Color</option>
                    <option value="model" {{ old('filter_by', request('filter_by')) == 'model' ? 'selected' : '' }}>Modelo (Año)</option>
                </select>
            </div>
            <input class="form-control mr-sm-2" type="search" placeholder="Buscar..." aria-label="Search" name="search" value="{{ request('search') }}">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
            @if(request('search') || request('filter_by')) {{-- Muestra el botón de limpiar si hay algún filtro activo --}}
                <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary my-2 my-sm-0 ml-2">Limpiar Filtro</a>
            @endif
        </form>



    </div>
</div>

@if ($vehicles->isEmpty())
    <div class="alert alert-warning" role="alert">
        No se encontraron vehículos. ¡Sé el primero en registrar uno!
    </div>
@else
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                {{--    <th class="text-center">ID</th>  --}}
                    <th class="text-center">Marca</th>
                    <th class="text-center">Placa</th>
                    <th class="text-center">Color</th>
                    <th class="text-center">Modelo</th>
                    <th class="text-center">Fecha Compra</th>
                    <th class="text-center">Accidente</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vehicles as $vehicle)
                    <tr>
                    {{--    <td>{{ $vehicle->id }}</td>   --}}
                        <td>{{ $vehicle->make }}</td>
                        <td>{{ $vehicle->license_plate }}</td>
                        <td>{{ $vehicle->color }}</td>
                        <td>{{ $vehicle->model }}</td>
                        <td>{{ $vehicle->purchase_date->format('d/m/Y') }}</td>
                         <td>
                            @if ($vehicle->accident_report)
                                <span class="badge bg-danger">Sí</span>
                            @else
                                <span class="badge bg-success">No</span>
                            @endif
                        </td> 
                        <td class="d-flex justify-content-around">
                            <a href="{{ route('vehicles.show', $vehicle->id) }}" class="btn btn-info btn-sm me-1">Ver</a>
                            <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-warning btn-sm me-1">Editar</a>
                            <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" class="d-inline delete-form"> 
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal" data-item-name="{{ $vehicle->make }} ({{ $vehicle->license_plate }})" data-form-id="{{ $vehicle->id }}" >Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $vehicles->links() }}
    </div>
@endif

@endsection