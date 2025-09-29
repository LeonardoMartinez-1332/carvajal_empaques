<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="paginaTitulo">Blog</title>

    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="assets/Logo_Carvajal.png" type="image/png">
</head>

<body class="blog-body">

    <div id="header"></div> <!-- Encabezado dinámico -->

    <div class="blog-container">
        <main class="main-content">
            <h1 id="tituloBlog" class="titulo-blog">Blog</h1>

            <article class="blog-post">
                <img src="assets/quinoa-2.avif" alt="Título de la publicación" class="imagen-publicacion">
                <h2 id="tituloPost1" class="titulo-publicacion">Ensalada de Quinoa y Verduras</h2>
                <p id="textoPost1" class="resumen-publicacion">
                    Descripción: Esta ensalada fresca y nutritiva es perfecta para un
                    almuerzo ligero. La quinoa es rica en proteínas y fibra, lo que la convierte en una excelente opción
                    para mantener la energía durante el día. Ideal para llevar en frascos reutilizables.
                    <br><br>Ingredientes Destacados: Quinoa, pimiento rojo, pepino.
                </p>
                <a id="linkPost1" href="https://www.kiwilimon.com/receta/ensaladas/ensalada-de-quinoa-con-verduras"
                    class="enlace-ver-mas">Ver receta</a>
            </article>

            <article class="blog-post">
                <img src="assets/receta-1.png" alt="Título de la publicación" class="imagen-publicacion">
                <h2 id="tituloPost2" class="titulo-publicacion">Bowl de Frutas y Yogur</h2>
                <p id="textoPost2" class="resumen-publicacion">
                    Descripción: Un delicioso y nutritivo bowl que combina frutas frescas y
                    yogur, ideal para un desayuno saludable o un snack refrescante. Se puede empaquetar en envases de
                    cartón reciclables.
                    <br><br>Ingredientes Destacados: Frutas de temporada, yogur natural, granola.
                </p>
                <a id="linkPost2" href="https://www.recepedia.com/es-mx/receta/fruta/236528-bowl-de-yogurt-con-frutos-rojos/"
                    class="enlace-ver-mas">Ver receta</a>
            </article>
        </main>

        <aside class="sidebar">
            <h3 id="categoriasTitulo">Categorías</h3>
            <ul>
                <li><a id="categoria1" href="#">Recetas</a></li>
                <li><a id="categoria2" href="#">Innovaciones en empaques</a></li>
            </ul>

            <h3 id="archivoTitulo">Archivo</h3>
            <ul>
                <li><a id="archivo1" href="#">Octubre 2024</a></li>
                <li><a id="archivo2" href="#">Septiembre 2024</a></li>
            </ul>

            <h3 id="redesTitulo">Síguenos en redes</h3>
            <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
            <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
            <a href="https://www.twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
        </aside>
    </div>

    <div id="footer"></div> <!-- Pie de página dinámico -->

    <script src="main.js"></script>
    <script>
        cargarEncabezadoYPie(); // Llamar la función que carga el encabezado y pie de página
    </script>

    <script src="idioma.js"></script> <!-- Script para manejar traducciones -->

</body>

</html>
