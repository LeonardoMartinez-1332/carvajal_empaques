<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="{{ asset('css/editar-usuario.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="form-container">
        <div class="encabezado-admin">
            <div class="logo-titulo">
                <img src="{{ asset('assets/Logo_Carvajal.png') }}" alt="Logo Empresa" class="logo-empresa">
                <div class="texto-bienvenida">
                    <h2>Bienvenido, {{ Auth::user()->nombre }}</h2>
                    <p class="subtexto">Estás editando un usuario registrado</p>
                </div>
            </div>
            <a href="{{ route('admin.panel') }}" class="btn-volver"><i class="fas fa-arrow-left"></i> Volver al Panel</a>
        </div>

        <div class="form-card">
            <h2><i class="fas fa-user-edit"></i> Editar Usuario</h2>

            <form action="{{ route('usuarios.actualizar', $usuario->id) }}" method="POST">
                @csrf
                @method('PUT')

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>

                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" value="{{ old('correo', $usuario->correo) }}" required>

                <label for="rol">Rol:</label>
                <select name="rol" id="rol" required>
                    <option value="usuario" {{ $usuario->rol == 'usuario' ? 'selected' : '' }}>Usuario</option>
                    <option value="supervisor" {{ $usuario->rol == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                    <option value="superusuario" {{ $usuario->rol == 'superusuario' ? 'selected' : '' }}>Superusuario</option>
                </select>

                <div class="form-buttons">
                    <button type="submit" class="btn-save"><i class="fas fa-save"></i> Guardar Cambios</button>
                    <a href="{{ route('admin.panel') }}" class="btn-cancel"><i class="fas fa-times"></i> Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
