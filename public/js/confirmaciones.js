// confirmaciones.js

// Esperar a que el DOM cargue
window.addEventListener('DOMContentLoaded', () => {
    const formularios = document.querySelectorAll('form[data-confirm]');

    formularios.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevenir envío inmediato

            Swal.fire({
                title: '¿Estás seguro?',
                text: form.getAttribute('data-confirm') || 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Enviar si confirma
                }
            });
        });
    });
});
