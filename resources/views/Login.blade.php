<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="icon" href="{{ asset('assets/Logo_Carvajal.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="{{ asset('assets/Logo_Carvajal.png') }}" alt="Ilustración bienvenida" class="login-img">
            <h1>Bienvenido a Carvajal - Rama Dev</h1>
            <p class="tagline">Conectando soluciones, impulsando éxitos.</p>
        </div>

        <form class="login-form" action="{{ route('login.procesar') }}" method="POST">
            @csrf
            <h2>Inicio de Sesión</h2>

            @if(session('error'))
                <div class="error-msg">{{ session('error') }}</div>
            @endif

            <input type="email" name="correo" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required>

            <button type="submit"><i class="fas fa-sign-in-alt"></i> Entrar</button>
        </form>
    </div>
    @if(session('bloqueado'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Cuenta bloqueada',
            text: "{{ session('bloqueado') }}",
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#d33'
        });
    </script>
    @endif

</body>
</html>
