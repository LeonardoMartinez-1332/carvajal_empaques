<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="{{ asset('css/crear_usuario.css') }}">
    <link rel="icon" href="{{ asset('assets/Logo_Carvajal.png') }}" type="image/png">
</head>
<body>
    <div class="registro-wrapper">
        <div class="registro-logo">
            <img src="{{ asset('assets/Logo_Carvajal.png') }}" alt="Logo Carvajal">
        </div>

        <h2 class="registro-titulo">
            <span class="orange">Registrar</span>
            <span class="purple">Nuevo Usuario</span>
        </h2>

        <form action="{{ route('usuarios.guardar') }}" method="POST" class="registro-formulario">
            @csrf
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required>

            <label for="correo">Correo Electrónico:</label>
            <input type="email" name="correo" required>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" required>

            <label for="rol">Rol:</label>
            <select name="rol">
                <option value="usuario">Usuario</option>
                <option value="supervisor">Supervisor</option>
                <option value="superusuario">Superusuario</option>
            </select>

            <button type="submit">Crear Usuario</button>
        </form>

        <a href="{{ route('admin.panel') }}" class="btn-regresar">← Regresar</a
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#3085d6'
        });
    </script>
    @endif
</body>
</html>
