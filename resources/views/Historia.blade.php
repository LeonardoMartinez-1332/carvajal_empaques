<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="paginaTitulo">Nuestra Historia</title>

    <link rel="stylesheet" href="estilos.css"> <!-- Enlazar archivo CSS para los estilos -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="assets/Logo_Carvajal.png" type="image/png">
</head>

<body class="historia-body">

    <div id="header"></div> <!-- Aquí se cargará dinámicamente el encabezado -->

    <main class="historia-main">
        <div id="historia-content-mision">
            <article class="article-mision">
                <header>
                    <div class="imagen-mision">
                        <h1 id="tituloHistoria" class="titulo-principal">Historia</h1>
                    </div>
                </header>

                <!-- Secciones con IDs para traducción -->
                <section class="seccion-mision">
                    <img src="assets/imagen-h1.png.jpg" alt="imagen-h1" class="imagen-seccion">
                    <h2 id="inicioTitulo" class="titulo-seccion">Inicio</h2>
                    <p id="inicioTexto" class="texto-seccion">
                        La empresa Visipak S.A. se establece en el municipio de Ginebra, Colombia.
                        Esta fue la primera compañía.
                    </p>
                </section>

                <section class="seccion-vision">
                    <img src="assets/imagen-h2.png" alt="imagen-h2" class="imagen-seccion">
                    <h2 id="anos80Titulo" class="titulo-seccion">1980’s</h2>
                    <p id="anos80Texto" class="texto-seccion">
                        En Colombia, se instalan máquinas operativas de alta velocidad para
                        permitir que la empresa ingrese mayores ganancias.
                    </p>
                </section>

                <section class="seccion-mision">
                    <img src="assets/imagen-hpamolsa.png" alt="imagen-h3" class="imagen-seccion">
                    <h2 id="ano1996Titulo" class="titulo-seccion">1996.</h2>
                    <p id="ano1996Texto" class="texto-seccion">
                        Carvajal Empaques se convierte en el accionista mayoritario de la Compañía
                        Pamolsa ubicada en Caldas.
                    </p>
                </section>

                <section class="seccion-mision">
                    <img src="assets/imagen-h3.png" alt="imagen-h3" class="imagen-seccion">
                    <h2 id="ano2007Titulo" class="titulo-seccion">2007.</h2>
                    <p id="ano2007Texto" class="texto-seccion">
                        Se compra la Compañía llamada Termoformados Modernos ubicada en la
                        ciudad de Santa Tecla.
                    </p>
                </section>

                <section class="seccion-mision">
                    <img src="assets/imagen-h4.png" alt="imagen-h4" class="imagen-seccion">
                    <h2 id="ano2011Titulo" class="titulo-seccion">2011.</h2>
                    <p id="ano2011Texto" class="texto-seccion">
                        En Colombia, se compra el proceso productivo de los envases térmicos
                        desechables de la Compañía.
                    </p>
                </section>

                <section class="seccion-mision">
                    <img src="assets/imagen-h5.png" alt="imagen-h5" class="imagen-seccion">
                    <h2 id="ano2012Titulo" class="titulo-seccion">2012.</h2>
                    <p id="ano2012Texto" class="texto-seccion">
                        Carvajal Empaques se cotiza en el mercado de valores.
                    </p>
                </section>

                <section class="seccion-mision">
                    <img src="assets/imagen-h6.png" alt="imagen-h6" class="imagen-seccion">
                    <h2 id="ano2014Titulo" class="titulo-seccion">2014.</h2>
                    <p id="ano2014Texto" class="texto-seccion">
                        En Colombia, la planta ubicada en Tocancipá duplica su tamaño cuando las
                        operaciones de envasado incrementan sus ventas.
                    </p>
                </section>

                <section class="seccion-mision">
                    <img src="assets/imagen-h7.png" alt="imagen-h7" class="imagen-seccion">
                    <h2 id="ano2015Titulo" class="titulo-seccion">2015.</h2>
                    <p id="ano2015Texto" class="texto-seccion">
                        La planta de Carvajal Empaques se inaugura en Durán, Ecuador.
                    </p>
                </section>

                <section class="seccion-mision">
                    <img src="assets/imagen-h8.png" alt="imagen-h8" class="imagen-seccion">
                    <h2 id="ano2020Titulo" class="titulo-seccion">2020.</h2>
                    <p id="ano2020Texto" class="texto-seccion">
                        Estados Unidos, México y Centro América se unen en una misma Región
                        denominada Región Norte.
                    </p>
                </section>
            </article>
        </div>
    </main>

    <div id="footer"></div> <!-- Aquí se cargará dinámicamente el pie de página -->

    <script src="main.js"></script> <!-- Enlace al archivo JavaScript externo -->
    <script>
        cargarEncabezadoYPie(); // Llamar la función que carga el encabezado y pie de página
    </script>
    <script src="idioma.js"></script> <!-- Script para manejar traducciones -->

</body>

</html>
