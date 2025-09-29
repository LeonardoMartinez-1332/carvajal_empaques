<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="{{ asset('css/editar_producto.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="form-container">

        <div class="encabezado-admin">
            <div class="logo-titulo">
                <img src="{{ asset('assets/Logo_Carvajal.png') }}" alt="Logo Empresa" class="logo-empresa">
                <div class="texto-bienvenida">
                    <h2>Editar Producto</h2>
                    <p class="subtexto">Modifica la información del producto registrado</p>
                </div>
            </div>
            <a href="{{ route('productos.panel') }}" class="btn-volver"><i class="fas fa-arrow-left"></i> Volver</a>
        </div>

        <div class="form-card">
            <form action="{{ route('productos.actualizar', $producto->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Número de Caja:</label>
                    <input type="text" name="num_caja" value="{{ $producto->num_caja }}" required>
                </div>

                <div class="form-group">
                    <label>Código:</label>
                    <input type="text" name="codigo" value="{{ $producto->codigo }}" required>
                </div>

                <div class="form-group">
                    <label>Camas:</label>
                    <input type="number" name="camas" value="{{ $producto->camas }}" required>
                </div>

                <div class="form-group">
                    <label>Cajas por Cama:</label>
                    <input type="number" name="cajas_por_cama" value="{{ $producto->cajas_por_cama }}" required>
                </div>

                <div class="form-group">
                    <label>Total de Cajas:</label>
                    <input type="number" name="total_cajas" value="{{ $producto->total_cajas }}" required>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-guardar"><i class="fas fa-save"></i> Guardar Cambios</button>
                    <a href="{{ route('productos.panel') }}" class="btn-cancelar"><i class="fas fa-times"></i> Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#00A650',
            timer: 2500,
            showConfirmButton: false
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{ session('error') }}",
            confirmButtonColor: '#F37021',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif

</body>
</html>