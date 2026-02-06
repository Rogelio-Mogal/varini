<?php
if ($userPrinterSize == '58') {
    $medidaTicket = 170; //180;685 para el de 58mm, 945 para el de 80mm
} elseif ($userPrinterSize == '80') {
    $medidaTicket = 270; //180;685 para el de 58mm, 945 para el de 80mm
}
?>
<!DOCTYPE html>
<html>

<head>

    <style>
        * {
            font-size: 9px;
            font-family: 'DejaVu Sans', serif;
        }

        h1 {
            font-size: 9px;
        }

        h2 {
            font-size: 9px;
        }

        .ticket {
            margin: 2px;
        }

        td,
        th,
        tr,
        table {
            border-top: 0px solid black;
            border-collapse: collapse;
            margin: 0 auto;
        }

        td.precio {
            text-align: right;
            font-size: 9px;
        }

        td.cantidad {
            font-size: 9px;
        }

        td.producto {
            text-align: left;
        }

        th {
            text-align: center;
        }


        .centrado {
            margin-top: 10px;
            text-align: center;
            align-content: center;
        }

        .textoGrande {
            font-size: 13px;
            font-weight: bold;
        }

        .ticket {
            width: <?php echo $medidaTicket; ?>px;
            max-width: <?php echo $medidaTicket; ?>px;
        }

        img {
            /*max-width: inherit;
            width: inherit;*/
        }

        * {
            margin: 0;
            padding: 0;
        }

        .ticket {
            margin: 0;
            padding: 0;
        }

        body {
            text-align: center;
        }

        .uppercase {
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    @php
        $detalle = $venta->detalles->first();
        $servicio = $detalle?->servicioPonchado;
    @endphp

    <div class="ticket centrado">
        <img src="{{ public_path('storage/'.$config->imagen) }}" width="120" height="auto" />
        <h1>Puerto Escondido No. 407</h1>
        <h2>Col. Eliseo Jiménez Ruiz. Oaxaca, Oax.</h2>
        <h2>951 244 21 08  varinipaz@hotmail.com</h2>
        <h2>Lunes a Viernes</h2>
        <h2>10:00 a 14:00 y de 16:00 a 19:00 Hrs</h2>
        <h2>Sábado</h2>
        <h2>10:00 a 15:00 Hrs</h2>

        {{-- Datos generales --}}
        <p><strong>Cliente:</strong>
            {{ $servicio && $servicio->cliente_id == 17
                ? ($servicio->cliente_alias ?? 'CLIENTE PÚBLICO')
                : ($venta->cliente?->full_name ?? 'CLIENTE PÚBLICO')
            }}
        </p>

        <p><strong>Folio:</strong> {{ $venta->folio }}</p>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}</p>


        <table>
            <thead>
                <tr>
                    <td class="producto" colspan="3">
                        -----------------------------------------------------------------------</td>
                </tr>
                <tr class="centrado">
                    <th class="cantidad">CANT</th>
                    <th class="producto">PU.</th>
                    <th class="precio">IMP.</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="producto" colspan="3">
                        -----------------------------------------------------------------------</td>
                </tr>
                @foreach ($venta->detalles as $detalle)

                    @if ($detalle->activo == 0)
                        <span class="text-red-600 text-2xl font-bold">REGISTRO ELIMINADO</span>
                    @endif

                    <tr>
                        <td class="producto uppercase" colspan="3">
                            {{-- Mostrar nombre según sea producto o servicio --}}
                            @if ($detalle->producto)
                                {{ $detalle->producto->nombre }}
                            @elseif ($detalle->servicioPonchado)
                                {{ $detalle->servicioPonchado->ponchado->nombre ?? 'Servicio Ponchado' }}
                            @else
                                {{ $detalle->producto_comun }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="cantidad">{{ $detalle->cantidad }}</td>
                        <td class="cantidad">{{ '$' . number_format($detalle->precio, 2, '.', ',') }}</td>
                        <td class="precio">{{ '$' . number_format($detalle->total, 2, '.', ',') }}</td>
                    </tr>
                    <tr>
                        <td class="producto" colspan="3">
                            -----------------------------------------------------------------------</td>
                    </tr>
                @endforeach
            </tbody>

            <tr>
                <td class="cantidad"></td>
                <td class="producto">
                    <strong>TOTAL</strong>
                </td>
                <td class="precio">
                    ${{ number_format($venta->total, 2) }}
                </td>
            </tr>

            <tr>
                <td class="cantidad"></td>
                <td class="producto">
                    <strong>TOTAL PAGADO</strong>
                </td>
                <td class="precio">
                    ${{ number_format($venta->monto_recibido, 2) }}
                </td>
            </tr>

            <tr>
                <td class="cantidad"></td>
                <td class="producto">
                    <strong>FALTANTE</strong>
                </td>
                <td class="precio">
                    ${{ number_format($venta->total - $venta->monto_recibido, 2) }}
                </td>
            </tr>

        </table>
    </div>
</body>

</html>
