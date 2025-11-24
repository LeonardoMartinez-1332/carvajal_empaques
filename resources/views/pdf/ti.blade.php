{{-- resources/views/pdf/ti.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>TI {{ $ti->folio ?? '' }}</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 11px;
        }
        body {
            margin: 25px;
            color: #222;
        }
        .header {
            border-bottom: 2px solid #004b8d;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .logo {
            float: left;
            width: 120px;
        }
        .logo img {
            max-width: 120px;
        }
        .title-block {
            margin-left: 140px;
            text-align: right;
        }
        .title-block h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            color: #004b8d;
        }
        .title-block small {
            display: block;
            margin-top: 4px;
            color: #555;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .section-title {
            font-weight: bold;
            margin-top: 14px;
            margin-bottom: 4px;
            font-size: 12px;
            color: #004b8d;
        }

        table.meta,
        table.detalle {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.meta td {
            padding: 3px 4px;
        }

        table.meta td.label {
            font-weight: bold;
            width: 22%;
        }

        table.detalle th,
        table.detalle td {
            border: 1px solid #ccc;
            padding: 4px 5px;
        }

        table.detalle th {
            background: #f0f4f8;
            text-align: left;
            font-size: 11px;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .firma-block {
            margin-top: 45px;
            width: 100%;
        }
        .firma-col {
            width: 33%;
            text-align: center;
            float: left;
        }
        .firma-line {
            border-top: 1px solid #000;
            margin: 0 auto 4px auto;
            width: 80%;
            height: 1px;
        }
        .firma-nombre {
            font-size: 10px;
            font-weight: bold;
        }
        .firma-rol {
            font-size: 9px;
            color: #555;
        }

        footer {
            position: fixed;
            bottom: 18px;
            left: 25px;
            right: 25px;
            border-top: 1px solid #ccc;
            padding-top: 3px;
            font-size: 9px;
            text-align: right;
            color: #777;
        }
    </style>
</head>
<body>

    {{-- ENCABEZADO --}}
    <div class="header clearfix">
        <div class="logo">
            {{-- Ajusta la ruta a donde tengas el logo en public/ --}}
            <img src="{{ public_path('assets/Logo-marcas-empaques-5.jpeg') }}" alt="Carvajal Empaques">
        </div>
        <div class="title-block">
            <h1>Transferencia Interna (TI)</h1>
            <small>ERP Carvajal Empaques</small>
            <small>Folio: <strong>{{ $ti->folio ?? 'SIN FOLIO' }}</strong></small>
        </div>
    </div>

    {{-- DATOS GENERALES --}}
    <div class="section-title">Datos generales</div>
    <table class="meta">
        <tr>
            <td class="label">Fecha:</td>
            <td>{{ $ti->fecha ?? now()->format('Y-m-d') }}</td>
            <td class="label">Creado por:</td>
            <td>{{ optional($ti->creadoPor)->nombre ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Almacén origen:</td>
            <td>{{ optional($ti->almacenOrigen)->nombre ?? '-' }}</td>
            <td class="label">Almacén destino:</td>
            <td>{{ optional($ti->almacenDestino)->nombre ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Comentario:</td>
            <td colspan="3">{{ $ti->comentario ?? '-' }}</td>
        </tr>
    </table>

    {{-- DETALLE DE LÍNEAS --}}
    <div class="section-title">Detalle de material</div>
    <table class="detalle">
        <thead>
            <tr>
                <th style="width: 12%;">Código</th>
                <th>Descripción</th>
                <th style="width: 7%;">Udm</th>
                <th style="width: 10%;">Tarimas</th>
                <th style="width: 12%;">Cajas x tarima</th>
                <th style="width: 10%;">Cajas totales</th>
                <th style="width: 12%;">Pz x caja</th>
                <th style="width: 12%;">Piezas totales</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ti->lineas as $linea)
                @php
                    $prod   = $linea->producto;
                    $cajas  = $linea->cajas ?? 0;
                    $pzCaja = $prod->pz_x_pt ?? 0;
                    $pzTot  = $cajas * $pzCaja;
                @endphp
                <tr>
                    <td>{{ $prod->codigo ?? '-' }}</td>
                    <td>{{ $prod->descripcion ?? '-' }}</td>
                    <td>{{ $prod->udm ?? 'PZ' }}</td>
                    <td class="text-right">{{ $linea->tarimas ?? 0 }}</td>
                    <td class="text-right">{{ $prod->cajas_por_tarima ?? '-' }}</td>
                    <td class="text-right">{{ $cajas }}</td>
                    <td class="text-right">{{ $pzCaja }}</td>
                    <td class="text-right">{{ $pzTot }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- FIRMAS --}}
    <div class="firma-block clearfix">
        <div class="firma-col">
            <div class="firma-line"></div>
            <div class="firma-nombre">{{ optional($ti->creadoPor)->nombre ?? '________________' }}</div>
            <div class="firma-rol">Analista de facturación (Jobs)</div>
        </div>
        <div class="firma-col">
            <div class="firma-line"></div>
            <div class="firma-nombre">&nbsp;</div>
            <div class="firma-rol">Responsable almacén origen</div>
        </div>
        <div class="firma-col">
            <div class="firma-line"></div>
            <div class="firma-nombre">&nbsp;</div>
            <div class="firma-rol">Responsable almacén destino</div>
        </div>
    </div>

    <footer>
        TI generada desde ERP Carvajal — {{ now()->format('d/m/Y H:i') }}
    </footer>
</body>
</html>
