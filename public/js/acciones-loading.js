
document.addEventListener("DOMContentLoaded", function () {
    // Botón Editar
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
            const icon = this.querySelector('i');
            icon.classList.remove('fa-edit');
            icon.classList.add('fa-spinner', 'fa-spin');
            this.setAttribute('disabled', 'disabled');
        });
    });

    // Botón Guardar
    const formGuardar = document.querySelector('form.guardar-form');
    if (formGuardar) {
        formGuardar.addEventListener('submit', function () {
            const btnGuardar = formGuardar.querySelector('button[type="submit"]');
            const icon = btnGuardar.querySelector('i');
            icon.classList.remove('fa-save');
            icon.classList.add('fa-spinner', 'fa-spin');
            btnGuardar.setAttribute('disabled', 'disabled');
        });
    }

    // Botón Eliminar (después de confirmar)
    const deleteForms = document.querySelectorAll('.form-delete');
    deleteForms.forEach(form => {
        const button = form.querySelector('button');
        form.addEventListener('submit', function () {
            const icon = button.querySelector('i');
            icon.classList.remove('fa-trash');
            icon.classList.add('fa-spinner', 'fa-spin');
            button.setAttribute('disabled', 'disabled');
        });
    });
});
