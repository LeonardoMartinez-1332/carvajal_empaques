<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitácora de Camiones</title>
    <link rel="stylesheet" href="{{ asset('css/bitacora.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="bitacora-container">
        <div class="header-flex">
            <div class="logo-container">
                <img src="{{ asset('assets/Logo_Carvajal.png') }}" alt="Logo Carvajal" class="logo">
            </div>
            <div class="buttons-bar">
                <a href="{{ route('supervisor.panel') }}" class="btn-volver">
                    <i class="fas fa-arrow-left"></i> Regresar al panel</a>
                <button id="mostrarFormulario" class="btn-registro">
                    <i class="fas fa-plus"></i> Registrar Camión
                </button>
            </div>
        </div>

        <div class="encabezado">
            <h2>Bitácora de Registro de Camiones</h2>
            <p class="bienvenida">Hola {{ Auth::user()->nombre }}, bienvenido al registro.</p>
        </div>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form id="formularioRegistro" action="{{ route('bitacora.guardar') }}" method="POST" style="display: none;">
            @csrf
            <div class="form-group">
                <label for="id_transp">Línea de transporte:</label>
                <select name="id_transp" id="id_transp" required>
                    <option value="">Selecciona una línea</option>
                    @foreach($camiones as $camion)
                        <option value="{{ $camion->id }}">{{ $camion->nom_linea }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="logistica">Logística:</label>
                <input type="text" name="logistica" required>
            </div>

            <div class="form-group">
                <label for="num_asn">Número ASN:</label>
                <input type="text" name="num_asn" required>
            </div>

            <div class="form-group">
                <label for="cantidad_tarimas">Cantidad de tarimas:</label>
                <input type="text" name="cantidad_tarimas" required>
            </div>

            <div class="form-group">
                <label for="hora_salida">Hora de salida:</label>
                <input type="time" name="hora_salida" required>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Guardar Registro
            </button>
        </form>

        <div class="historial">
            <h3>Historial de Registros</h3>
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora Llegada</th>
                        <th>Turno</th>
                        <th>Línea</th>
                        <th>Logística</th>
                        <th>ASN</th>
                        <th>Supervisor</th>
                        <th>Tarimas</th>
                        <th>Hora Salida</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registros as $registro)
                        <tr>
                            <td>{{ $registro->fecha }}</td>
                            <td>{{ $registro->hora_llegada }}</td>
                            <td>{{ $registro->turno->nombre_turno ?? 'N/A' }}</td>
                            <td>{{ $registro->camion->nom_linea ?? 'N/A' }}</td>
                            <td>{{ $registro->logistica }}</td>
                            <td>{{ $registro->num_asn }}</td>
                            <td>{{ $registro->supervisor->nombre ?? 'N/A' }}</td>
                            <td>{{ $registro->cantidad_tarimas }}</td>
                            <td>{{ $registro->hora_salida }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('mostrarFormulario').addEventListener('click', () => {
            const form = document.getElementById('formularioRegistro');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        });
    </script>
</body>
</html>
