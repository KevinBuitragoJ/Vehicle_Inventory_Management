@extends('layouts.app')

@section('title', 'Registrar Nuevo Vehículo')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Registrar Nuevo Vehículo</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('vehicles.store') }}" method="POST" id="vehicleForm">
            @csrf
            
            <div class="mb-3">
                <label for="make" class="form-label">Marca:</label>
                <input type="text" class="form-control @error('make') is-invalid @enderror" id="make" name="make" value="{{ old('make') }}" required>
                @error('make')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="license_plate" class="form-label">Placa:</label>
                <input type="text" class="form-control @error('license_plate') is-invalid @enderror" id="license_plate" name="license_plate" value="{{ old('license_plate') }}" required>
                @error('license_plate')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="color" class="form-label">Color:</label>
                <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color') }}" required>
                @error('color')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="model" class="form-label">Modelo (Año):</label>
                <input type="number" class="form-control @error('model') is-invalid @enderror" id="model" name="model" value="{{ old('model') }}" required min="1900" max="{{ date('Y') + 1 }}">
                @error('model')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="purchase_date" class="form-label">Fecha de Compra:</label>
                <input type="date" class="form-control @error('purchase_date') is-invalid @enderror" id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}"
                    required min="1900-01-01" max="{{ date('Y-m-d') }}">
                @error('purchase_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input @error('accident_report') is-invalid @enderror" id="accident_report" name="accident_report" value="1" {{ old('accident_report') ? 'checked' : '' }}>
                <label class="form-check-label" for="accident_report">¿Ha tenido accidente?</label>
                @error('accident_report')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="button" class="btn btn-success" id="openConfirmModalBtn">Guardar Vehículo</button>
            <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>

        {{-- Modal de Confirmación --}}
        <div class="modal fade" id="confirmSaveModal" tabindex="-1" aria-labelledby="confirmSaveModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmSaveModalLabel">Confirmar Datos del Vehículo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Por favor, revisa los datos antes de guardar:</p>
                        <ul class="list-group list-group-flush" id="vehicleDataList"></ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success" id="confirmSaveBtn">Confirmar Guardar</button>
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
        const confirmModal = $('#confirmSaveModal');
        const openConfirmModalBtn = $('#openConfirmModalBtn');
        const vehicleDataList = $('#vehicleDataList');
        const confirmSaveBtn = $('#confirmSaveBtn');
        const vehicleForm = $('#vehicleForm'); // Usar el ID 

        openConfirmModalBtn.on('click', function () {
            vehicleDataList.empty();

            const formData = new FormData(vehicleForm[0]);

            for (let [name, value] of formData.entries()) {
                let displayValue = value;
                let displayName = '';

                // Mapeo de nombres de campo 
                switch(name) {
                    case 'make': displayName = 'Marca'; break;
                    case 'license_plate': displayName = 'Placa'; break;
                    case 'color': displayName = 'Color'; break;
                    case 'model': displayName = 'Modelo (Año)'; break;
                    case 'purchase_date':
                        displayName = 'Fecha de Compra';
                        displayValue = new Date(value).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' });
                        break;
                    case 'accident_report':
                        displayName = 'Ha tenido accidente';
                        displayValue = value === '1' ? 'Sí' : 'No';
                        break;
                    default: displayName = name.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()); // Fallback para otros campos
                }

                if (name === '_token' || name === '_method') continue;

                vehicleDataList.append(`<li class="list-group-item"><strong>${displayName}:</strong> ${displayValue}</li>`);
            }

            confirmModal.modal('show');
        });

        confirmSaveBtn.on('click', function () {
            vehicleForm.submit();
        });
    });
</script>
@endpush