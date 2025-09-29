<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="paginaTitulo">Convermex México</title>

    <link rel="stylesheet" href="estilos.css"> <!-- Se manda a llamar estilos.css -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Extensión para mostrar iconos de las redes sociales -->

    <link rel="icon" href="assets/Logo_Carvajal.png" type="image/png">
    <!-- Extensión para mostrar la imagen del logo de la empresa al momento de cargar la página -->
</head>

<body>
    <!-- Encabezado -->
    <div id="header"></div> <!-- Aquí se cargará dinámicamente el encabezado -->

    <main>
        <!-- Contenido de la página principal -->
        <div id="content">
            <article class="article">
                <header>
                    <div class="imagen-fondo">
                        <h1 id="tituloPrincipal">Carvajal Empaques</h1>
                        <h2 id="subtituloPrincipal">"Soluciones de Empaques"</h2>
                    </div>
                </header>
                <p id="descripcionPrincipal">
                    Somos una empresa multinacional, especializada en el diseño, producción y distribución de soluciones
                    de envasado sostenibles, innovadoras y personalizadas para los mercados industriales, de servicios
                    de
                    alimentos y domésticos.
                </p>
            </article>
        </div>
    </main>

    <!-- Pie de página -->
    <div id="footer"></div> <!-- Aquí se cargará dinámicamente el pie de página -->

    <!-- Llamar a la función para cargar el encabezado y pie de página -->
    <script src="main.js"></script> <!-- Enlace al archivo JavaScript -->
    <script>
        cargarEncabezadoYPie(); // Llamar la función que carga el encabezado y pie de página
    </script>

    <script src="idioma.js"></script> <!-- Script para manejar traducciones -->
</body>

</html>
