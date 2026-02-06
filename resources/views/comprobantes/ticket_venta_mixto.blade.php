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
        <img src="{{ public_path('storage/'.$config->imagen) }}" width="120" height="auto" />
        <h1>Puerto Escondido No. 407</h1>
        <h2>Col. Eliseo Jiménez Ruiz. Oaxaca, Oax.</h2>
        <h2>951 244 21 08 varinipaz@hotmail.com</h2>
        <h2>Lunes a Viernes</h2>
        <h2>10:00 a 14:00 y de 16:00 a 19:00 Hrs</h2>
        <h2>Sábado</h2>
        <h2>10:00 a 15:00 Hrs</h2>


        {{-- VENTAS DETALLADAS --}}
        @if ($ventas->count())
            <h2>--- VENTAS ---</h2>
            @foreach ($ventas as $venta)
                <br />
                <p><strong>Folio:</strong> {{ $venta->folio }}</p>
                <p><strong>Cliente:</strong> {{ $venta->cliente->full_name ?? 'CLIENTE PÚBLICO' }}</p>
                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}</p>

                <table>
                    <thead>
                        <tr>
                            <td colspan="3">-----------------------------------------------------------------------
                            </td>
                        </tr>
                        <tr class="centrado">
                            <th class="cantidad">CANT</th>
                            <th class="producto">PU.</th>
                            <th class="precio">IMP.</th>
                        </tr>
                        <tr>
                            <td colspan="3">-----------------------------------------------------------------------
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($venta->detalles as $detalle)
                            @if ($detalle->activo == 0)
                                <tr>
                                    <td colspan="3" class="text-red-600 text-2xl font-bold">REGISTRO ELIMINADO</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="producto uppercase">
                                    {{ $detalle->producto->nombre ?? ($detalle->servicioPonchado->ponchado->nombre ?? $detalle->producto_comun) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="cantidad">{{ $detalle->cantidad }}</td>
                                <td class="cantidad">${{ number_format($detalle->precio, 2) }}</td>
                                <td class="precio">${{ number_format($detalle->total, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    -----------------------------------------------------------------------</td>
                            </tr>
                        @endforeach
                    </tbody>
                    @php
                        $importe = $venta->detalles->sum(fn($d) => $d->total ?? 0);
                        $pagado = $venta->monto_recibido ?? 0;
                        $faltante = $importe - $pagado;
                    @endphp
                    <tr>
                        <td></td>
                        <td><strong>TOTAL: </strong></td>
                        <td>${{ number_format($importe, 2) }}</td>
                    </tr>
                    {{-- <tr>
                    <td></td>
                    <td><strong>TOTAL PAGADO; </strong></td>
                    <td>${{ number_format($pagado,2) }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td><strong>FALTANTE: </strong></td>
                    <td>${{ number_format($faltante,2) }}</td>
                </tr>
                --}}
                </table>
            @endforeach
        @endif

        {{-- PEDIDOS POR LIQUIDAR --}}
        @if ($pedidos->count())
            <h2>--- PEDIDOS POR PAGAR ---</h2>

            @php
                $granTotalPedido = 0;
                $granTotalAbonos = 0;
                $granTotalRestante = 0;
            @endphp

            @foreach ($pedidos as $ref => $items)
                @php
                    // total del pedido
                    $totalPedido = $items->sum(fn($d) => $d->subtotal ?? 0);

                    // total abonado
                    $totalAbonos = $items->sum(fn($d) => $d->formasPago->sum('monto'));

                    // restante por pagar
                    $restante = $totalPedido - $totalAbonos;

                    // acumular en gran total
                    $granTotalPedido += $totalPedido;
                    $granTotalAbonos += $totalAbonos;
                    $granTotalRestante += $restante;
                @endphp

                <br />
                <p><strong>Referencia:</strong> {{ $ref }}</p>
                <p><strong>Cliente:</strong> {{ $items->first()->cliente->full_name ?? 'CLIENTE PÚBLICO' }}</p>
                <p><strong>Fecha entrega:</strong>
                    {{ \Carbon\Carbon::parse($items->first()->fecha_estimada_entrega)->format('d/m/Y') }}</p>

                <table>
                    <thead>
                        <tr>
                            <td colspan="3">-----------------------------------------------------------------------
                            </td>
                        </tr>
                        <tr class="centrado">
                            <th class="cantidad">CANT</th>
                            <th class="producto">PU.</th>
                            <th class="precio">IMPORTE</th>
                        </tr>
                        <tr>
                            <td colspan="3">-----------------------------------------------------------------------
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $detalle)
                            <tr>
                                <td colspan="3" class="producto uppercase">
                                    {{ $detalle->ponchado->nombre }}
                                </td>
                            </tr>
                            <tr>
                                <td class="cantidad">{{ $detalle->cantidad_piezas }}</td>
                                <td class="cantidad">${{ number_format($detalle->precio_unitario ?? 0, 2) }}</td>
                                <td class="precio">${{ number_format($detalle->subtotal ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td><strong>TOTAL PEDIDO: </strong></td>
                            <td>${{ number_format($totalPedido, 2) }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><strong>TOTAL ABONADO: </strong></td>
                            <td>${{ number_format($totalAbonos, 2) }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><strong>RESTANTE: </strong></td>
                            <td>${{ number_format($restante, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            @endforeach

            {{-- Mostrar sumatoria total --}}
            <br><br>
            <h3>--- RESUMEN GENERAL ---</h3>
            <table>
                <tr>
                    <td><strong>TOTAL PEDIDOS: </strong></td>
                    <td>${{ number_format($granTotalPedido, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>TOTAL ABONADO: </strong></td>
                    <td>${{ number_format($granTotalAbonos, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>RESTANTE: </strong></td>
                    <td>${{ number_format($granTotalRestante, 2) }}</td>
                </tr>
            </table>
        @endif
    </div>


</body>

</html>
