<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="{{ asset('css/crear_producto.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="tabla-container">
        <nav>
            <div class="logo-titulo">
                <img src="{{ asset('assets/Logo_Carvajal.png') }}" alt="Logo Empresa" class="logo-empresa">
                <h2>Agregar Nuevo Producto</h2>
            </div>
            <div class="menu">
                <a href="{{ url('/gestionar-productos') }}" class="btn-volver"><i class="fas fa-arrow-left"></i> Volver</a>
            </div>
        </nav>

        <section class="admin-section">
            <h3>Datos del Producto</h3>
            <form action="{{ route('productos.crear') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="num_caja">Número de Caja:</label>
                    <input type="text" id="num_caja" name="num_caja" required>
                </div>
                <div class="form-group">
                    <label for="codigo">Código:</label>
                    <input type="text" id="codigo" name="codigo" required>
                </div>
                <div class="form-group">
                    <label for="camas">Camas:</label>
                    <input type="number" id="camas" name="camas" min="1" required>
                </div>
                <div class="form-group">
                    <label for="cajas_por_cama">Cajas por Cama:</label>
                    <input type="number" id="cajas_por_cama" name="cajas_por_cama" min="1" required>
                </div>
                <div class="form-group">
                    <label for="total_cajas">Total de Cajas:</label>
                    <input type="number" id="total_cajas" name="total_cajas" min="1" required>
                </div>
                <button type="submit" class="btn-agregar"><i class="fas fa-plus"></i> Agregar Producto</button>
            </form>
        </section>
    </div>
</body>

</html>
