<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="{{ asset('css/gestion_producto.css') }}">
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
                <h2>Gestión de Productos</h2>
            </div>
            <div class="menu">
                <a href="{{ route('productos.crear') }}" class="btn-agregar"><i class="fas fa-box"></i> Agregar Producto</a>
            </div>
        </nav>

        <!-- Barra de búsqueda uniforme -->
        <div class="barra-busqueda">
            <form action="{{ route('productos.panel') }}" method="GET">
                <input type="text" name="buscar" placeholder="Buscar producto por ID o código..." value="{{ request('buscar') }}">
                <button type="submit"><i class="fas fa-search"></i> Buscar</button>
            </form>
        </div>


        <section class="admin-section">
            <h3>Lista de Productos</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Número de Caja</th>
                        <th>Código</th>
                        <th>Camas</th>
                        <th>Cajas por Cama</th>
                        <th>Total Cajas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                    <tr>
                        <td>{{ $producto->id }}</td>
                        <td>{{ $producto->num_caja }}</td>
                        <td>{{ $producto->codigo }}</td>
                        <td>{{ $producto->camas }}</td>
                        <td>{{ $producto->cajas_por_cama }}</td>
                        <td>{{ $producto->total_cajas }}</td>
                        <td>
                        <a href="{{ route('productos.editar', $producto->id) }}" class="btn-edit"><i class="fas fa-edit"></i> Editar</a>
                            <form action="{{ route('productos.eliminar', $producto->id) }}" method="POST" class="form-delete" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </div>

    @include('components.alerts')

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.form-delete').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Eliminar producto?',
                        text: 'Esta acción no se puede deshacer.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            const button = form.querySelector('button');
                            const icon = button.querySelector('i');
                            icon.classList.remove('fa-trash');
                            icon.classList.add('fa-spinner', 'fa-spin');
                            button.setAttribute('disabled', 'disabled');
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
