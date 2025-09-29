function cargarEncabezadoYPie() {
    // Cargar encabezado dinámicamente
    fetch('header.html')
        .then(response => response.text())
        .then(html => {
            document.getElementById('header').innerHTML = html;

            // Una vez cargado el encabezado, inicializa el script de idioma
            inicializarIdioma();
        });

    // Cargar pie de página dinámicamente
    fetch('footer.html')
        .then(response => response.text())
        .then(html => {
            document.getElementById('footer').innerHTML = html;
        });
}

