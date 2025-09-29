<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Bitácora de Camiones</title>
    <link rel="stylesheet" href="{{ asset('css/bitacora_admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="tabla-container">

        <!-- Encabezado personalizado -->
        <div class="encabezado-admin">
            <h2>Bienvenido, {{ Auth::user()->nombre }}</h2>
            <a href="{{ route('superusuario.panel') }}" class="btn-volver"><i class="fas fa-arrow-left"></i> Volver al Panel</a>
        </div>

        <nav>
            <div class="logo-titulo">
                <img src="{{ asset('assets/Logo_Carvajal.png') }}" alt="Logo Empresa" class="logo-empresa">
                <h2>Administración de Bitácora de Camiones</h2>
            </div>
        </nav>

        <!-- Barra de búsqueda -->
        <form action="{{ route('bitacora.admin') }}" method="GET" class="search-container">
            <input type="text" name="buscar" placeholder="Buscar por ID, logística o fecha..." value="{{ request('buscar') }}">
            <button type="submit"><i class="fas fa-search"></i> Buscar</button>
        </form>

        <!-- Tabla de bitácora -->
        <section class="admin-section">
            <h3>Registros de la Bitácora</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Hora Llegada</th>
                        <th>Logística</th>
                        <th>Num ASN</th>
                        <th>Supervisor</th>
                        <th>Tarimas</th>
                        <th>Hora Salida</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bitacoras as $bitacora)
                    <tr>
                        <td>{{ $bitacora->id }}</td>
                        <td>{{ $bitacora->fecha }}</td>
                        <td>{{ $bitacora->hora_llegada }}</td>
                        <td>{{ $bitacora->logistica }}</td>
                        <td>{{ $bitacora->num_asn }}</td>
                        <td>{{ $bitacora->id_supervisor }}</td>
                        <td>{{ $bitacora->cantidad_tarimas }}</td>
                        <td>{{ $bitacora->hora_salida ?? 'Pendiente' }}</td>
                        <td>
                            <div class="acciones">
                                <a href="{{ route('bitacora.editar', $bitacora->id) }}" class="btn-edit">
                                    <i class="fas fa-edit"></i> Editar</a>
                                    <form action="{{ route('bitacora.eliminar', $bitacora->id) }}" method="POST" class="form-delete" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                        </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </div>

    @include('components.alerts')
    <script src="{{ asset('js/confirm-delete.js') }}"></script>
    <script src="{{ asset('js/acciones-loading.js') }}"></script>
    <script src="{{ asset('js/confirmaciones.js') }}"></script>
</body>
</html>
