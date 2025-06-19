<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Vehículos - @yield('title', 'Inicio')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('vehicles.index') }}">Gestión de Vehículos</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vehicles.index') }}">Ver Vehículos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vehicles.create') }}">Registrar Nuevo</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @yield('content')
    </div>

    {{-- Modal de Confirmación para Eliminar (NUEVA SECCIÓN) --}}
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar <strong id="deleteItemName">este elemento</strong>? Esta acción no se puede deshacer.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    
    {{-- Script del Modal de Eliminación  --}}
    <script>
        $(document).ready(function() {
            const confirmDeleteModal = $('#confirmDeleteModal');
            const confirmDeleteBtn = $('#confirmDeleteBtn');
            const deleteItemNameSpan = $('#deleteItemName');
            let formToDelete = null;

            confirmDeleteModal.on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget); // Botón que disparó el modal
                const itemName = button.data('item-name'); // Obtener el nombre del ítem
                const formId = button.data('form-id'); // Obtener el ID del formulario

                // Buscar el formulario específico usando el ID del vehículo
                formToDelete = $(`form.delete-form button[data-form-id="${formId}"]`).closest('form');

                // Actualizar el texto del modal
                deleteItemNameSpan.text(itemName);
            });

            confirmDeleteBtn.on('click', function() {
                if (formToDelete) {
                    formToDelete.submit(); // Enviar el formulario
                }
                confirmDeleteModal.modal('hide'); // Ocultar el modal después de la confirmación
            });

            confirmDeleteModal.on('hidden.bs.modal', function () {
                formToDelete = null; // Limpiar la referencia cuando el modal se oculta
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>