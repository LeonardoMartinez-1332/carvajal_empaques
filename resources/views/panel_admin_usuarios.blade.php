<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración de Usuarios</title>
    <link rel="stylesheet" href="{{ asset('css/panel_admin_usuarios.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="tabla-container">

        <div class="encabezado-admin">
            <h2>Bienvenido, {{ Auth::user()->nombre }}</h2>
            <a href="{{ route('superusuario.panel') }}" class="btn-volver"><i class="fas fa-arrow-left"></i> Volver al Panel</a>
        </div>

        <nav>
            <div class="logo-titulo">
                <img src="{{ asset('assets/Logo_Carvajal.png') }}" alt="Logo Empresa" class="logo-empresa">
                <h2>Panel de Administración de Usuarios</h2>
            </div>
            <div class="menu">
                <button><a href="{{ url('/crear-usuario') }}"><i class="fas fa-user-plus"></i> Crear Usuario</a></button>
            </div>
        </nav>

        <form action="{{ route('admin.panel') }}" method="GET" class="search-container">
            <input type="text" name="buscar" placeholder="Buscar usuario por ID, nombre o correo..." value="{{ request('buscar') }}">
            <button type="submit"><i class="fas fa-search"></i> Buscar</button>
        </form>

        <section class="admin-section">
            <h3>Usuarios Registrados</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->correo }}</td>
                        <td>{{ ucfirst($usuario->rol) }}</td>
                        <td>
                            <a href="{{ url('/usuarios/' . $usuario->id . '/editar') }}" class="btn-edit">
                                <i class="fas fa-edit"></i> Editar
                            </a>

                            <form action="{{ route('usuarios.eliminar', $usuario->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>

                            @if($usuario->bloqueado)
                            <form action="{{ route('usuarios.desbloquear', $usuario->id) }}" method="POST" class="form-desbloquear" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-unblock">
                                    <i class="fas fa-lock-open"></i> Desbloquear
                                </button>
                            </form>
                            @endif

                            <form action="{{ route('usuarios.resetear', $usuario->id) }}" method="POST" class="form-resetear" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-reset">
                                    <i class="fas fa-key"></i> Resetear Contraseña
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </div>

    <!-- SweetAlert2 Scripts -->
    <script>
        document.querySelectorAll('.form-resetear').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Resetear contraseña?',
                    text: 'Se establecerá como: 12345678',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ffc107',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, resetear',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        document.querySelectorAll('.form-desbloquear').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Desbloquear usuario?',
                    text: 'Se restaurarán los intentos fallidos',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#17a2b8',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, desbloquear',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>
</html>
