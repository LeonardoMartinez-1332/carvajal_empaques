<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Registro de Bitácora</title>
    <link rel="stylesheet" href="{{ asset('css/editar_bitacora.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="form-container">

        <div class="encabezado-admin">
            <div class="logo-titulo">
                <img src="{{ asset('assets/Logo_Carvajal.png') }}" alt="Logo Empresa" class="logo-empresa">
                <div class="texto-bienvenida">
                    <h2>Editar Registro de Bitácora</h2>
                    <p class="subtexto">Modifica la información del camión registrado</p>
                </div>
            </div>
            <a href="{{ route('bitacora.admin') }}" class="btn-volver"><i class="fas fa-arrow-left"></i> Volver</a>
        </div>

        <div class="form-card">
            <form action="{{ route('bitacora.actualizar', $bitacora->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Fecha:</label>
                    <input type="date" name="fecha" value="{{ $bitacora->fecha }}" required>
                </div>

                <div class="form-group">
                    <label>Hora de Llegada:</label>
                    <input type="time" name="hora_llegada" value="{{ $bitacora->hora_llegada }}" required>
                </div>

                <div class="form-group">
                    <label>Logística:</label>
                    <input type="text" name="logistica" value="{{ $bitacora->logistica }}" required>
                </div>

                <div class="form-group">
                    <label>Número ASN:</label>
                    <input type="text" name="num_asn" value="{{ $bitacora->num_asn }}" required>
                </div>

                <div class="form-group">
                    <label>ID Supervisor:</label>
                    <select name="id_supervisor">
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ $bitacora->id_supervisor == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label>Cantidad de Tarimas:</label>
                    <select name="cantidad_tarimas">
                        @for ($i = 1; $i <= 30; $i++)
                            <option value="{{ $i }}" {{ $bitacora->cantidad_tarimas == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label>Hora de Salida (opcional):</label>
                    <input type="time" name="hora_salida" value="{{ $bitacora->hora_salida }}">
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-guardar"><i class="fas fa-save"></i> Guardar Cambios</button>
                    <a href="{{ route('bitacora.admin') }}" class="btn-cancelar"><i class="fas fa-times"></i> Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#00A650'
        });
    </script>
    @endif

</body>
</html>
