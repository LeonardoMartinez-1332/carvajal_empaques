<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="paginaTitulo">Misión y Visión</title>

    <link rel="stylesheet" href="estilos.css"> <!-- Enlazar archivo CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="assets/Logo_Carvajal.png" type="image/png">
</head>

<body class="body-mision">

    <div id="header"></div> <!-- Aquí se cargará dinámicamente el encabezado -->

    <main class="main-mision">
        <div id="content-mision">
            <article class="article-mision">
                <header>
                    <div class="imagen-mision">
                        <h1 id="tituloMisionVision" class="titulo-principal">Misión y Visión</h1>
                    </div>
                </header>

                <section class="seccion-mision">
                    <img src="assets/imagen-medio-ambiente.png" alt="Imagen Misión" class="imagen-seccion">
                    <h2 id="misionTitulo" class="titulo-seccion"><i class="fas fa-bullseye"></i> Misión</h2>
                    <p id="misionTexto" class="texto-seccion">
                        Proveer soluciones de empaque innovadoras y sostenibles que faciliten la
                        vida de las personas, garantizando calidad, eficiencia y compromiso con el medio ambiente.
                    </p>
                </section>

                <section class="seccion-vision">
                    <img src="assets/Imagen-Transformacion.png" alt="Imagen Visión" class="imagen-seccion">
                    <h2 id="visionTitulo" class="titulo-seccion"><i class="fas fa-eye"></i> Visión</h2>
                    <p id="visionTexto" class="texto-seccion">
                        Convertirnos en líderes globales en la industria del empaque, ofreciendo
                        productos sostenibles que contribuyan a un futuro más limpio y eficiente.
                    </p>
                </section>

            </article>
        </div>
    </main>

    <div id="footer"></div> <!-- Aquí se cargará dinámicamente el pie de página -->

    <script src="main.js"></script> <!-- Enlace al archivo JavaScript externo -->
    <script>
        cargarEncabezadoYPie();  // Llamar la función que carga el encabezado y pie de página
    </script>
    <script src="idioma.js"></script> <!-- Script para manejar traducciones -->

</body>

</html>
