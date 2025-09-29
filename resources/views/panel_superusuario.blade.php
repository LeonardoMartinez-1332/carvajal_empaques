<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Superusuario</title>

    <link rel="stylesheet" href="{{ asset('css/panel_superusuario.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="{{ asset('assets/Logo_Carvajal.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <header class="usuario-header">
        <img src="{{ asset('assets/Logo_Carvajal.png') }}" alt="Logo Empresa" class="logo-empresa">
        <div class="header-text">
            <h1>Panel de Control - Superusuario</h1>
            <p class="frase-bienvenida">Hola, {{ Auth::user()->nombre }}. Tienes acceso completo al sistema.</p>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</button>
        </form>
    </header>

    <main class="main-consulta">
        <section class="product-info">
            <h2>Accesos Rápidos</h2>
            <div class="info-box">
                <a href="{{ route('admin.panel') }}" class="btn-consultar"><i class="fas fa-users-cog"></i> Gestión de Usuarios</a>
                <a href="{{ route('bitacora.admin') }}" class="btn-consultar"><i class="fas fa-truck"></i> Bitácora de Camiones</a>
                <a href="{{ route('productos.panel') }}" class="btn-consultar"><i class="fas fa-box"></i> Gestión de Productos</a>
            </div>
        </section>

        <section class="product-info">
            <h2>Estadísticas Rápidas</h2>
            <div class="info-box">
                <p><strong>Usuarios Registrados:</strong> {{ DB::table('usuarios')->count() }}</p>
                <p><strong>Productos Activos:</strong> {{ DB::table('productos')->count() }}</p>
                <p><strong>Registros en Bitácora:</strong> {{ DB::table('bitacora_camiones')->count() }}</p>
            </div>
        </section>

        <section class="chart-section">
            <h2>Registros de Camiones por Día</h2>
            <canvas id="graficaBitacora" width="400" height="150"></canvas>
        </section>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const ctx = document.getElementById('graficaBitacora').getContext('2d');
            const dias = {!! json_encode($dias) !!};
            const registros = {!! json_encode($registros) !!};

            const grafica = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dias,
                    datasets: [{
                        label: 'Registros',
                        data: registros,
                        backgroundColor: '#00AAE4',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
