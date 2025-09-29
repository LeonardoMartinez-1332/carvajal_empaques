<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="paginaTitulo">Aviso de Privacidad</title>

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
                    <div id="tituloAviso" class="titulo-principal">Aviso de Privacidad</div>
                </header>

                <section id="textoAviso" class="texto-seccion">
                    En cumplimiento con lo establecido por la Ley Federal de Protección de Datos Personales en Posesión
                    de los Particulares, su reglamento y los lineamientos aplicables a GRUPO CARVAJAL México bajo las
                    siguientes razones sociales:
                </section>

                <p id="textoGrupo1" class="texto-seccion">
                    GRUPO CONVERMEX, S.A DE C.V., con domicilio calle Entronque número 25, Col. Zona Industrial Oriente
                    Puebla, Puebla C.P. 72300 y portal de internet
                    <a id="enlaceGrupo1" href="https://mexico.carvajalempaques.com/aviso-de-privacidad-clientes-y-proveedores/"
                        style="color: black; text-decoration: none;">
                        https://mexico.carvajalempaques.com/aviso-de-privacidad-clientes-y-proveedores/
                    </a>
                </p>

                <p id="textoGrupo2" class="texto-seccion">
                    CARVAJAL EDUCACIÓN, S.A DE C.V., con domicilio calle Avenida 3, número 3, Col. Parque Industrial
                    Cartagena. C.P. 54900, Tultitlán de Mariano Escobedo, Estado de México, México y portal de internet
                    <a id="enlaceGrupo2" href="https://www.tiendanorma.com.mx/aviso-de-privacidad/"
                        style="color: black; text-decoration: none;">
                        https://www.tiendanorma.com.mx/aviso-de-privacidad/
                    </a>
                </p>

                <p id="textoGrupo3" class="texto-seccion">
                    CARVAJAL TECNOLOGÍA Y SERVICIOS, S.A DE C.V., con domicilio calle Bosque de Duraznos No.127 piso 12,
                    Col. Bosques de las Lomas, Ciudad de México, Alcaldía Miguel Hidalgo, C.P. 11700, México y portal de
                    internet
                    <a id="enlaceGrupo3" href="https://carvajaldigital.mx/aviso-de-privacidad/"
                        style="color: black; text-decoration: none;">
                        https://carvajaldigital.mx/aviso-de-privacidad/
                    </a>
                </p>

                <section id="textoClientes" class="texto-seccion">
                    En el caso de los Clientes:
                </section>

                <p id="textoDatos" class="texto-seccion">
                    Solicitaremos todos o algunos de los siguientes datos personales: Datos de identificación: nombre
                    completo, lugar y fecha de nacimiento, nacionalidad, edad, firma autógrafa, Acta Constitutiva,
                    Registro Federal de Contribuyentes (RFC), copia de identificación oficial (Credencial del Instituto
                    Nacional de Elector), referencias comerciales y/o bancarias. Datos laborales: categoría de empleado
                    y puesto, domicilio de oficinas corporativas y/o plantas industriales.
                </p>

                <p id="textoFinalidad" class="texto-seccion">
                    Finalidades. Sus Datos Personales serán utilizados para cualquiera de las siguientes finalidades
                    principales: Realizar e integrar un expediente de información y registrarlo en nuestra base de datos;
                    elaborar y celebrar contratos para la adquisición de nuestros productos y/o servicios; procesar,
                    completar y darle seguimiento a los productos y/o servicios requeridos por usted.
                </p>

                <p id="textoNoRecabados" class="texto-seccion">
                    Sus Datos Personales no serán recabados ni tratados para finalidades secundarias.
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
