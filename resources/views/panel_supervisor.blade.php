<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Supervisor</title>
    <link rel="stylesheet" href="{{ asset('css/supervisor.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="{{ asset('assets/Logo_Carvajal.png') }}" type="image/png">
</head>
<body>
    <div class="panel-container">
        <div class="encabezado">
            <img src="{{ asset('assets/Logo_Carvajal.png') }}" alt="Logo Carvajal" class="logo-carvajal">
            <h2>Bienvenido a Carvajal Empaques</h2>
            <p class="saludo">¡Hola {{ Auth::check() ? Auth::user()->nombre : 'Invitado' }}, que tengas un excelente día!</p>
        </div>

        <div class="acciones">
            <a href="{{ route('consulta.producto') }}" class="btn-panel">
                <i class="fas fa-search"></i> Consultar Productos
            </a>

            <a href="{{ route('bitacora.panel') }}" class="btn-panel">
                <i class="fas fa-truck"></i> Bitácora de Camiones
            </a>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </button>
        </form>
    </div>
</body>
</html>
