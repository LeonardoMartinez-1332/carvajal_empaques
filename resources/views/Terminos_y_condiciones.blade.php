<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="paginaTitulo">Términos y Condiciones</title>

    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="assets/Logo_Carvajal.png" type="image/png">
</head>

<body>

    <!-- encabezado -->
    <div id="header"></div> <!-- Aquí se cargará dinámicamente el encabezado -->

    <main>
        <div id="historia-content-mision">
            <article class="article-mision">
                <header>
                    <div id="tituloTerminos" class="titulo-principal">Términos y Condiciones</div>
                </header>

                <p id="propietario" class="texto-seccion">
                    Propietario: GRUPO CONVERMEX S.A. de C.V., una sociedad mexicana, con domicilio principal en la ciudad de
                    México, Ciudad de México, identificada con clave de RFC GCO0303128G0, en adelante se denominará
                    «GRUPO CONVERMEX» y/o “ECOMMERCE” y/o “PLATAFORMA”
                </p>

                <p id="aceptacion" class="texto-seccion">
                    Mediante la inscripción, aceptación y/o conocimiento en cualquiera de sus modalidades y el diligenciamiento
                    de las solicitudes de Productos y/o Servicios que se ofrecen, y/o la aceptación electrónica expresa de
                    los presentes se entenderá como una aceptación lisa, llana e incondicional de estos Términos y Condiciones.
                </p>

                <p id="capacidad" class="texto-seccion">
                    Así mismo, al efectuar alguna de las actividades mencionadas anteriormente, los USUARIOS declaran que tienen
                    capacidad jurídica (incluida la mayoría de edad) y las facultades necesarias para obligarse en los presentes
                    Términos y Condiciones.
                </p>

                <p id="definiciones" class="texto-seccion">DEFINICIONES</p>

                <p id="plataforma" class="texto-seccion">
                    1.1 LA PLATAFORMA: es el sitio web comercial a través del cual ofrecerá a los USUARIOS el servicio
                    interactivo de intermediación de los Productos y/o Servicios propios o de terceros, por medio de la
                    publicación de ofertas y promoción de bienes.
                </p>

                <p id="usuarios" class="texto-seccion">
                    1.2 LOS USUARIOS O EL USUARIO: son los visitantes que previamente se han registrado en LA PLATAFORMA
                    WEB y que la utilizan para buscar, contactar y/o solicitar los Productos y/o Servicios del ECOMMERCE.
                </p>

                <p id="productos" class="texto-seccion">
                    1.3 LOS PRODUCTOS: Son los productos y/o Servicios que se ofrecen en la PLATAFORMA.
                </p>

                <p id="promociones" class="texto-seccion">
                    1.4 LAS PROMOCIONES: Son los incentivos a corto plazo que publica la PLATAFORMA para incrementar la
                    compra o la venta de los PRODUCTOS Y/O SERVICIOS.
                </p>

                <p id="contratos" class="texto-seccion">
                    1.5 CONTRATOS, SOLICITUD DE SERVICIO Y/O ÓRDENES DE COMPRA: Son aquellas compras o adquisiciones
                    de PRODUCTOS ordenadas por el USUARIO, referidas a los PRODUCTOS.
                </p>

                <p id="descripcion" class="texto-seccion">
                    DESCRIPCIÓN DE LOS SERVICIOS Y/O PRODUCTOS, ASPECTOS ASOCIADOS Y COBERTURA DE LA PLATAFORMA:
                    La PLATAFORMA presta servicios interactivos de intermediación de productos y/o servicios propios o
                    de terceros.
                </p>
            </article>
        </div>
    </main>

    <!-- pie de página -->
    <div id="footer"></div> <!-- Aquí se cargará dinámicamente el pie de página -->

    <!-- Llamar a la función para cargar el encabezado y pie de página -->
    <script src="main.js"></script>
    <script>
        cargarEncabezadoYPie(); // Llamar la función que carga el encabezado y pie de página
    </script>
    <script src="idioma.js"></script> <!-- Script para manejar traducciones -->
</body>

</html>
