<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Producto</title>

    <link rel="stylesheet" href="{{ asset('css/consulta-producto.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="{{ asset('assets/Logo_Carvajal.png') }}" type="image/png">
</head>
<body>
    <header class="usuario-header">
        <img src="{{ asset('assets/Logo_Carvajal.png') }}" alt="Logo Carvajal" class="logo-carvajal">
        <h1>Bienvenido a Carvajal Empaques</h1>
        <p class="frase-bienvenida">Hola, {{ Auth::check() ? Auth::user()->nombre : 'Usuario de prueba' }}.</p>

        @if(Auth::check() && Auth::user()->rol === 'supervisor')
            <a href="{{ route('supervisor.panel') }}" class="btn-regresar">
                <i class="fas fa-arrow-left"></i> Regresar al panel
            </a>
        @endif

        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
            </button>
        </form>

        
    </header>

    <main class="main-consulta">
        <section class="product-info">
            <h2>Introduzca el tipo de caja</h2>
            <form id="productForm">
                <input type="text" id="productCode" class="input-codigo" placeholder="Tipo de caja" required>
                <button type="submit" class="btn-consultar">Consultar</button>
            </form>

            <h2>Descripción del producto</h2>
            <div class="info-box" id="descripcion-articulo">Whataburger 32 oz</div>

            <h2>Cant. de cajas por cama</h2>
            <div class="info-box" id="cantidad-cajas">4 cajas</div>

            <h2>Cant. de camas por altura</h2>
            <div class="info-box" id="cantidad-camas">3 camas</div>

            <h2>Total de cajas por tarima</h2>
            <div class="info-box highlight" id="total-cajas">12 cajas</div>
        </section>

        <section class="product-image">
            <img src="{{ asset('assets/cajas-en-un-palet.jpg') }}" alt="Imagen de tarima con cajas" class="tarima-image">
            <button class="next-button">➡</button>
        </section>
    </main>

    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
