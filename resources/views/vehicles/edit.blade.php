@extends('layouts.app')

@section('title', 'Editar Vehículo')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Editar Vehículo</h3>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">¡Hubo un error!</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST" id="vehicleEditForm"> {{-- ID del formulario para JS --}}
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="make" class="form-label">Marca:</label>
                <input type="text" class="form-control @error('make') is-invalid @enderror" id="make" name="make" value="{{ old('make', $vehicle->make) }}" required>
                @error('make')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="license_plate" class="form-label">Placa:</label>
                <input type="text" class="form-control @error('license_plate') is-invalid @enderror" id="license_plate" name="license_plate" value="{{ old('license_plate', $vehicle->license_plate) }}">
                @error('license_plate')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="color" class="form-label">Color:</label>
                <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color', $vehicle->color) }}" required>
                @error('color')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="model" class="form-label">Modelo (Año):</label>
                <input type="number" class="form-control @error('model') is-invalid @enderror" id="model" name="model" value="{{ old('model', $vehicle->model) }}" required min="1900" max="{{ date('Y') + 1 }}">
                @error('model')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="purchase_date" class="form-label">Fecha de Compra:</label> 
                <input type="date" class="form-control @error('purchase_date') is-invalid @enderror" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', $vehicle->purchase_date->format('Y-m-d') ?? '') }}"
                    required min = "1900-01-01" max = "{{ date('Y-m-d') }}">
                @error('purchase_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input @error('accident_report') is-invalid @enderror" id="accident_report" name="accident_report" value="1" {{ old('accident_report', $vehicle->accident_report) ? 'checked' : '' }}>
                <label class="form-check-label" for="accident_report">¿Ha tenido accidente?</label>
                @error('accident_report')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Botón para abrir el modal --}}
            <button type="button" class="btn btn-success" id="openConfirmEditModalBtn">Actualizar Vehículo</button>
            <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>

        {{-- Modal de Confirmación para Editar --}}
        <div class="modal fade" id="confirmEditModal" tabindex="-1" aria-labelledby="confirmEditModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmEditModalLabel">Confirmar Actualización del Vehículo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Por favor, revisa los datos antes de actualizar:</p>
                        <ul class="list-group list-group-flush" id="editVehicleDataList"></ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success" id="confirmEditBtn">Confirmar Actualización</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        const confirmModal = $('#confirmEditModal'); // Selector del modal
        const openConfirmModalBtn = $('#openConfirmEditModalBtn'); // Selector del botón que abre el modal
        const vehicleDataList = $('#editVehicleDataList'); // Selector del UL dentro del modal
        const confirmEditBtn = $('#confirmEditBtn'); // Selector del botón de confirmación dentro del modal
        const vehicleForm = $('#vehicleEditForm'); // Selector del formulario

        openConfirmModalBtn.on('click', function () {
            vehicleDataList.empty(); // Limpia la lista

            const formData = new FormData(vehicleForm[0]); // Obtener el elemento DOM nativo

            for (let [name, value] of formData.entries()) {
                let displayValue = value;
                let displayName = '';

                // Mapeo de nombres de campo a nombres
                switch(name) {
                    case 'make': displayName = 'Marca'; break;
                    case 'license_plate': displayName = 'Placa'; break;
                    case 'color': displayName = 'Color'; break;
                    case 'model': displayName = 'Modelo (Año)'; break;
                    case 'purchase_date':
                        displayName = 'Fecha de Compra';
                        // Asegúrate de que value no sea nulo antes de crear Date
                        if (value) {
                             displayValue = new Date(value).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' });
                        } else {
                            displayValue = 'No especificada';
                        }
                        break;
                    case 'accident_report':
                        displayName = 'Ha tenido accidente';
                        displayValue = value === '1' ? 'Sí' : 'No';
                        break;
                    case '_token':
                    case '_method':
                        continue; // Ignorar estos campos
                    default: displayName = name.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                }

                vehicleDataList.append(`<li class="list-group-item"><strong>${displayName}:</strong> ${displayValue}</li>`);
            }

            confirmModal.modal('show'); // Mostrar el modal
        });

        confirmEditBtn.on('click', function () {
            vehicleForm.submit(); // Enviar el formulario
        });
    });
</script>
@endpush