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



    <div class="ticket centrado">
        {{-- <img src="{{ $base64 }}" width="145" height="auto" /> --}}
        <h1>Puerto Escondido No. 407</h1>
        <h2>Col. Eliseo Jiménez Ruiz. Oaxaca, Oax.</h2>
        <h2>951 244 21 08  varinipaz@hotmail.com</h2>
        <h2>Lunes a Viernes</h2>
        <h2>10:00 a 14:00 y de 16:00 a 19:00 Hrs</h2>
        <h2>Sábado</h2>
        <h2>10:00 a 15:00 Hrs</h2>

        @foreach ($pedidos as $pedido)
            <p><strong>{{ $pedido->referencia_cliente }}</strong> </p>

            <p><strong>Cliente:
                {{ $pedido->cliente_id == 17
                    ? ($pedido->cliente_alias ?? 'CLIENTE PÚBLICO')
                    : ($pedido->cliente->full_name ?? 'CLIENTE PÚBLICO')
                }}
                </strong>
            </p>

            <p><strong>Fecha estimada de entrega:</strong> {{ \Carbon\Carbon::parse($pedido->fecha_estimada_entrega)->format('d/m/Y') }}</p>
            @break
        @endforeach


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
                @foreach ($pedidos as $pedido)
                    @if ($pedido->activo == 0)
                        <span class="text-red-600 text-2xl font-bold">REGISTRO ELIMINADO</span>
                    @endif

                    <tr>
                        <td class="producto uppercase" colspan="3">
                            {{ $pedido->ponchado->nombre ?? 'Sin nombre' }} |
                            {{ $pedido->clasificacionUbicacion->nombre }} |
                            {{ $pedido->prenda }}
                        </td>
                    </tr>
                    <tr>
                        <td class="cantidad">{{ $pedido->cantidad_piezas }}</td>
                        <td class="cantidad">{{ '$' . number_format($pedido->precio_unitario, 2, '.', ',') }}</td>
                        <td class="precio">{{ '$' . number_format(($pedido->cantidad_piezas * $pedido->precio_unitario), 2, '.', ',') }}</td>
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
                    ${{ number_format($totalVenta, 2) }}
                </td>
            </tr>

            <tr>
                <td class="cantidad"></td>
                <td class="producto">
                    <strong>TOTAL PAGADO</strong>
                </td>
                <td class="precio">
                    ${{ number_format($totalPagado, 2) }}
                </td>
            </tr>

            <tr>
                <td class="cantidad"></td>
                <td class="producto">
                    <strong>FALTANTE</strong>
                </td>
                <td class="precio">
                    ${{ number_format($totalFaltante, 2) }}
                </td>
            </tr>

        </table>





        {{--
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
                    <td class="producto" colspan="3">-----------------------------------------------------------------------</td>
                </tr>

                @foreach ($ventaArray['detalles'] as $detalle)

                    <tr>
                        <td class="producto uppercase" colspan="3">
                            @if ($detalle['tipo_item'] === 'PRODUCTO' || $detalle['tipo_item'] === 'SERVICIO')
                                {{ $detalle['producto']['nombre'] ?? 'N/A' }}
                            @elseif ($detalle['tipo_item'] === 'PONCHADO')
                                {{ $detalle['servicio_ponchado']['referencia_cliente'] ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="cantidad">{{ $detalle['cantidad'] }}</td>
                        <td class="cantidad">${{ number_format($detalle['precio'], 2) }}</td>
                        <td class="precio">${{ number_format($detalle['total'], 2) }}</td>
                    </tr>
                    <tr>
                        <td class="producto" colspan="3">-----------------------------------------------------------------------</td>
                    </tr>
                @endforeach
            </tbody>

            <tr>
                <td class="cantidad"></td>
                <td class="producto">
                    <strong>TOTAL</strong>
                </td>
                <td class="precio">
                    $<?php echo number_format($ventaArray['total'], 2); ?>
                </td>
            </tr>

        </table>
        <p class="centrado"><strong>
                @if (intval($opc->{'Leyenda inferior'}) == 1)
                    {{ $text1 }}
                @endif
            </strong>
        </p>
        --}}
    </div>
</body>

</html>
